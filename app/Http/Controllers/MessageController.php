<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $messages       = Message::where('user_id', $user->id)->paginate(10);
        $messagesUnread = Message::where('user_id', $user->id)->where('is_read', 0)->count();

        return [
            'messages'       => MessageResource::collection($messages),
            'messagesUnread' => $messagesUnread
        ];
    }

    public function getMessage(Message $message)
    {
        $user = Auth::user();

        if ($message['user_id'] === $user->id) {
            $message->update(['is_read' => 1]);

            return [
                'message' => new MessageResource($message),
            ];
        }

        abort(403);
    }
}
