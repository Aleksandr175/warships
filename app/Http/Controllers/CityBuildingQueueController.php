<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\BuildingCancelRequest;
use App\Http\Requests\Api\BuildRequest;
use App\Http\Resources\BuildingResource;
use App\Http\Resources\CityBuildingQueueResource;
use App\Http\Resources\ResourceChangesResource;
use App\Services\BuildingQueueService;
use App\Services\ResourceService;
use Illuminate\Support\Facades\Auth;

class CityBuildingQueueController extends Controller
{
    public function build(BuildRequest $request, BuildingQueueService $buildingQueueService)
    {
        $user   = Auth::user();
        $data   = $request->only('cityId');
        $cityId = $data['cityId'];

        $queue = $buildingQueueService->store($user->id, $request);

        // get resources from queue
        $queueResources = $queue->resources;

        $city = $user->cities()->where('id', $cityId)->first();

        if ($queue && $queue->id) {
            return [
                'buildings'       => BuildingResource::collection($city->buildings),
                'buildingQueue'   => new CityBuildingQueueResource($city->buildingQueue),
                'resourceChanges' => ResourceChangesResource::collection((new ResourceService())->getResourceChanges($queueResources, 'remove')),
                'cityId'          => $cityId
            ];
        }

        return abort(403);
    }

    public function cancel(BuildingCancelRequest $request, BuildingQueueService $buildingQueueService)
    {
        $cityId = $request->post('cityId');

        $user = Auth::user();

        $city = $user->cities()->where('id', $cityId)->first();

        if ($city && $city->id) {
            $resourceChanges = $buildingQueueService->cancel($city);

            return [
                'buildings'       => BuildingResource::collection($city->buildings),
                'buildingQueue'   => [],
                'resourceChanges' => ResourceChangesResource::collection((new ResourceService())->getResourceChanges($resourceChanges, 'add')),
                'cityId'          => $city->id
            ];
        }

        return abort(403);
    }
}
