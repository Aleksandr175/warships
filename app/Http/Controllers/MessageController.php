<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityShortInfoResource;
use App\Http\Resources\MessageResource;
use App\Models\City;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(): array
    {
        $user = Auth::user();

        $messages       = Message::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        $messagesUnread = Message::where('user_id', $user->id)->where('is_read', 0)->count();

        $cityIds       = $messages->pluck('city_id')->toArray();
        $targetCityIds = $messages->pluck('target_city_id')->toArray();

        $cities = City::whereIn('id', array_merge($cityIds, $targetCityIds))->get();

        return [
            'messages'       => MessageResource::collection($messages),
            'messagesNumber' => $messages->total(),
            'messagesUnread' => $messagesUnread,
            'cities'         => CityShortInfoResource::collection($cities),
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
