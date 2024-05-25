<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResourceV2Resource;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    public function getCityResources($cityId)
    {
        $user = Auth::user();

        $cityResources = $user->cities()->where('id', $cityId)->first()->resources;

        return [
            'cityId'        => (int)$cityId,
            'cityResources' => CityResourceV2Resource::collection($cityResources)
        ];
    }
}
