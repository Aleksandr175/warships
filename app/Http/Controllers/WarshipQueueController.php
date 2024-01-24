<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\WarshipCreateRequest;
use App\Http\Resources\CityResourceV2Resource;
use App\Http\Resources\WarshipQueueResource;
use App\Models\City;
use App\Services\WarshipQueueService;
use Illuminate\Support\Facades\Auth;

class WarshipQueueController extends Controller
{
    public function run(WarshipCreateRequest $request, WarshipQueueService $warshipQueueService)
    {
        $user   = Auth::user();
        $data   = $request->only('cityId');
        $cityId = $data['cityId'];

        $queue = $warshipQueueService->store($user->id, $request);

        $city = City::where('id', $cityId)->where('user_id', $user->id)->first();

        $cityResources = $city->resources;

        return [
            'warships'      => [],//BuildingResource::collection($city->buildings),
            'queue'         => WarshipQueueResource::collection($queue),
            'cityResources' => CityResourceV2Resource::collection($cityResources)
        ];
    }
}
