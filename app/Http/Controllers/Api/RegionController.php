<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;

class RegionController extends Controller
{
    public function index()
    {
        return response()->json([
            'regions' => Region::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }
}