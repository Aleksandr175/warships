<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ResearchRequest;
use App\Http\Resources\CityResourcesResource;
use App\Http\Resources\ResearchQueueResource;
use App\Models\City;
use App\Models\ResearchQueue;
use App\Services\ResearchQueueService;
use Illuminate\Support\Facades\Auth;

class ResearchQueueController extends Controller
{
    public function run(ResearchRequest $request, ResearchQueueService $researchQueueService) {
        $user = Auth::user();
        $data = $request->only('cityId');
        $cityId = $data['cityId'];

        $city = $user->cities()->where('id', $cityId)->first();

        $queue = $researchQueueService->store($user->id, $request, $city);

        if ($queue && $queue->id) {
            return [
                'researches' => [],//BuildingResource::collection($city->buildings),
                'queue' => new ResearchQueueResource($queue),
                'cityResources' => new CityResourcesResource($city)
            ];
        }

        return abort(403);
    }

    public function cancel() {
        $user = Auth::user();

        $researchQueue = ResearchQueue::where('user_id', $user->id)->first();

        if ($researchQueue && $researchQueue->id) {
            // update resource
            $city = City::find($researchQueue->city_id);

            $city->update([
                'gold' => $city->gold + $researchQueue->gold,
                'population' => $city->population + $researchQueue->population,
            ]);

            $researchQueue->delete();

            return [
                'queue' => [],
                'cityResources' => new CityResourcesResource($city)
            ];
        }

        return abort(403);
    }
}
