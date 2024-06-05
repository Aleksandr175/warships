<?php

namespace App\Events;

use App\Http\Resources\CityResourceV2Resource;
use App\Http\Resources\RefiningQueueResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityRefiningDataUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $refiningQueue;
    public $cityResources;
    public $cityId;
    public $refiningSlots;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $cityId, $refiningQueue, $refiningSlots, $cityResources)
    {
        $this->userId        = $userId;
        $this->refiningQueue = RefiningQueueResource::collection($refiningQueue);
        $this->refiningSlots = $refiningSlots;
        $this->cityResources = CityResourceV2Resource::collection($cityResources);
        $this->cityId        = $cityId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }
}
