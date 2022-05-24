<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResearchQueueController extends Controller
{
    public function run() {
        /*$user = Auth::user();
        $data = $request->only('cityId', 'buildingId');
        $cityId = $data['cityId'];
        $buildingId = $data['buildingId'];

        $city = $user->cities()->where('id', $cityId)->first();

        if ($city && $city->id && $city->canBuild($buildingId)) {
            // check if queue is empty
            $queue = CityBuildingQueue::where('city_id', $city->id)->first();

            if (!$queue) {
                // order build
                $city->build($buildingId);

                return [
                    'buildings' => BuildingResource::collection($city->buildings),
                    'buildingQueue' => new CityBuildingQueueResource($city->buildingQueue),
                    'cityResources' => new CityResourcesResource($city)
                ];
            }
        }

        return abort(403);*/
    }
}
