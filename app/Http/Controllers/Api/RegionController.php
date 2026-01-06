<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Regions\StoreRegionRequest;
use App\Models\Region;

class RegionController extends Controller
{
    public function store(StoreRegionRequest $request)
    {
        $region = Region::create($request->validated());

        return response()->json([
            'message' => 'Région créée avec succès.',
            'region' => $region,
        ], 201);
    }
}