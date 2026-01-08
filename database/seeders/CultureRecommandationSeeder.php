<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Culture;
use App\Models\Recommandation;
use App\Enums\NomCulture;
use App\Enums\ActiviteType;

class CultureRecommandationSeeder extends Seeder
{
    public function run(): void
    {
        $cultures = [
            // ---------------- TOMATE ----------------
            NomCulture::Tomate->value => [
                'enum' => NomCulture::Tomate,
                'cycle_date' => 90,
                'temp_ideale_min' => 20,
                'temp_ideale_max' => 30,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Arrosage régulier (2–3x/semaine)',
                'description' => "Culture maraîchère cultivée pour ses fruits comestibles.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Besoin d'engrais cette semaine",
                        'contenu' => "Si les plants sont en croissance active, appliquez un engrais NPK (15-15-15) en dose modérée.",
                        'activite_type' => null,
                        'min_jour' => 14, 'max_jour' => 45,
                        'weather_rules' => ['temp_min' => 18, 'temp_max' => 35],
                        'priorite' => 20,
                    ],
                    [
                        'titre' => "Arrosage",
                        'contenu' => "Arrosez tôt le matin. Évitez l’excès d’eau pour limiter les maladies.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => 90,
                        'weather_rules' => ['temp_min' => 22],
                        'priorite' => 40,
                    ],
                    [
                        'titre' => "Traitement préventif",
                        'contenu' => "Surveillez les feuilles. En cas d’humidité élevée, pensez à un traitement préventif antifongique adapté.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 10, 'max_jour' => 80,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 15,
                    ],
                    [
                        'titre' => "Désherbage et aération",
                        'contenu' => "Désherbez autour des plants et aérez pour réduire l’humidité stagnante.",
                        'activite_type' => null,
                        'min_jour' => 7, 'max_jour' => 60,
                        'weather_rules' => null,
                        'priorite' => 60,
                    ],
                    [
                        'titre' => "Préparation récolte",
                        'contenu' => "À l’approche de la récolte, réduisez légèrement l’arrosage pour améliorer la qualité des fruits.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 60, 'max_jour' => 90,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- RIZ ----------------
            NomCulture::Riz->value => [
                'enum' => NomCulture::Riz,
                'cycle_date' => 120,
                'temp_ideale_min' => 20,
                'temp_ideale_max' => 35,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Besoin élevé en eau (selon système irrigué/pluvial)',
                'description' => "Céréale cultivée en zones irriguées ou pluviales selon la région.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Préparer la parcelle",
                        'contenu' => "Assurez un bon nivellement et des canaux d’irrigation fonctionnels avant le semis.",
                        'activite_type' => ActiviteType::Semis->value,
                        'min_jour' => 0, 'max_jour' => 7,
                        'weather_rules' => null,
                        'priorite' => 20,
                    ],
                    [
                        'titre' => "Gestion de l’eau",
                        'contenu' => "Maintenez une lame d’eau régulière si riz irrigué. Évitez les stress hydriques.",
                        'activite_type' => null,
                        'min_jour' => 7, 'max_jour' => 90,
                        'weather_rules' => ['temp_max' => 38],
                        'priorite' => 30,
                    ],
                    [
                        'titre' => "Fertilisation de couverture",
                        'contenu' => "Appliquez une fertilisation azotée fractionnée selon le stade (tallage / montaison).",
                        'activite_type' => null,
                        'min_jour' => 20, 'max_jour' => 70,
                        'weather_rules' => null,
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Surveillance ravageurs",
                        'contenu' => "Surveillez les insectes et maladies. Intervenez tôt si attaque détectée.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 15, 'max_jour' => 100,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 15,
                    ],
                    [
                        'titre' => "Préparer la récolte",
                        'contenu' => "Planifiez la récolte quand les grains sont mûrs et réduisez l’eau avant coupe.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 100, 'max_jour' => 130,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- MAIS ----------------
            NomCulture::Mais->value => [
                'enum' => NomCulture::Mais,
                'cycle_date' => 100,
                'temp_ideale_min' => 18,
                'temp_ideale_max' => 35,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Arrosage modéré, critique à floraison',
                'description' => "Céréale sensible au stress hydrique surtout à floraison et remplissage.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Semis et densité",
                        'contenu' => "Respectez une densité adaptée et semez sur sol suffisamment humide.",
                        'activite_type' => ActiviteType::Semis->value,
                        'min_jour' => 0, 'max_jour' => 7,
                        'weather_rules' => ['temp_min' => 16],
                        'priorite' => 20,
                    ],
                    [
                        'titre' => "Désherbage précoce",
                        'contenu' => "Désherbez tôt (2–4 semaines) pour éviter la concurrence.",
                        'activite_type' => null,
                        'min_jour' => 10, 'max_jour' => 35,
                        'weather_rules' => null,
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Arrosage à floraison",
                        'contenu' => "Assurez un bon apport en eau au stade floraison et remplissage des grains.",
                        'activite_type' => null,
                        'min_jour' => 40, 'max_jour' => 70,
                        'weather_rules' => ['temp_max' => 38],
                        'priorite' => 15,
                    ],
                    [
                        'titre' => "Traitement ciblé",
                        'contenu' => "En cas d’attaque de chenilles/ravageurs, utilisez un traitement adapté.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 15, 'max_jour' => 80,
                        'weather_rules' => ['wind_max' => 10],
                        'priorite' => 18,
                    ],
                    [
                        'titre' => "Récolte et séchage",
                        'contenu' => "Récoltez à maturité et séchez correctement pour éviter les moisissures.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 85, 'max_jour' => 120,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- POMME DE TERRE ----------------
            NomCulture::PommeDeTerre->value => [
                'enum' => NomCulture::PommeDeTerre,
                'cycle_date' => 90,
                'temp_ideale_min' => 15,
                'temp_ideale_max' => 25,
                'ensoleillement' => 'Soleil modéré',
                'eau' => 'Arrosage régulier sans excès',
                'description' => "Tubercule : sensible aux excès d’eau et aux maladies foliaires.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Buttage",
                        'contenu' => "Réalisez le buttage pour protéger les tubercules et limiter le verdissement.",
                        'activite_type' => null,
                        'min_jour' => 15, 'max_jour' => 35,
                        'weather_rules' => null,
                        'priorite' => 20,
                    ],
                    [
                        'titre' => "Irrigation",
                        'contenu' => "Arrosez régulièrement, surtout à la tubérisation, sans saturer le sol.",
                        'activite_type' => null,
                        'min_jour' => 10, 'max_jour' => 70,
                        'weather_rules' => ['temp_max' => 30],
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Prévention mildiou",
                        'contenu' => "Si humidité élevée, surveillez le mildiou et intervenez rapidement si symptômes.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 20, 'max_jour' => 80,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 12,
                    ],
                    [
                        'titre' => "Désherbage",
                        'contenu' => "Désherbez pour limiter la concurrence et faciliter l’aération des plants.",
                        'activite_type' => null,
                        'min_jour' => 10, 'max_jour' => 45,
                        'weather_rules' => null,
                        'priorite' => 35,
                    ],
                    [
                        'titre' => "Préparer la récolte",
                        'contenu' => "Réduisez l’arrosage avant récolte et récoltez quand les fanes jaunissent.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 70, 'max_jour' => 110,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- CITRON ----------------
            NomCulture::Citron->value => [
                'enum' => NomCulture::Citron,
                'cycle_date' => 365,
                'temp_ideale_min' => 18,
                'temp_ideale_max' => 32,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Arrosage régulier (arbres)',
                'description' => "Agrume : nécessite taille, arrosage régulier et surveillance des parasites.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Arrosage des jeunes plants",
                        'contenu' => "Arrosez régulièrement sans excès, surtout en saison sèche.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['temp_max' => 38],
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Fertilisation",
                        'contenu' => "Apportez du compost ou engrais organique au pied, en fractionnant si besoin.",
                        'activite_type' => null,
                        'min_jour' => 30, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 30,
                    ],
                    [
                        'titre' => "Traitement contre parasites",
                        'contenu' => "Surveillez pucerons/aleurodes. Traitez si infestation significative.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['wind_max' => 10],
                        'priorite' => 15,
                    ],
                    [
                        'titre' => "Taille légère",
                        'contenu' => "Taillez les branches mortes et aérez la canopée pour limiter les maladies.",
                        'activite_type' => null,
                        'min_jour' => 90, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 40,
                    ],
                    [
                        'titre' => "Récolte progressive",
                        'contenu' => "Récoltez les fruits arrivés à maturité sans blesser les branches.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- ORANGE ----------------
            NomCulture::Orange->value => [
                'enum' => NomCulture::Orange,
                'cycle_date' => 365,
                'temp_ideale_min' => 18,
                'temp_ideale_max' => 32,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Arrosage régulier (arbres)',
                'description' => "Agrume : gestion de l’eau, fertilisation, taille et protection sanitaire.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Arrosage en saison sèche",
                        'contenu' => "Arrosez régulièrement et paillez pour limiter l’évaporation.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['temp_max' => 40],
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Fertilisation annuelle",
                        'contenu' => "Ajoutez compost/engrais au début de la saison favorable, en respectant les doses.",
                        'activite_type' => null,
                        'min_jour' => 60, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 30,
                    ],
                    [
                        'titre' => "Surveillance maladies",
                        'contenu' => "Surveillez tâches sur feuilles/fruits. Traitez si nécessaire.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 15,
                    ],
                    [
                        'titre' => "Taille d’entretien",
                        'contenu' => "Éliminez branches encombrées/mortes pour aérer l’arbre.",
                        'activite_type' => null,
                        'min_jour' => 120, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 45,
                    ],
                    [
                        'titre' => "Récolte",
                        'contenu' => "Récoltez quand les fruits sont bien colorés et sucrés.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- MANGUE ----------------
            NomCulture::Mangue->value => [
                'enum' => NomCulture::Mangue,
                'cycle_date' => 365,
                'temp_ideale_min' => 20,
                'temp_ideale_max' => 38,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Arrosage modéré, attention à l’excès',
                'description' => "Arbre fruitier : nécessite taille, protection floraison/fructification.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Taille et aération",
                        'contenu' => "Taillez après récolte pour aérer et limiter maladies.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 35,
                    ],
                    [
                        'titre' => "Surveillance anthracnose",
                        'contenu' => "Humidité élevée = risque d’anthracnose. Surveillez et traitez si nécessaire.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 12,
                    ],
                    [
                        'titre' => "Arrosage raisonné",
                        'contenu' => "Arrosez en période sèche, évitez l’excès pour limiter les maladies racinaires.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['temp_max' => 42],
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Protection fruits",
                        'contenu' => "Protégez les fruits des piqûres d’insectes (pièges, hygiène).",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 20,
                    ],
                    [
                        'titre' => "Récolte",
                        'contenu' => "Récoltez à maturité physiologique et manipulez délicatement.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- POIVRE ----------------
            NomCulture::Poivre->value => [
                'enum' => NomCulture::Poivre,
                'cycle_date' => 120,
                'temp_ideale_min' => 20,
                'temp_ideale_max' => 35,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Arrosage régulier',
                'description' => "Culture maraîchère sensible au stress hydrique et aux maladies.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Arrosage régulier",
                        'contenu' => "Arrosez tôt le matin et paillez pour garder l’humidité.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => 120,
                        'weather_rules' => ['temp_max' => 38],
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Fertilisation",
                        'contenu' => "Appliquez un engrais équilibré après reprise de croissance.",
                        'activite_type' => null,
                        'min_jour' => 14, 'max_jour' => 70,
                        'weather_rules' => null,
                        'priorite' => 30,
                    ],
                    [
                        'titre' => "Traitement préventif",
                        'contenu' => "Humidité élevée = risque de maladies foliaires. Surveillez et traitez si besoin.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 10, 'max_jour' => 100,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 12,
                    ],
                    [
                        'titre' => "Désherbage",
                        'contenu' => "Désherbez régulièrement pour limiter la concurrence et les abris de ravageurs.",
                        'activite_type' => null,
                        'min_jour' => 10, 'max_jour' => 60,
                        'weather_rules' => null,
                        'priorite' => 40,
                    ],
                    [
                        'titre' => "Préparer la récolte",
                        'contenu' => "Récoltez progressivement quand les fruits atteignent la couleur/maturité voulue.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 80, 'max_jour' => 140,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- FRAISE ----------------
            NomCulture::Fraise->value => [
                'enum' => NomCulture::Fraise,
                'cycle_date' => 120,
                'temp_ideale_min' => 15,
                'temp_ideale_max' => 28,
                'ensoleillement' => 'Soleil modéré',
                'eau' => 'Arrosage fréquent mais léger',
                'description' => "Culture sensible aux excès d’humidité et aux maladies fongiques.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Paillage",
                        'contenu' => "Paillez pour limiter le contact des fruits avec le sol et garder l’humidité.",
                        'activite_type' => null,
                        'min_jour' => 7, 'max_jour' => 45,
                        'weather_rules' => null,
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Arrosage",
                        'contenu' => "Arrosez au pied. Évitez de mouiller les feuilles pour limiter les champignons.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => 120,
                        'weather_rules' => ['humidity_max' => 90], // si tu ajoutes la gestion humidity_max dans ton score
                        'priorite' => 30,
                    ],
                    [
                        'titre' => "Traitement préventif",
                        'contenu' => "Si humidité élevée, surveillez botrytis/oidium et intervenez si symptômes.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 15, 'max_jour' => 120,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 12,
                    ],
                    [
                        'titre' => "Désherbage",
                        'contenu' => "Désherbez autour des plants pour réduire la concurrence et améliorer l’aération.",
                        'activite_type' => null,
                        'min_jour' => 10, 'max_jour' => 60,
                        'weather_rules' => null,
                        'priorite' => 40,
                    ],
                    [
                        'titre' => "Récolte régulière",
                        'contenu' => "Récoltez souvent les fruits mûrs pour stimuler la production.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 60, 'max_jour' => 150,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- RAISIN ----------------
            NomCulture::Raisin->value => [
                'enum' => NomCulture::Raisin,
                'cycle_date' => 365,
                'temp_ideale_min' => 18,
                'temp_ideale_max' => 35,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Arrosage modéré',
                'description' => "Vigne : nécessite taille, palissage et surveillance des maladies.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Taille et palissage",
                        'contenu' => "Taillez et palissez pour bien exposer les grappes et limiter l’humidité.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 30,
                    ],
                    [
                        'titre' => "Traitement mildiou/oidium",
                        'contenu' => "En période humide, surveillez mildiou/oidium et traitez si nécessaire.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 12,
                    ],
                    [
                        'titre' => "Arrosage raisonné",
                        'contenu' => "Arrosez en saison sèche, évitez l’excès.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['temp_max' => 42],
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Éclaircissage",
                        'contenu' => "Éclaircissez si surcharge pour améliorer la qualité des grappes.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 40,
                    ],
                    [
                        'titre' => "Récolte",
                        'contenu' => "Récoltez quand les grappes sont sucrées et bien colorées.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- PAPAYE ----------------
            NomCulture::Papaye->value => [
                'enum' => NomCulture::Papaye,
                'cycle_date' => 300,
                'temp_ideale_min' => 20,
                'temp_ideale_max' => 35,
                'ensoleillement' => 'Plein soleil',
                'eau' => 'Arrosage régulier',
                'description' => "Plante tropicale : sensible aux vents forts et à l’excès d’eau.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Arrosage",
                        'contenu' => "Arrosez régulièrement, évitez l’engorgement.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['temp_max' => 40],
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Fertilisation",
                        'contenu' => "Apportez compost/engrais organique pour soutenir la fructification.",
                        'activite_type' => null,
                        'min_jour' => 30, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 30,
                    ],
                    [
                        'titre' => "Protection vent",
                        'contenu' => "Si vent fort fréquent, installez une protection (haie/brise-vent).",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['wind_max' => 12],
                        'priorite' => 20,
                    ],
                    [
                        'titre' => "Traitement si symptômes",
                        'contenu' => "Surveillez les feuilles. Traitez uniquement si symptômes ou attaque.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 18,
                    ],
                    [
                        'titre' => "Récolte progressive",
                        'contenu' => "Récoltez les fruits au fur et à mesure de la maturation.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 200, 'max_jour' => 400,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],

            // ---------------- POMME ----------------
            NomCulture::Pomme->value => [
                'enum' => NomCulture::Pomme,
                'cycle_date' => 365,
                'temp_ideale_min' => 10,
                'temp_ideale_max' => 25,
                'ensoleillement' => 'Soleil modéré',
                'eau' => 'Arrosage régulier',
                'description' => "Arbre fruitier : entretien, taille et protection phytosanitaire.",
                'image_url' => null,
                'recs' => [
                    [
                        'titre' => "Taille d’entretien",
                        'contenu' => "Taillez pour équilibrer l’arbre et améliorer l’ensoleillement des fruits.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 35,
                    ],
                    [
                        'titre' => "Fertilisation",
                        'contenu' => "Apportez compost/engrais organique au début de la période favorable.",
                        'activite_type' => null,
                        'min_jour' => 60, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 30,
                    ],
                    [
                        'titre' => "Traitement préventif",
                        'contenu' => "Humidité élevée favorise certaines maladies. Surveillez et traitez si besoin.",
                        'activite_type' => ActiviteType::Traitement->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['humidity_min' => 70],
                        'priorite' => 15,
                    ],
                    [
                        'titre' => "Arrosage",
                        'contenu' => "Arrosez régulièrement en saison sèche, sans excès.",
                        'activite_type' => null,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => ['temp_max' => 35],
                        'priorite' => 25,
                    ],
                    [
                        'titre' => "Récolte",
                        'contenu' => "Récoltez à maturité selon la variété et stockez correctement.",
                        'activite_type' => ActiviteType::Recolte->value,
                        'min_jour' => 0, 'max_jour' => null,
                        'weather_rules' => null,
                        'priorite' => 10,
                    ],
                ],
            ],
        ];

        foreach ($cultures as $nomCulture => $payload) {

            $culture = Culture::firstOrCreate(
                ['nom' => $nomCulture],
                [
                    'cycle_date' => $payload['cycle_date'],
                    'temp_ideale_min' => $payload['temp_ideale_min'],
                    'temp_ideale_max' => $payload['temp_ideale_max'],
                    'ensoleillement' => $payload['ensoleillement'],
                    'eau' => $payload['eau'],
                    'description' => $payload['description'],
                    'image_url' => $payload['image_url'],
                ]
            );

            foreach ($payload['recs'] as $it) {
                Recommandation::updateOrCreate(
                    [
                        'culture_id' => $culture->id,
                        'titre' => $it['titre'],
                    ],
                    array_merge($it, [
                        'culture_id' => $culture->id,
                        'region_id' => null,
                        'saison' => null,
                        'is_active' => true,
                    ])
                );
            }
        }
    }
}