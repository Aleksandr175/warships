<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResourcesResource;
use App\Models\BuildingProduction;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    public function getCityResources($cityId) {
        $user = Auth::user();

        $city = $user->cities()->where('id', $cityId)->first();
        $miner = $city->buildings()->where('id', 2)->first();
        $gold = 0;
        $production = 0;
        $now = Carbon::now();

        if ($miner && $miner->lvl) {
            $minerLvl = $miner->lvl;

            $buildingProduction = BuildingProduction::where('building_id', 2)->where('lvl', $minerLvl)->first();

            $resourceLastUpdated = Carbon::parse($city->resource_last_updated);

            $timeDiff = $now->diffInSeconds($resourceLastUpdated);

            $production = $timeDiff * $buildingProduction->qty / 3600;

            $gold = $city->gold;
        }

        $city->update([
            'gold' => $gold + $production,
            'resource_last_updated' => $now
        ]);

        return new CityResourcesResource($city);
    }
}
