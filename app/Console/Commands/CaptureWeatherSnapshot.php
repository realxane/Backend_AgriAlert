<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Champ;
use App\Models\HistoriqueMeteo;
use App\Enums\SourceMeteo;
use App\Services\WeatherService;

class CaptureWeatherSnapshot extends Command
{
    protected $signature = 'weather:capture';
    protected $description = 'Capture un snapshot météo pour chaque champ';

    public function handle(WeatherService $weather): int
    {
        Champ::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->chunk(200, function ($champs) use ($weather) {
                foreach ($champs as $champ) {
                    $one = $weather->oneCall($champ->latitude, $champ->longitude);
                    $current = data_get($one, 'current', []);

                    HistoriqueMeteo::create([
                        'region_id' => $champ->region_id,
                        'champ_id' => $champ->id,
                        'date' => now(),
                        'temperature' => data_get($current, 'temp'),
                        'precipitation' => (float) (data_get($current, 'rain.1h') ?? 0),
                        'humidite' => data_get($current, 'humidity'),
                        'source' => SourceMeteo::OpenWeather,
                    ]);
                }
            });

        $this->info('Snapshots météo enregistrés.');
        return self::SUCCESS;
    }
}