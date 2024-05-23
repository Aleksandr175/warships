<?php

namespace App\Events;

use App\Http\Resources\CityShortInfoResource;
use App\Http\Resources\FleetDetailResource;
use App\Http\Resources\FleetResource;
use App\Models\Fleet;
use App\Models\FleetDetail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FleetUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fleets;
    public $fleetDetails;
    public $user;
    public $cities;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $fleets, $fleetsDetails, $cities)
    {
        $this->user         = $user;
        $this->fleets       = FleetResource::collection($fleets);
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
        return new PrivateChannel('user.' . $this->user['id']);
    }
}
