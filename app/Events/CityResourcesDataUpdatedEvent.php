<?php

namespace App\Events;

use App\Http\Resources\CityResourceV2Resource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityResourcesDataUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $cityResources;
    public $cityId;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $cityId, $cityResources)
    {
        $this->userId        = $userId;
        $this->cityId        = $cityId;
        $this->cityResources = CityResourceV2Resource::collection($cityResources);
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
