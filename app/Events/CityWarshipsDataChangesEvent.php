<?php

namespace App\Events;

use App\Http\Resources\WarshipChangeResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CityWarshipsDataChangesEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $cityWarshipsChanges;
    public $cityId;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $cityId, $warshipsChanges)
    {
        $this->userId              = $userId;
        $this->cityWarshipsChanges = WarshipChangeResource::collection($warshipsChanges);
        $this->cityId              = $cityId;
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
