<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildingResource;
use App\Http\Resources\CityBuildingQueueResource;
use App\Http\Resources\BuildingProductionsResource;
use App\Models\BuildingProduction;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    public function get(Request $request) {
        $userId = Auth::user()->id;
        $cityId = $request->get('cityId');

        $city = City::where('id', $cityId)->where('user_id', $userId)->first();

        if ($city && $city->id) {
            return [
                'buildings' => BuildingResource::collection($city->buildings),
                'buildingQueue' => $city->buildingQueue ? new CityBuildingQueueResource($city->buildingQueue) : [],
            ];
        }

        return abort(403);
    }
}
