<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Culture;

class CultureController extends Controller
{
    public function index()
    {
        return response()->json([
            'cultures' => Culture::query()
                ->select('id', 'nom')
                ->orderBy('nom')
                ->get()
                ->map(fn ($c) => [
                    'id' => $c->id,
                    'nom' => is_object($c->nom) ? $c->nom->value : $c->nom,
                ]),
        ]);
    }
}