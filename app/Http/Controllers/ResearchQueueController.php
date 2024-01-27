<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ResearchRequest;
use App\Http\Resources\CityResourcesResource;
use App\Http\Resources\CityResourceV2Resource;
use App\Http\Resources\ResearchQueueResource;
use App\Services\ResearchQueueService;
use Illuminate\Support\Facades\Auth;

class ResearchQueueController extends Controller
{
    public function run(ResearchRequest $request, ResearchQueueService $researchQueueService)
    {
        $user   = Auth::user();
        $data   = $request->only('cityId');
        $cityId = $data['cityId'];

        $queue = $researchQueueService->store($user->id, $request);

        $city = $user->cities()->where('id', $cityId)->first();

        $cityResources = $city->resources;

        if ($queue && $queue->id) {
            return [
                'researches'    => [],//BuildingResource::collection($city->buildings),
                'queue'         => new ResearchQueueResource($queue),
                'cityResources' => CityResourceV2Resource::collection($cityResources)
            ];
        }

        return abort(403);
    }

    public function cancel(ResearchQueueService $researchQueueService)
    {
        $user = Auth::user();

        $city = $researchQueueService->cancel($user->id);

        return [
            'queue'         => [],
            'cityResources' => new CityResourcesResource($city)
        ];
    }
}
