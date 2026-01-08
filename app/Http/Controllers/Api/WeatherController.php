<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Champ;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function today(Request $request, WeatherService $weather)
    {
        $champId = $request->query('champ_id');

        $champ = Champ::where('id', $champId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        abort_if(!$champ->latitude || !$champ->longitude, 422, "Le champ n'a pas de latitude/longitude.");

        // Option A: OneCall (recommandé)
        $one = $weather->oneCall($champ->latitude, $champ->longitude);

        $current = data_get($one, 'current', []);
        $hourly = collect(data_get($one, 'hourly', []))->take(12)->values(); // 12 prochaines heures
        $daily  = collect(data_get($one, 'daily', []))->take(1)->values();  // aujourd'hui

        return response()->json([
            'champ' => ['id' => $champ->id, 'nom' => $champ->nom],
            'today' => [
                'temp' => data_get($current, 'temp'),
                'desc' => data_get($current, 'weather.0.description'),
                'humidity' => data_get($current, 'humidity'),
                'wind' => data_get($current, 'wind_speed'),
                'uv' => data_get($current, 'uvi'), // présent dans OneCall
                'rain_prob' => data_get($hourly->first(), 'pop'), // 0..1
            ],
            'hourly' => $hourly->map(fn($h) => [
                'time' => data_get($h, 'dt'),
                'temp' => data_get($h, 'temp'),
                'pop'  => data_get($h, 'pop'),
            ]),
            'daily_preview' => $daily->map(fn($d) => [
                'date' => data_get($d, 'dt'),
                'min'  => data_get($d, 'temp.min'),
                'max'  => data_get($d, 'temp.max'),
                'desc' => data_get($d, 'weather.0.description'),
            ]),
        ]);
    }

    public function forecast(Request $request, WeatherService $weather)
    {
        $champId = $request->query('champ_id');
        $days = (int) ($request->query('days', 7));

        $champ = Champ::where('id', $champId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        abort_if(!$champ->latitude || !$champ->longitude, 422, "Le champ n'a pas de latitude/longitude.");

        $one = $weather->oneCall($champ->latitude, $champ->longitude);

        $daily = collect(data_get($one, 'daily', []))->take(max(1, min($days, 8)))->values();

        return response()->json([
            'champ' => ['id' => $champ->id, 'nom' => $champ->nom],
            'days' => $daily->map(fn($d) => [
                'date' => data_get($d, 'dt'),
                'min'  => data_get($d, 'temp.min'),
                'max'  => data_get($d, 'temp.max'),
                'humidity' => data_get($d, 'humidity'),
                'wind' => data_get($d, 'wind_speed'),
                'pop'  => data_get($d, 'pop'),
                'desc' => data_get($d, 'weather.0.description'),
            ]),
        ]);
    }
}