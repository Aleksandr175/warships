<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\WarshipCreateRequest;
use App\Http\Resources\ResourceChangesResource;
use App\Http\Resources\WarshipQueueResource;
use App\Services\ResourceService;
use App\Services\WarshipQueueService;
use Illuminate\Support\Facades\Auth;

class WarshipQueueController extends Controller
{
    public function run(WarshipCreateRequest $request, WarshipQueueService $warshipQueueService)
    {
        $user   = Auth::user();
        $data   = $request->only('cityId');
        $cityId = $data['cityId'];

        $data            = $warshipQueueService->store($user->id, $request);
        $warshipQueue    = $data['queue'];
        $resourceChanges = $data['resourceChanges'];

        return [
            // TODO: add changes for queue?
            'warshipQueue'    => WarshipQueueResource::collection($warshipQueue),
            'resourceChanges' => ResourceChangesResource::collection((new ResourceService())->getResourceChanges($resourceChanges, 'remove')),
            'cityId'          => $cityId
        ];
    }
}
