<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\WarshipCreateRequest;
use App\Http\Resources\CityResourcesResource;
use App\Http\Resources\WarshipQueueResource;
use Illuminate\Support\Facades\Auth;

class WarshipQueueController extends Controller
{
    public function run(WarshipCreateRequest $request) {
        $user = Auth::user();
        $data = $request->only('cityId', 'warshipId', 'qty');
        $cityId = $data['cityId'];
        $warshipId = $data['warshipId'];
        $qty = $data['qty'];

        $city = $user->cities()->where('id', $cityId)->first();

        if ($city && $city->id) {
            $city->orderWarship($warshipId, $qty);

            return [
                'warships' => [],//BuildingResource::collection($city->buildings),
                'queue' => WarshipQueueResource::collection($city->warshipQueues),
                'cityResources' => new CityResourcesResource($city)
            ];
        }

        return abort(403);
    }
}
