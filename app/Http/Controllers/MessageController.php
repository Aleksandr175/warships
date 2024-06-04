<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityShortInfoResource;
use App\Http\Resources\Messages\MessageResource;
use App\Models\City;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(): array
    {
        $user = Auth::user();

        $messagesData = MessageService::collectMessagesData($user);

        return [
            'messages'       => MessageResource::collection($messagesData['messages']),
            'messagesNumber' => $messagesData['messagesNumber'],
            'messagesUnread' => $messagesData['messagesUnread'],
            'cities'         => CityShortInfoResource::collection($messagesData['cities']),
        ];
    }

    public function getMessage(Message $message)
    {
        $user = Auth::user();

        if ($message['user_id'] === $user->id) {
            $message->update(['is_read' => 1]);

            $cities = City::whereIn('id', [$message->city_id, $message->target_city_id])->get();

            // get message with additional resources and fleet details info
            $message->with('resources', 'fleetDetails', 'battleLog', 'battleLogDetails');

            return [
                'message' => new MessageResource($message),
                'cities'  => CityShortInfoResource::collection($cities),
            ];
        }

        abort(403);
    }
}
