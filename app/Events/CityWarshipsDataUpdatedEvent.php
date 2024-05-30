<?php

namespace App\Events;

use App\Http\Resources\WarshipQueueResource;
use App\Http\Resources\WarshipResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityWarshipsDataUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $warships;
    public $warshipQueue;
    public $warshipSlots;
    public $cityId;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $cityId, $warships, $warshipQueue, $warshipSlots)
    {
        $this->userId       = $userId;
        $this->warships     = WarshipResource::collection($warships);
        $this->warshipQueue = WarshipQueueResource::collection($warshipQueue);
        $this->warshipSlots = $warshipSlots;
        $this->cityId       = $cityId;
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
