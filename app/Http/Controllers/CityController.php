<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResourcesResource;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    public function getCityResources($cityId) {
        $user = Auth::user();

        $city = $user->cities()->where('id', $cityId)->first();

        return new CityResourcesResource($city);
    }
}
