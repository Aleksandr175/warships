<?php

namespace App\Services;

use App\Events\MessagesDataUpdatedEvent;
use App\Models\City;
use App\Models\Fleet;
use App\Models\Message;
use App\Models\MessageFleetDetail;
use App\Models\MessageFleetResource;
use App\Models\User;

class MessageService
{
    public function addMessageAboutResources(Fleet $fleet, int $messageId): void
    {
        $fleetResources = $fleet->resources;

        foreach ($fleetResources as $resource) {
            MessageFleetResource::create([
                'message_id'  => $messageId,
                'resource_id' => $resource->resource_id,
                'qty'         => $resource->qty
            ]);
        }
    }

    public function addMessageAboutFleetDetails($fleetDetails, int $messageId): void
    {
        foreach ($fleetDetails as $fleetDetail) {
            MessageFleetDetail::create([
                'qty'        => $fleetDetail['qty'],
                'warship_id' => $fleetDetail['warship_id'],
                'message_id' => $messageId,
            ]);
        }
    }

    public static function collectMessagesData(User $user)
    {
        $messages       = Message::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        $messagesUnread = Message::where('user_id', $user->id)->where('is_read', 0)->count();

        $cityIds       = $messages->pluck('city_id')->toArray();
        $targetCityIds = $messages->pluck('target_city_id')->toArray();

        $cities = City::whereIn('id', array_merge($cityIds, $targetCityIds))->get();

        return [
            'messages'       => $messages,
            'messagesNumber' => $messages->total(),
            'messagesUnread' => $messagesUnread,
            'cities'         => $cities,
        ];
    }

    public function sendMessagesUpdatedEvent(User $user): void
    {
        $messagesData = self::collectMessagesData($user);

        MessagesDataUpdatedEvent::dispatch($user, $messagesData['cities'], $messagesData['messages'], $messagesData['messagesNumber'], $messagesData['messagesUnread']);
    }
}
