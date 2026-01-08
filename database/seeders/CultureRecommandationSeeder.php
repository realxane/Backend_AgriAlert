<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Culture;
use App\Models\Recommandation;
use App\Enums\NomCulture;

class CultureRecommandationSeeder extends Seeder
{
    public function run(): void
    {
        $tomate = Culture::firstOrCreate(
            ['nom' => NomCulture::Tomate],
            [
                'cycle_date' => 90,
                'temp_ideale_min' => 20,
                'temp_ideale_max' => 30,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Arrosage régulier (2–3x/semaine)',
                'description' => 'La tomate est une culture maraîchère cultivée pour ses fruits comestibles.',
                'image_url' => null,
            ]
        );

        // 5 recommandations de base (exemples)
        $items = [
            [
                'titre' => "Besoin d'engrais cette semaine",
                'contenu' => "Si les plants sont en croissance active, appliquez un engrais NPK (15-15-15) en dose modérée.",
                'activite_type' => null,
                'min_jour' => 14,
                'max_jour' => 45,
                'weather_rules' => ['temp_min' => 18, 'temp_max' => 35],
                'priorite' => 20,
            ],
            [
                'titre' => "Arrosage",
                'contenu' => "Arrosez tôt le matin. Évitez l’excès d’eau pour limiter les maladies.",
                'activite_type' => null,
                'min_jour' => 0,
                'max_jour' => 90,
                'weather_rules' => ['temp_min' => 22],
                'priorite' => 40,
            ],
            [
                'titre' => "Traitement préventif",
                'contenu' => "Surveillez les feuilles. En cas d’humidité élevée, pensez à un traitement préventif antifongique adapté.",
                'activite_type' => 'Traitement',
                'min_jour' => 10,
                'max_jour' => 80,
                'weather_rules' => ['humidity_min' => 70],
                'priorite' => 15,
            ],
            [
                'titre' => "Désherbage et aération",
                'contenu' => "Désherbez autour des plants et aérez pour réduire l’humidité stagnante.",
                'activite_type' => null,
                'min_jour' => 7,
                'max_jour' => 60,
                'weather_rules' => null,
                'priorite' => 60,
            ],
            [
                'titre' => "Préparation récolte",
                'contenu' => "À l’approche de la récolte, réduisez légèrement l’arrosage pour améliorer la qualité des fruits.",
                'activite_type' => 'Recolte',
                'min_jour' => 60,
                'max_jour' => 90,
                'weather_rules' => null,
                'priorite' => 10,
            ],
        ];

        foreach ($items as $it) {
            Recommandation::updateOrCreate(
                [
                    'culture_id' => $tomate->id,
                    'titre' => $it['titre'],
                ],
                array_merge($it, [
                    'culture_id' => $tomate->id,
                    'contenu' => $it['contenu'],
                    'region_id' => null,
                    'saison' => null,
                    'is_active' => true,
                ])
            );
        }
    }
}