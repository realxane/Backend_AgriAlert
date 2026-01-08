<?php

namespace App\Services;

use App\Models\Champ;
use App\Models\Culture;
use App\Models\Recommandation;
use App\Enums\Saison;

class RecommandationEngine
{
    public function __construct(private WeatherService $weather) {}

    public function forCulture(Culture $culture, ?Champ $champ = null): array
    {
        $regionId = $champ?->region_id;

        // 1) Trouver la plantation "active" (celle en cours) pour ce champ et cette culture
        $plantation = $champ?->plantations()
            ->where('culture_id', $culture->id)
            ->whereDate('date_debut', '<=', now())
            ->where(function ($q) {
                $q->whereNull('date_fin')->orWhereDate('date_fin', '>=', now());
            })
            ->latest('date_debut')
            ->first();

        // 2) Calculer le nombre de jours depuis le début de la plantation
        $jours = $plantation ? now()->diffInDays($plantation->date_debut) : null;

        // 3) Récupérer la dernière activité agricole (Semis / Traitement / Recolte)
        $activite = $plantation?->activitesAgricoles()
            ->latest('done_at')
            ->value('type'); // string ou enum cast

        // 4) Récupérer la météo via OpenWeather (si champ a lat/lon)
        $meteo = ($champ && $champ->latitude && $champ->longitude)
            ? $this->weather->current($champ->latitude, $champ->longitude)
            : null;

        // 5) Déduire la saison (simple approximation) ou tu peux faire depuis la région
        $saison = $this->guessSaison();

        // 6) Charger les recommandations candidates depuis la DB
        $candidats = Recommandation::query()
            ->where('is_active', true)
            ->where('culture_id', $culture->id)

            // region : soit null (générique), soit la region du champ
            ->when($regionId, fn($q) => $q->where(function ($qq) use ($regionId) {
                $qq->whereNull('region_id')->orWhere('region_id', $regionId);
            }))

            // saison : soit null (toutes saisons), soit la saison actuelle
            ->where(function ($q) use ($saison) {
                $q->whereNull('saison')->orWhere('saison', $saison);
            })

            // activite_type : soit null (toutes activités), soit l’activité récente
            ->when($activite, fn($q) => $q->where(function ($qq) use ($activite) {
                $qq->whereNull('activite_type')->orWhere('activite_type', $activite);
            }))
            ->get();

        // 7) Scoring : donner un score à chaque reco et trier
        $ranked = $candidats->map(function ($rec) use ($jours, $meteo) {
            $score = 0;

            // 7.1 Priorité (plus petit priorite = plus important)
            $score += max(0, 300 - (int) $rec->priorite);

            // 7.2 Match stade (min_jour/max_jour) si on a les jours
            if (!is_null($jours)) {
                $min = $rec->min_jour;
                $max = $rec->max_jour;

                if (!is_null($min) && $jours < $min) $score -= 50;
                if (!is_null($max) && $jours > $max) $score -= 50;
                if (!is_null($min) && !is_null($max) && $jours >= $min && $jours <= $max) $score += 80;
            }

            // 7.3 Match météo (weather_rules)
            if ($meteo && is_array($rec->weather_rules)) {
                $score += $this->weatherMatchScore($meteo, $rec->weather_rules);
            }

            return [
                'id' => $rec->id,
                'titre' => $rec->titre,
                'contenu' => $rec->contenu,
                'score' => $score,
            ];
        })->sortByDesc('score')->values();

        // 8) Réponse finale pour le frontend
        return [
            'culture' => [
                'id' => $culture->id,
                'nom' => $culture->nom->value,
                'cycle_date' => $culture->cycle_date,
            ],
            'contexte' => [
                'jours_depuis_debut' => $jours,
                'activite_recente' => $activite?->value ?? $activite,
                'saison' => $saison?->value ?? $saison,
                'meteo' => $meteo,
            ],
            'recommandations' => $ranked->take(5),
        ];
    }

    private function guessSaison(): Saison
    {
        $m = (int) now()->format('n'); // 1..12
        // Mali (approx): hivernage ~ juin-sept
        return ($m >= 6 && $m <= 9) ? Saison::Hivernage : Saison::SaisonSeche;
    }

    private function weatherMatchScore(array $meteo, array $rules): int
    {
        $s = 0;

        if (isset($rules['temp_min']) && $meteo['temp'] < $rules['temp_min']) $s -= 30;
        if (isset($rules['temp_max']) && $meteo['temp'] > $rules['temp_max']) $s -= 30;

        if (isset($rules['humidity_min']) && $meteo['humidity'] < $rules['humidity_min']) $s -= 15;
        if (isset($rules['wind_max']) && $meteo['wind'] > $rules['wind_max']) $s -= 10;

        if (isset($rules['clouds_max']) && $meteo['clouds'] > $rules['clouds_max']) $s -= 10;

        // si aucune pénalité => la météo correspond bien
        if ($s === 0) $s += 25;

        return $s;
    }
}