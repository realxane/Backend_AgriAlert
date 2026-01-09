<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActiviteAgricole;
use App\Models\Plantation;
use Illuminate\Http\Request;

class ActiviteAgricoleController extends Controller
{
    public function index(Request $request, Plantation $plantation)
    {
        $plantation->load('champ');
        abort_unless($plantation->champ->user_id === $request->user()->id, 403);

        $list = $plantation->activitesAgricoles()->latest('done_at')->get();

        return response()->json(['activites' => $list]);
    }

    public function store(Request $request, Plantation $plantation)
    {
        $plantation->load('champ');
        abort_unless($plantation->champ->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'type' => ['required','in:Semis,Recolte,Traitement'],
            'done_at' => ['nullable','date'],
        ]);

        $act = ActiviteAgricole::create([
            'user_id' => $request->user()->id,
            'plantation_id' => $plantation->id,
            'type' => $data['type'],
            'done_at' => $data['done_at'] ?? now(),
        ]);

        return response()->json(['message' => 'Activité enregistrée', 'activite' => $act], 201);
    }
}