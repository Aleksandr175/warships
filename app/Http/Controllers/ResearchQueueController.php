<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ResearchRequest;
use App\Http\Resources\CityResourcesResource;
use App\Http\Resources\ResearchQueueResource;
use App\Models\ResearchQueue;
use Illuminate\Support\Facades\Auth;

class ResearchQueueController extends Controller
{
    public function run(ResearchRequest $request) {
        $user = Auth::user();
        $data = $request->only('cityId', 'researchId');
        $cityId = $data['cityId'];
        $researchId = $data['researchId'];

        $city = $user->cities()->where('id', $cityId)->first();

        if ($city && $city->id && $city->canResearch($researchId)) {
            // check if queue is empty
            $queue = ResearchQueue::where('user_id', $user->id)->where('research_id', $researchId)->first();

            if (!$queue) {
                // order research
                $city->orderResearch($researchId);

                return [
                    'researches' => [],//BuildingResource::collection($city->buildings),
                    'queue' => new ResearchQueueResource($user->researchesQueue),
                    'cityResources' => new CityResourcesResource($city)
                ];
            }
        }

        return abort(403);
    }
}
