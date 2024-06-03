<?php

namespace App\Events;

use App\Http\Resources\CityResourceV2Resource;
use App\Http\Resources\UserResourceResource;
use App\Http\Resources\UserShortResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityResourcesDataUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $cityResources;
    public $cityId;
    public $userResources;

    /**
     * Create a new event instance.
     */
    public function __construct($user, $cityId, $cityResources)
    {
        $this->user          = new UserShortResource($user);
        $this->cityId        = $cityId;
        $this->cityResources = CityResourceV2Resource::collection($cityResources);
        $this->userResources = UserResourceResource::collection($user->load('resources')->resources);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }
}
