<?php

namespace App\Events;

use App\Http\Resources\RefiningQueueResource;
use App\Http\Resources\ResourceChangesResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityRefiningDataChangesEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $refiningQueue;
    public $resourceChanges;
    public $cityId;
    public $maxRefiningSlots;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $cityId, $refiningQueue, $resourceChanges, $maxRefiningSlots)
    {
        $this->userId           = $userId;
        $this->refiningQueue    = RefiningQueueResource::collection($refiningQueue);
        $this->maxRefiningSlots = $maxRefiningSlots;
        $this->resourceChanges  = ResourceChangesResource::collection($resourceChanges);
        $this->cityId           = $cityId;
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
