<?php

namespace App\Services;

use App\Models\UserResource;

class UserService
{
    public function addResourceToUser(int $userId, int $resourceId, int $qty): void
    {
        $resource = UserResource::where('user_id', $userId)->where('resource_id', $resourceId)->first();

        if ($resource) {
            $resource->increment('qty', $qty);
        } else {
            UserResource::create([
                'user_id'     => $userId,
                'resource_id' => $resourceId,
                'qty'         => $qty
            ]);
        }
    }
}
