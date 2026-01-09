<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Champ;
use App\Models\Plantation;
use Illuminate\Http\Request;

class PlantationController extends Controller
{
    public function store(Request $request, Champ $champ)
    {
        abort_unless($champ->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'culture_id' => ['required','uuid'],
            'date_debut' => ['nullable','date'],
            'date_fin' => ['nullable','date','after_or_equal:date_debut'],
        ]);

        $plantation = Plantation::create([
            'champ_id' => $champ->id,
            'culture_id' => $data['culture_id'],
            'date_debut' => $data['date_debut'] ?? now()->toDateString(),
            'date_fin' => $data['date_fin'] ?? null,
        ]);

        return response()->json(['message' => 'Culture ajoutée au champ', 'plantation' => $plantation], 201);
    }

    public function update(Request $request, Plantation $plantation)
    {
        $plantation->load('champ');
        abort_unless($plantation->champ->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'date_debut' => ['sometimes','date'],
            'date_fin' => ['nullable','date','after_or_equal:date_debut'],
        ]);

        $plantation->fill($data)->save();

        return response()->json(['message' => 'Plantation mise à jour', 'plantation' => $plantation]);
    }

    public function destroy(Request $request, Plantation $plantation)
    {
        $plantation->load('champ');
        abort_unless($plantation->champ->user_id === $request->user()->id, 403);

        $plantation->delete();

        return response()->json(['message' => 'Plantation supprimée']);
    }
}