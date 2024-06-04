<?php

namespace App\Events;

use App\Http\Resources\UserShortResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessagesDataUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $cities;
    public $messages;
    public $messagesNumber;
    public $messagesUnread;

    /**
     * Create a new event instance.
     */
    public function __construct($user, $cities, $messages, $messagesNumber, $messagesUnread)
    {
        $this->user           = new UserShortResource($user);
        $this->citites        = $cities;
        $this->messagesNumber = $messagesNumber;
        $this->messagesUnread = $messagesUnread;
        $this->messages       = $messages;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }
}
