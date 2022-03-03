<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\BuildingCancelRequest;
use App\Http\Requests\Api\BuildRequest;
use App\Http\Resources\BuildingResource;
use App\Http\Resources\CityBuildingQueueResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CityResourcesResource;
use App\Models\CityBuildingQueue;
use Illuminate\Support\Facades\Auth;

class CityBuildingQueueController extends Controller
{
    public function build(BuildRequest $request) {
        $user = Auth::user();
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

        return abort(403);
    }

    public function cancel(BuildingCancelRequest $request) {
        $cityId = $request->post('cityId');

        $user = Auth::user();

        $city = $user->cities()->where('id', $cityId)->first();

        if ($city && $city->id) {
            $buildingQueue = $city->buildingQueue;

            if ($buildingQueue && $buildingQueue->id) {
                // update resource
                $city->update([
                    'gold' => $city->gold + $buildingQueue->gold,
                    'population' => $city->population + $buildingQueue->population,
                ]);

                $city->buildingQueue()->delete();

                return [
                    'buildings' => BuildingResource::collection($city->buildings),
                    'buildingQueue' => [],
                    'cityResources' => new CityResourcesResource($city)
                ];
            }
        }

        return abort(403);
    }
}
