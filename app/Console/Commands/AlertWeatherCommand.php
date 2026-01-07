<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Champ;
use App\Notifications\NotificationServiceAlert;
use Illuminate\Support\Facades\Log;

class AlertWeatherCommand extends Command
{
    protected $signature = 'weather:check';
    protected $description = 'Analyse la météo par champ (GPS) et envoie des alertes push';

    public function handle(NotificationServiceAlert $notificationService)
    {
        // On récupère tous les champs qui appartiennent à un utilisateur ayant un token FCM
        $champs = Champ::with('user')
            ->whereHas('user', function($query) {
                $query->whereNotNull('fcm_token');
            })->get();

        if ($champs->isEmpty()) {
            $this->info("Aucun champ avec utilisateur notifiable trouvé.");
            return;
        }

        foreach ($champs as $champ) {
            $this->info("Vérification pour le champ : {$champ->nom} (Propriétaire: {$champ->user->nom})");

            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'lat'   => $champ->latitude,
                'lon'   => $champ->longitude,
                'appid' => env('OPENWEATHER_API_KEY'),
                'units' => 'metric',
                'lang'  => 'fr'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $temp = $data['main']['temp'];
                $humidity = $data['main']['humidity'];
                $weatherMain = $data['weather'][0]['main'];
                $rain = isset($data['rain']['1h']) ? $data['rain']['1h'] : 0;

                $alertTitle = "";
                $alertBody = "";

                // --- LOGIQUE DES SEUILS CRITIQUES ---
                if ($temp > 43) {
                    $alertTitle = "🌡️ Température extrême";
                    $alertBody = "Chaleur de $temp°C sur votre champ '{$champ->nom}'. Arrosez tôt le matin.";
                } elseif ($rain > 15 || $weatherMain == 'Thunderstorm') {
                    $alertTitle = "🌊 Risque d'Inondation";
                    $alertBody = "Fortes pluies détectées sur '{$champ->nom}'. Vérifiez vos drainages.";
                } elseif ($temp > 35 && $humidity < 25) {
                    $alertTitle = "☀️ Risque de Sécheresse";
                    $alertBody = "Air très sec sur '{$champ->nom}'. Un arrosage d'appoint est nécessaire.";
                } elseif ($humidity > 85 && $temp > 22 && $temp < 28) {
                    $alertTitle = "🐛 Risque de Maladie";
                    $alertBody = "Humidité élevée sur '{$champ->nom}'. Risque de mildiou. Surveillez les feuilles.";
                }

                // Envoi de la notification si une alerte est déclenchée
                if ($alertTitle != "" && $champ->user->fcm_token) {
                    try {
                        $notificationService->sendPush($champ->user->fcm_token, $alertTitle, $alertBody);
                        $this->info("✅ Alerte envoyée pour {$champ->nom}");
                    } catch (\Exception $e) {
                        $this->error("❌ Échec envoi notification : " . $e->getMessage());
                    }
                }
            } else {
                $this->error("Impossible de contacter l'API météo pour le champ {$champ->nom}");
            }
        }
    }
}