<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Champ;
use App\Models\Culture;
use App\Services\RecommandationEngine;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function byCulture(Request $request, Culture $culture, RecommandationEngine $engine)
    {
        $champId = $request->query('champ_id');

        $champ = $champId
            ? Champ::where('id', $champId)
                ->where('user_id', $request->user()->id)
                ->firstOrFail()
            : null;

        return response()->json(
            $engine->forCulture($culture, $champ)
        );
    }
}