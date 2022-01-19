<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildingResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    public function get(Request $request) {
        $userId = Auth::user()->id;
        $cityId = $request->get('cityId');

        $city = City::where('id', $cityId)->where('user_id', $userId)->first();

        if ($city && $city->id) {
            return BuildingResource::collection($city->buildings);
        }

        return abort(403);
    }
}
