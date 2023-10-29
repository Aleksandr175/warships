<?php

namespace App\Services;

use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\Message;
use App\Models\User;
use App\Models\WarshipDictionary;

class ExpeditionService
{
    public function handle(Fleet $fleet): void
    {
        dump('Handle expedition result...');
        // Generate a random number between 1 and 100
        $randomNumber = random_int(1, 100);

        $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

        $isDestroyed = false;

        if ($randomNumber <= 75) {
            // 75% chance: Gain resources
            $this->gainResources($fleet, $fleetDetails);
        } elseif ($randomNumber <= 76) {
            // 1% chance: Lose the entire fleet
            $this->loseEntireFleet($fleet, $fleetDetails);
            $isDestroyed = true;
        } elseif ($randomNumber <= 80) {
            // 4% chance: A storm damages 20% of the fleet
            $this->handleStormDamage($fleet, $fleetDetails);
        } else {
            // 20% chance: Nothing happens
            // Continue with the expedition without any changes

            $city = City::find($fleet->city_id);
            $user = User::find($city->user_id);

            Message::create([
                'user_id' => $user->id,
                'content' => 'Expedition Fleet found nothing.',
                'template_id' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_NOTHING'),
                'event_type' => 'Expedition',
                'archipelago_id' => $city->archipelago_id,
                'coord_x' => $city->coord_x,
                'coord_y' => $city->coord_y,
            ]);
        }

        if (!$isDestroyed) {
            $statusId = config('constants.FLEET_STATUSES.EXPEDITION_GOING_BACK');

            $fleet->update([
                'status_id' => $statusId,
            ]);
        }
    }

    public function gainResources(Fleet $fleet, $fleetDetails): void {
        $warshipsDictionary = WarshipDictionary::get();

        $fleetDetails = (new BattleService)->populateFleetDetailsWithCapacityAndHealth($fleetDetails, $warshipsDictionary);

        $availableCapacity = (new BattleService)->getAvailableCapacity($fleet, $fleetDetails);

        if ($availableCapacity > 0) {
            $goldAmount = random_int(1, $availableCapacity);

            $fleet->increment('gold', $goldAmount);

            dump('gain resources', $goldAmount);

            $city = City::find($fleet->city_id);
            $user = User::find($city->user_id);

            Message::create([
                'user_id' => $user->id,
                'content' => 'Expedition Fleet found resources.',
                'template_id' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_RESOURCES'),
                'gold' => $goldAmount,
                'event_type' => 'Expedition',
                'archipelago_id' => $city->archipelago_id,
                'coord_x' => $city->coord_x,
                'coord_y' => $city->coord_y,
            ]);
        }
    }

    public function loseEntireFleet(Fleet $fleet, $fleetDetails): void {
        $fleet->delete();

        foreach ($fleetDetails as $fleetDetail) {
            $fleetDetail->delete();
        }

        $city = City::find($fleet->city_id);
        $user = User::find($city->user_id);

        Message::create([
            'user_id' => $user->id,
            'content' => 'Expedition Fleet was lost.',
            'template_id' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_LOST'),
            'event_type' => 'Expedition',
            'archipelago_id' => $city->archipelago_id,
            'coord_x' => $city->coord_x,
            'coord_y' => $city->coord_y,
        ]);


        dump('delete fleet');
    }

    public function handleStormDamage(Fleet $fleet, $fleetDetails): void {
        foreach ($fleetDetails as $fleetDetail) {
            $fleetDetail->update(['qty' => floor($fleetDetail['qty'] * 0.8)]);

            if ($fleetDetail['qty'] < 1) {
                $fleetDetail->delete();
            }
        }

        $city = City::find($fleet->city_id);
        $user = User::find($city->user_id);

        if (!count($fleetDetails)) {
            $fleet->delete();

            Message::create([
                'user_id' => $user->id,
                'content' => 'Expedition Fleet was lost.',
                'template_id' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_LOST'),
                'event_type' => 'Expedition',
                'archipelago_id' => $city->archipelago_id,
                'coord_x' => $city->coord_x,
                'coord_y' => $city->coord_y,
            ]);
        } else {
            // TODO: add info about damaged warships
            Message::create([
                'user_id' => $user->id,
                'content' => 'Expedition Fleet was caught in a storm. Some warships have been destroyed',
                'template_id' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_STORM'),
                'event_type' => 'Expedition',
                'archipelago_id' => $city->archipelago_id,
                'coord_x' => $city->coord_x,
                'coord_y' => $city->coord_y,
            ]);
        }

        dump('handle storm damage');
    }
}
