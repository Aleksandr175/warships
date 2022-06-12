<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\BuildingCancelRequest;
use App\Http\Requests\Api\BuildRequest;
use App\Http\Resources\BuildingResource;
use App\Http\Resources\CityBuildingQueueResource;
use App\Http\Resources\CityResourcesResource;
use App\Services\BuildingQueueService;
use Illuminate\Support\Facades\Auth;

class CityBuildingQueueController extends Controller
{
    public function build(BuildRequest $request, BuildingQueueService $buildingQueueService) {
        $user = Auth::user();
        $data = $request->only('cityId');
        $cityId = $data['cityId'];

        $city = $user->cities()->where('id', $cityId)->first();

        $queue = $buildingQueueService->store($user->id, $request, $city);

        if ($queue && $queue->id) {
            return [
                'buildings' => BuildingResource::collection($city->buildings),
                'queue' => new CityBuildingQueueResource($city->buildingQueue),
                'cityResources' => new CityResourcesResource($city)
            ];
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
