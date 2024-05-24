<?php

namespace App\Events;

use App\Http\Resources\BuildingResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityBuildingDataUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $cityBuildings;
    public $cityId;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $cityId, $cityBuildings)
    {
        $this->userId        = $userId;
        $this->cityId        = $cityId;
        $this->cityBuildings = BuildingResource::collection($cityBuildings);
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
