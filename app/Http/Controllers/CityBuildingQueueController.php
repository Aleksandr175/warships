<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\BuildingCancelRequest;
use App\Http\Requests\Api\BuildRequest;
use App\Http\Resources\BuildingResource;
use App\Http\Resources\CityBuildingQueueResource;
use App\Http\Resources\CityResourceV2Resource;
use App\Services\BuildingQueueService;
use Illuminate\Support\Facades\Auth;

class CityBuildingQueueController extends Controller
{
    public function build(BuildRequest $request, BuildingQueueService $buildingQueueService)
    {
        $user   = Auth::user();
        $data   = $request->only('cityId');
        $cityId = $data['cityId'];

        $queue = $buildingQueueService->store($user->id, $request);

        $city = $user->cities()->where('id', $cityId)->first();

        $cityResources = $city->resources;

        if ($queue && $queue->id) {
            return [
                'buildings'     => BuildingResource::collection($city->buildings),
                'buildingQueue' => new CityBuildingQueueResource($city->buildingQueue),
                'cityResources' => CityResourceV2Resource::collection($cityResources)
            ];
        }

        return abort(403);
    }

    public function cancel(BuildingCancelRequest $request, BuildingQueueService $buildingQueueService)
    {
        $cityId = $request->post('cityId');

        $user = Auth::user();

        $city = $user->cities()->where('id', $cityId)->first();

        $cityResources = $city->resources;

        if ($city && $city->id) {
            $buildingQueueService->cancel($city);

            return [
                'buildings'     => BuildingResource::collection($city->buildings),
                'buildingQueue' => [],
                'cityResources' => CityResourceV2Resource::collection($cityResources)
            ];
        }

        return abort(403);
    }
}
