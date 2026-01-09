<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Champ;
use Illuminate\Http\Request;

class ChampController extends Controller
{
    public function index(Request $request)
    {
        $champs = Champ::where('user_id', $request->user()->id)->get();
        return response()->json(['champs' => $champs]);
    }

    public function show(Request $request, Champ $champ)
    {
        abort_unless($champ->user_id === $request->user()->id, 403);

        $champ->load(['region', 'plantations.culture']);
        return response()->json(['champ' => $champ]);
    }

    public function update(Request $request, Champ $champ)
    {
        abort_unless($champ->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'nom' => ['sometimes','string','max:255'],
            'region_id' => ['sometimes','uuid'],
            'latitude' => ['sometimes','numeric'],
            'longitude' => ['sometimes','numeric'],
            'aire' => ['sometimes','numeric','min:0'],
        ]);

        $champ->fill($data)->save();

        return response()->json(['message' => 'Champ mis à jour', 'champ' => $champ]);
    }
}