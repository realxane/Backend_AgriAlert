<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function current(float $lat, float $lon): array
    {
        $key = "weather:current:$lat:$lon";

        return Cache::remember($key, now()->addMinutes(30), function () use ($lat, $lon) {
            $apiKey = config('services.openweather.key');

            $resp = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'fr',
            ])->throw()->json();

            return [
                'temp' => data_get($resp, 'main.temp'),
                'humidity' => data_get($resp, 'main.humidity'),
                'wind' => data_get($resp, 'wind.speed'),
                'clouds' => data_get($resp, 'clouds.all'),
                'desc' => data_get($resp, 'weather.0.description'),
            ];
        });
    }
}