<?php

namespace App\Services;

use App\Models\Plantation;
use Carbon\Carbon;

class PlantationContextService
{
    /**
     * Retourne le contexte utile pour les recommandations :
     * - jours_depuis_debut : nombre de jours depuis date_debut
     * - progress_percent   : progression en % du cycle de la culture
     * - last_activity_type : dernier type d'activité (Semis/Recolte/Traitement)
     */
    public function build(?Plantation $plantation): array
    {
        // Si aucune plantation active trouvée, on renvoie un contexte vide
        if (!$plantation) {
            return [
                'jours_depuis_debut' => null,
                'progress_percent' => null,
                'last_activity_type' => null,
            ];
        }

        $today = now();

        // 1) Calcul du nombre de jours depuis le début de la plantation
        $jours = Carbon::parse($plantation->date_debut)->diffInDays($today);

        // 2) Récupération du cycle de la culture (ex: 90 jours pour tomate)
        $cycle = $plantation->culture?->cycle_date;

        // 3) Progression en % (0..100)
        // Exemple : jours=20, cycle=100 => 20%
        $progress = ($cycle && $cycle > 0)
            ? min(100, (int) round($jours * 100 / $cycle))
            : null;

        // 4) Dernière activité agricole enregistrée (Semis/Recolte/Traitement)
        $lastActivity = $plantation->activitesAgricoles()
            ->latest('done_at')
            ->value('type'); // retourne 'Semis'/'Recolte'/'Traitement' (cast enum possible)

        return [
            'jours_depuis_debut' => $jours,
            'progress_percent' => $progress,
            'last_activity_type' => $lastActivity?->value ?? $lastActivity,
        ];
    }
}