<?php

namespace App\Events;

use App\Http\Resources\CityShortInfoResource;
use App\Http\Resources\FleetDetailResource;
use App\Http\Resources\FleetResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FleetDataChangesEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fleet;
    public $action;
    public $fleetDetails;
    public $userId;
    public $cities;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $action, $fleet, $fleetsDetails, $cities)
    {
        $this->userId       = $userId;
        $this->action       = $action;
        $this->fleet        = new FleetResource($fleet);
        $this->fleetDetails = FleetDetailResource::collection($fleetsDetails);
        $this->cities       = CityShortInfoResource::collection($cities);
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
