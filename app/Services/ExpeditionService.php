<?php

namespace App\Services;

use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\Message;
use App\Models\Resource;
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

        $userId = City::find($fleet->city_id)->user_id;

        if ($randomNumber <= 75) {
            // 75% chance: Gain resources
            $this->gainResources($userId, $fleet, $fleetDetails);
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
                'user_id'        => $user->id,
                'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_NOTHING'),
                'event_type'     => 'Expedition',
                'archipelago_id' => $city->archipelago_id,
                'coord_x'        => $city->coord_x,
                'coord_y'        => $city->coord_y,
            ]);
        }

        if (!$isDestroyed) {
            $statusId = config('constants.FLEET_STATUSES.EXPEDITION_DONE');

            $fleet->update([
                'status_id' => $statusId,
            ]);
        }
    }

    public function gainResources($userId, Fleet $fleet, $fleetDetails): void
    {
        $warshipsDictionary = WarshipDictionary::get();

        $fleetDetails = (new BattleService)->populateFleetDetailsWithCapacityAndHealth($userId, $fleetDetails, $warshipsDictionary);

        $availableCapacity = (new BattleService)->getAvailableCapacity($fleet, $fleetDetails);

        if ($availableCapacity > 0) {
            $resourcesDict = Resource::where('type', config('constants.RESOURCE_TYPE_IDS.COMMON'))
                ->get()->toArray();

            $resourceResearchDict = Resource::where('type', config('constants.RESOURCE_TYPE_IDS.RESEARCH'))
                ->get()->toArray();

            $resourcesCardsDict = Resource::where('type', config('constants.RESOURCE_TYPE_IDS.CARD'))
                ->get()->toArray();

            // TODO: add coefficient for capacity depends on lucky, we can get small/medium/large amount of resources
            // example: 50% change get small amount of resources, 40% medium and 10% large amount
            $distributedResources = $this->distributeResources(floor($availableCapacity / 4), $resourcesDict);

            dump($distributedResources, $availableCapacity);

            foreach ($distributedResources as $resource) {
                (new BattleService())->moveResourceToFleet($fleet, $resource);
            }

            // we can find some cards in expedition
            $cardInfo = $this->getWarshipCards($resourcesCardsDict);

            if ($cardInfo['qty'] > 0) {
                dump('GOT CARD', $cardInfo);
                (new BattleService())->moveResourceToFleet($fleet, $cardInfo);
            }

            // we find some knowledge in expedition
            $researchResource = $this->getResearchResources($resourceResearchDict);
            dump('GOT RESEARCH RESOURCES', $researchResource);
            (new BattleService())->moveResourceToFleet($fleet, $researchResource);

            $city = City::find($fleet->city_id);
            $user = User::find($city->user_id);

            $messageId = Message::create([
                'user_id'        => $user->id,
                'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_RESOURCES'),
                'event_type'     => 'Expedition',
                'archipelago_id' => $city->archipelago_id,
                'coord_x'        => $city->coord_x,
                'coord_y'        => $city->coord_y,
            ])->id;
            (new MessageService())->addMessageAboutResources($fleet, $messageId);
        }
    }

    public function loseEntireFleet(Fleet $fleet, $fleetDetails): void
    {
        $city = City::find($fleet->city_id);
        $user = User::find($city->user_id);

        $fleet->resources()->delete();
        $fleet->delete();

        $messageId = Message::create([
            'user_id'        => $user->id,
            'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_LOST'),
            'event_type'     => 'Expedition',
            'archipelago_id' => $city->archipelago_id,
            'coord_x'        => $city->coord_x,
            'coord_y'        => $city->coord_y,
        ])->id;
        // info about damaged/lost warships
        (new MessageService())->addMessageAboutFleetDetails($fleetDetails, $messageId);

        foreach ($fleetDetails as $fleetDetail) {
            $fleetDetail->delete();
        }

        dump('delete fleet');
    }

    public function handleStormDamage(Fleet $fleet, $fleetDetails): void
    {
        $messageFleetDetails = [];

        foreach ($fleetDetails as $fleetDetail) {
            $newQty = floor($fleetDetail['qty'] * 0.8);

            $messageFleetDetails[] = [
                'qty'        => $fleetDetail['qty'] - $newQty,
                'warship_id' => $fleetDetail['warship_id'],
                'fleet_id'   => $fleet->id
            ];

            $fleetDetail->update(['qty' => $newQty]);

            if ($fleetDetail['qty'] < 1) {
                $fleetDetail->delete();
            }
        }

        $city = City::find($fleet->city_id);
        $user = User::find($city->user_id);

        if (!count($fleetDetails)) {
            $fleet->resources()->delete();
            $fleet->delete();

            $messageId = Message::create([
                'user_id'        => $user->id,
                'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_LOST'),
                'event_type'     => 'Expedition',
                'archipelago_id' => $city->archipelago_id,
                'coord_x'        => $city->coord_x,
                'coord_y'        => $city->coord_y,
            ]);
        } else {
            $messageId = Message::create([
                'user_id'        => $user->id,
                'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_STORM'),
                'event_type'     => 'Expedition',
                'archipelago_id' => $city->archipelago_id,
                'coord_x'        => $city->coord_x,
                'coord_y'        => $city->coord_y,
            ])->id;

        }

        // info about damaged/lost warships
        (new MessageService())->addMessageAboutFleetDetails($messageFleetDetails, $messageId);

        dump('handle storm damage');
    }

    public function distributeResources(int $availableCapacity, $resourceDictionaryArr)
    {
        // Step 1: Calculate Total Value of resources
        $totalValue = array_reduce($resourceDictionaryArr, static function ($carry, $resource) {
            return $carry + $resource['value'];
        }, 0);

        $qtyOfResources          = count($resourceDictionaryArr);
        $capacityForEachResource = $totalValue / $qtyOfResources; // 60 capacity / 3 = 20 capacity for each resource

        // Step 2: Distribute Resources with capacity per each resource
        $totalQty             = 0;
        $distributedResources = [];
        foreach ($resourceDictionaryArr as $resource) {
            $distributedQty         = ($capacityForEachResource / $resource['value']);
            $distributedResources[] = [
                'resource_id' => $resource['id'],
                'qty'         => $distributedQty,
            ];

            $totalQty += $distributedQty;
        }

        // Step 3: Fill whole capacity with resources by multiplying qty by coefficient
        $coefficient = $availableCapacity / $totalQty; // 60 / 30 = 2; - we multiply all distributed resources by 2

        for ($i = 0, $iMax = count($distributedResources); $i < $iMax; $i++) {
            $distributedResources[$i]['qty'] = floor($distributedResources[$i]['qty'] * $coefficient);
        }

        // Step 4: Add some changes not to get resource
        // TODO: use rand for qty? Depends on value, bigger value - less coefficient for rand

        return $distributedResources;
    }

    public function getWarshipCards($resourcesCards)
    {
        $randomArrayIndex = array_rand($resourcesCards, 1);
        $randomCard       = $resourcesCards[$randomArrayIndex];
        $randomQty        = random_int(0, 2);

        return [
            'resource_id' => $randomCard['id'],
            'qty'         => $randomQty
        ];
    }

    public function getResearchResources($resources)
    {
        $randomArrayIndex = array_rand($resources, 1);
        $randomCard       = $resources[$randomArrayIndex];
        $randomQty        = random_int(1, 5);

        return [
            'resource_id' => $randomCard['id'],
            'qty'         => $randomQty
        ];
    }
}
