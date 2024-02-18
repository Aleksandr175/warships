<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\RefiningRequest;
use App\Http\Resources\CityResourceV2Resource;
use App\Http\Resources\RefiningQueueResource;
use App\Services\RefiningQueueService;
use Illuminate\Support\Facades\Auth;

class RefiningQueueController extends Controller
{
    public function run(RefiningRequest $request, RefiningQueueService $refiningQueueService)
    {
        $user   = Auth::user();

        if (!$user) {
            return false;
        }

        $data   = $request->only('cityId', 'recipeId', 'qty');
        $cityId = $data['cityId'];

        $refiningQueueService->store($user->id, $request);

        $city = $user->cities()->where('id', $cityId)->first();

        $cityResources = $city->resources;

        $queue = $city->refiningQueue;

        if ($queue) {
            return [
                'queue'         => RefiningQueueResource::collection($queue),
                'cityResources' => CityResourceV2Resource::collection($cityResources)
            ];
        }

        return abort(403);
    }

}
