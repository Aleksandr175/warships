<?php

namespace App\Services;

use App\Models\Fleet;
use App\Models\MessageFleetDetail;
use App\Models\MessageFleetResource;

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

    public function addMessageAboutFleetDetails($fleetDetails, int $messageId):void {
        foreach ($fleetDetails as $fleetDetail) {
            MessageFleetDetail::create([
                'qty'        => $fleetDetail['qty'],
                'warship_id' => $fleetDetail['warship_id'],
                'fleet_id'   => $fleetDetail['fleet_id'],
                'message_id' => $messageId,
            ]);
        }

    }
}
