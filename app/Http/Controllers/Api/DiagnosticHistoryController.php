<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticIa;
use Illuminate\Http\Request;

class DiagnosticHistoryController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 10);

        $items = DiagnosticIa::where('user_id', $request->user()->id)
            ->latest()
            ->take(max(1, min($limit, 50)))
            ->get();

        return response()->json([
            'diagnostics' => $items,
        ]);
    }
}
