<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Culture;

class MetaDataController extends Controller
{
    public function getSettingsData()
    {
        return response()->json([
            'regions' => Region::select('id', 'name')->get(),
            'cultures' => Culture::select('id', 'nom')->get(),
        ]);
    }
}