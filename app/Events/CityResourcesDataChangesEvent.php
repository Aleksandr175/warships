<?php

namespace App\Events;

use App\Http\Resources\ResourceChangesResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityResourcesDataChangesEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $resourceChanges;
    public $cityId;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $cityId, $resources)
    {
        $this->userId          = $userId;
        $this->cityId          = $cityId;
        $this->resourceChanges = ResourceChangesResource::collection($resources);
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
