<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Champ;
use App\Models\Notification;
use App\Notifications\NotificationServiceAlert;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AlertWeatherCommand extends Command
{
    protected $signature = 'weather:check';
    protected $description = 'Analyse la météo par champ et envoie des alertes push intelligentes';

    public function handle(NotificationServiceAlert $notificationService)
    {
        // 1. Récupérer les champs des utilisateurs ayant un token FCM
        $champs = Champ::with('user')
            ->whereHas('user', function($query) {
                $query->whereNotNull('fcm_token');
            })->get();

        if ($champs->isEmpty()) {
            $this->info("Aucun champ avec utilisateur notifiable trouvé.");
            return;
        }

        foreach ($champs as $champ) {
            $this->info("Analyse météo pour : {$champ->nom}");

            try {
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
                    $weatherMain = $data['weather'][0]['main'] ?? '';
                    $rain = $data['rain']['1h'] ?? 0;

                    $alertTitle = "";
                    $alertBody = "";
                    $icon = "assets/images/notification_default.png";

                    // --- LOGIQUE DES SEUILS ---
                    if ($temp < 40) { 
                        $alertTitle = "🌡️ Température extrême";
                        $alertBody = "Bonjour {$champ->user->nom}, Il fait {$temp}°C sur votre champ '{$champ->nom}'. Pensez à irriguer.";
                        $icon = "assets/images/chaleur.png";
                    } elseif ($rain > 10 || $weatherMain == 'Thunderstorm') {
                        $alertTitle = "🌊 Risque d'Inondation";
                        $alertBody = "Bonjour {$champ->user->nom}, Grosses pluies détectées sur '{$champ->nom}'. Surveillez les drains.";
                        $icon = "assets/images/nuage.png";
                    } elseif ($humidity > 85 && $temp > 22) {
                        $alertTitle = "🐛 Risque de Maladie";
                        $alertBody = "Bonjour {$champ->user->nom}, Conditions favorables au mildiou sur '{$champ->nom}'.";
                        $icon = "assets/images/insecte.png";
                    }

                    // --- ENVOI SI ALERTE GÉNÉRÉE ---
                    if ($alertTitle != "") {
                        
                        // SÉCURITÉ : Ne pas renvoyer la même alerte si une existe déjà depuis moins de 12h
                        // $dejaEnvoye = Notification::where('user_id', $champ->user_id)
                        //     ->where('title', $alertTitle)
                        //     ->where('created_at', '>=', now()->subHours(12))
                        //     ->exists();
                        $dejaEnvoye= false; // Désactivé pour tests fréquents

                        if (!$dejaEnvoye) {
                            // 1. Sauvegarde en DB (pour l'historique dans l'app)
                            Notification::create([
                                'user_id' => $champ->user_id,
                                'title'   => $alertTitle,
                                'message' => $alertBody,
                                'icon'    => $icon,
                                'date'    => now(),
                            ]);

                            // 2. Envoi du Push réel
                            $notificationService->sendPush($champ->user->fcm_token, $alertTitle, $alertBody);
                            
                            $this->info(" Alerte envoyée à {$champ->user->nom} pour {$champ->nom}");
                        } else {
                            $this->info(" Alerte ignorée (déjà envoyée il y a moins de 12h)");
                        }
                    }

                }
            } catch (\Exception $e) {
                $this->error("Erreur pour {$champ->nom} : " . $e->getMessage());
                Log::error("Erreur WeatherCheck : " . $e->getMessage());
            }
        }
        
        $this->info("Vérification terminée.");
    }
}