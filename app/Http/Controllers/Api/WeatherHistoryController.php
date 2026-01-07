<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Champ;
use App\Models\HistoriqueMeteo;
use Illuminate\Http\Request;

class WeatherHistoryController extends Controller
{
    public function index(Request $request)
    {
        $champId = $request->query('champ_id');
        $days = (int) ($request->query('days', 7));

        $champ = Champ::where('id', $champId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $from = now()->subDays(max(1, min($days, 60)));

        $rows = HistoriqueMeteo::query()
            ->where('champ_id', $champ->id)
            ->where('date', '>=', $from)
            ->orderByDesc('date')
            ->get();

        return response()->json([
            'champ' => ['id' => $champ->id, 'nom' => $champ->nom],
            'history' => $rows,
        ]);
    }
}