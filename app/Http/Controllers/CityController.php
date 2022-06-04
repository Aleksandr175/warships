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

        return new CityResourcesResource($city);
    }
}
