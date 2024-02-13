<?php

namespace App\Http\Controllers;

use App\Http\Resources\MapCityResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    public function get(Request $request) {
        $user = Auth::user();

        $mainCity = City::where('user_id', $user->id)->first();
        $archipelagoId = $mainCity->archipelago_id;

        // get all islands in archipelago (incl. pirates and etc.)
        $cities = City::where('archipelago_id', $archipelagoId)->with('resourcesProductionCoefficient')->get();

        return [
            'cities' => MapCityResource::collection($cities)
        ];
    }
}
