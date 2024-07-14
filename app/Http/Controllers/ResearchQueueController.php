<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ResearchRequest;
use App\Http\Resources\ResearchQueueResource;
use App\Http\Resources\ResearchResource;
use App\Http\Resources\ResourceChangesResource;
use App\Http\Resources\UserResourceResource;
use App\Services\FleetService;
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

        // get resources from queue
        $queueResources = $queue->resources;

        $city = $user->cities()->where('id', $cityId)->first();

        if ($queue && $queue->id) {
            return [
                'researches'      => ResearchResource::collection($user->researches),
                'researchQueue'   => new ResearchQueueResource($queue),
                'userResources'   => UserResourceResource::collection($user->resources),
                'resourceChanges' => ResourceChangesResource::collection((new FleetService())->getCityResourceChanges($queueResources, 'remove')),
                'cityId'          => $cityId
            ];
        }

        return abort(403);
    }

    public function cancel(ResearchQueueService $researchQueueService)
    {
        $user = Auth::user();

        $data = $researchQueueService->cancel($user->id);
        $city = $data['city'];
        $resourceChanges = $data['resourceChanges'];

        if ($city && $city->id) {
            return [
                'researches'      => ResearchResource::collection($user->researches),
                'researchQueue'   => [],
                'userResources'   => UserResourceResource::collection($user->resources),
                'resourceChanges' => ResourceChangesResource::collection((new FleetService())->getCityResourceChanges($resourceChanges, 'add')),
                'cityId'          => $city->id
            ];
        }

        return abort(403);
    }
}
