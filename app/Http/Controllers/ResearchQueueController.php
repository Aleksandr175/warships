<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ResearchRequest;
use App\Http\Resources\CityResourceV2Resource;
use App\Http\Resources\ResearchQueueResource;
use App\Http\Resources\ResearchResource;
use App\Http\Resources\UserResourceResource;
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
                'researches'    => ResearchResource::collection($user->researches),
                'researchQueue' => new ResearchQueueResource($queue),
                'userResources' => UserResourceResource::collection($user->resources),
                'cityResources' => CityResourceV2Resource::collection($cityResources),
                'cityId'        => $cityId
            ];
        }

        return abort(403);
    }

    public function cancel(ResearchQueueService $researchQueueService)
    {
        $user = Auth::user();

        $city = $researchQueueService->cancel($user->id);

        if ($city && $city->id) {
            $cityResources = $city->resources;

            return [
                'researches'    => ResearchResource::collection($user->researches),
                'researchQueue' => [],
                'userResources' => UserResourceResource::collection($user->resources),
                'cityResources' => CityResourceV2Resource::collection($cityResources),
                'cityId'        => $city->id
            ];
        }

        return abort(403);
    }
}
