<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
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

        $one = $weather->oneCall($champ->latitude, $champ->longitude);

        $timezone = data_get($one, 'timezone', 'UTC');

        $current = data_get($one, 'current', []);
        $hourly  = collect(data_get($one, 'hourly', []))->take(12)->values();
        $daily   = collect(data_get($one, 'daily', []))->take(1)->values(); // preview = aujourd'hui

        return response()->json([
            'champ' => ['id' => $champ->id, 'nom' => $champ->nom],

            'today' => [
                'temp' => data_get($current, 'temp'),
                'desc' => data_get($current, 'weather.0.description'),
                'humidity' => data_get($current, 'humidity'),
                'wind' => data_get($current, 'wind_speed'),
                'uv' => data_get($current, 'uvi'),

                // pop est 0..1 → si tu veux direct en %, décommente la ligne rain_percent
                'rain_prob' => data_get($hourly->first(), 'pop'),
                // 'rain_percent' => is_numeric(data_get($hourly->first(), 'pop')) ? (int) round(data_get($hourly->first(), 'pop') * 100) : null,
            ],

            // ✅ Format heure "07:40"
            'hourly' => $hourly->map(function ($h) use ($timezone) {
                $dt = data_get($h, 'dt'); // timestamp en secondes
                return [
                    'time' => $dt ? Carbon::createFromTimestamp($dt, $timezone)->format('H:i') : null,
                    'temp' => data_get($h, 'temp'),
                    'pop'  => data_get($h, 'pop'),
                ];
            })->values(),

            // ✅ Format date lisible pour "aujourd'hui" (ex: "2026-01-07" + "Tue 07")
            'daily_preview' => $daily->map(function ($d) use ($timezone) {
                $dt = data_get($d, 'dt');
                $day = $dt ? Carbon::createFromTimestamp($dt, $timezone) : null;

                return [
                    'date' => $day?->toDateString(),              // "2026-01-07"
                    'label' => $day?->translatedFormat('D d'),    // "mar. 07" (si locale fr)
                    'min'  => data_get($d, 'temp.min'),
                    'max'  => data_get($d, 'temp.max'),
                    'desc' => data_get($d, 'weather.0.description'),
                    'pop'  => data_get($d, 'pop'),
                ];
            })->values(),
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

        $timezone = data_get($one, 'timezone', 'UTC');

        $daily = collect(data_get($one, 'daily', []))
            ->take(max(1, min($days, 8)))
            ->values();

        return response()->json([
            'champ' => ['id' => $champ->id, 'nom' => $champ->nom],

            // ✅ Format date lisible pour chaque jour
            'days' => $daily->map(function ($d) use ($timezone) {
                $dt = data_get($d, 'dt');
                $day = $dt ? Carbon::createFromTimestamp($dt, $timezone) : null;

                return [
                    'date' => $day?->toDateString(),              // "2026-01-08"
                    'label' => $day?->translatedFormat('D d'),    // "mer. 08"
                    'min'  => data_get($d, 'temp.min'),
                    'max'  => data_get($d, 'temp.max'),
                    'humidity' => data_get($d, 'humidity'),
                    'wind' => data_get($d, 'wind_speed'),
                    'pop'  => data_get($d, 'pop'),
                    'desc' => data_get($d, 'weather.0.description'),
                ];
            })->values(),
        ]);
    }
}
