<?php

namespace App\Http\Controllers;

use App\Http\Resources\MapCityResource;
use App\Models\City;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function get(Request $request) {
        $cities = City::get();

        return [
            'cities' => MapCityResource::collection($cities)
        ];
    }
}
