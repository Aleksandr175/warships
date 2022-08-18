<?php

namespace App\Events;

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
    public $fleetsDetails;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($fleets, $fleetsDetails)
    {
        $this->fleets = FleetResource::collection($fleets);
        $this->fleetsDetails = FleetDetailResource::collection($fleetsDetails);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('fleets');
    }
}
