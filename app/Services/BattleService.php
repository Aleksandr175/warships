<?php

namespace App\Services;

use App\Models\Adventure;
use App\Models\BattleLog;
use App\Models\BattleLogDetail;
use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetResource;
use App\Models\Message;
use App\Models\WarshipCombatMultiplier;
use App\Models\WarshipDictionary;
use App\Models\WarshipImprovement;

class BattleService
{
    // handle battle process
    public function handle(Fleet $fleet): void
    {
        $targetCity          = City::find($fleet->target_city_id);
        $city                = City::find($fleet->city_id);
        $userId              = $city->user_id;
        $targetCityUserId    = $targetCity->user_id;
        $targetArchipelagoId = City::where('id', $targetCity->id)->first()->archipelago_id;

        $fortress = $targetCity->building(config('constants.BUILDINGS.FORTRESS'));

        $adventure = Adventure::where('archipelago_id', $targetArchipelagoId)->first();

        $isUserAttackingAdventureIsland = false;

        if (isset($adventure->id)) {
            $isUserAttackingAdventureIsland = true;
        }

        $attackingFleetDetails = FleetDetail::getFleetDetails([$fleet->id])->toArray();

        $warshipsDictionary = WarshipDictionary::get();

        dump("Battle logic. FleetID: $fleet->id, CityId: $city->id, TargetCityId: $targetCity->id, UserId: $userId");

        dump('AttackingFleetDetails', $attackingFleetDetails);

        $attackingUserId = $userId;
        $defendingUserId = $targetCityUserId;

        dump("Attacker ID is $attackingUserId, Defender ID is: $defendingUserId");

        // TODO if we attack player's island
        // get warships in target island
        // summarize all fleets in city, all trade warships if exist (from other player)
        // ...

        //  get warships for player's bay
        // TODO: do it later

        $round        = 0;
        $logAttacking = [];
        $logDefending = [];

        // set needed data for attacker, like health and capacity
        $attackingFleetDetails = $this->populateFleetDetailsWithCapacityAndHealth($attackingUserId, $attackingFleetDetails, $warshipsDictionary);

        dump('Populated attacking fleet details with bonuses: ', $attackingFleetDetails);

        $defendingFleetDetails = [];
        $defendingWarships     = $targetCity->warships;

        // if defender has no warships - skip this logic
        if ($defendingWarships && count($defendingWarships) > 0) {
            foreach ($defendingWarships as $warship) {
                $defendingFleetDetails[] = [
                    'warship_id' => $warship['warship_id'],
                    'qty'        => $warship['qty']
                ];
            }

            // set needed data for defender, like health and capacity
            $defendingFleetDetails = $this->populateFleetDetailsWithCapacityAndHealth($defendingUserId, $defendingFleetDetails, $warshipsDictionary);

            dump('Populated defending fleet details with bonuses: ', $defendingFleetDetails);

            $multipliers = WarshipCombatMultiplier::getMultipliers();  // Retrieve multipliers once at the beginning of the battle

            // calculate rounds while we have warships on each side
            do {
                // Calculate attack force per warship type
                $attackForces = $this->calculateFleetAttackForEachType($attackingFleetDetails, $fortress);
                $defendForces = $this->calculateFleetAttackForEachType($defendingFleetDetails, $fortress);

                //dump('$attackForces', $attackForces);
                //dump('$defendForces', $defendForces);

                // Distribute this attack force across enemy warships considering multipliers
                $attackerDamage = $this->distributeDamage($attackForces, $defendingFleetDetails, $multipliers);
                $defenderDamage = $this->distributeDamage($defendForces, $attackingFleetDetails, $multipliers);

                //dump('$attackerDamage', $attackerDamage);
                //dump('$defenderDamage', $defenderDamage);

                // Apply the distributed damage
                [$defendingFleetDetails, $logAttacking[$round]] = $this->shoot($attackerDamage, $defendingFleetDetails);
                [$attackingFleetDetails, $logDefending[$round]] = $this->shoot($defenderDamage, $attackingFleetDetails);

                $round++;
            } while (count($defendingFleetDetails) > 0 && count($attackingFleetDetails) > 0);
        }

        // get latest battle id
        $battleLog = BattleLog::latest()->first();

        if ($battleLog) {
            $newBattleLogId = $battleLog->battle_log_id + 1;
        } else {
            $newBattleLogId = 1;
        }

        for ($i = 0; $i < $round; $i++) {
            $data = [];
            for ($logIndex = 0, $logIndexMax = count($logAttacking[$i]); $logIndex < $logIndexMax; $logIndex++) {
                $newLogRow = [
                    'warship_id'    => $logAttacking[$i][$logIndex]['warship_id'],
                    'qty'           => $logAttacking[$i][$logIndex]['qty'],
                    'destroyed'     => $logAttacking[$i][$logIndex]['destroyed'],
                    'battle_log_id' => $newBattleLogId,
                    'round'         => $i + 1,
                    'user_id'       => $attackingUserId
                ];

                $data[] = $newLogRow;
            }

            BattleLogDetail::insert($data);

            $data = [];
            for ($logIndex = 0, $logIndexMax = count($logDefending[$i]); $logIndex < $logIndexMax; $logIndex++) {
                $newLogRow = [
                    'warship_id'    => $logDefending[$i][$logIndex]['warship_id'],
                    'qty'           => $logDefending[$i][$logIndex]['qty'],
                    'destroyed'     => $logDefending[$i][$logIndex]['destroyed'],
                    'battle_log_id' => $newBattleLogId,
                    'round'         => $i + 1,
                    'user_id'       => $defendingUserId
                ];

                $data[] = $newLogRow;
            }

            BattleLogDetail::insert($data);
        }

        $winner = count($attackingFleetDetails) > 0 ? 'attacker' : 'defender';

        // $logAttacking - information about how much damage was dealt to the opposite side.
        dump('LOGS', 'Attack log: ', $logAttacking, 'Defence log: ', $logDefending);

        dump('Attacker Fleet details left ', $attackingFleetDetails);
        dump('Defender Fleet details left ', $defendingFleetDetails);

        // calculate resources if attacker wins
        if ($winner === 'attacker') {
            dump('Attacker WON');
            $this->moveResourcesToAttackerFleetAndRemoveItFromCity($fleet, $attackingFleetDetails, $targetCity);

            $cityResources = $targetCity->resources->toArray();

            $fleet->update([
                'status_id' => config('constants.FLEET_STATUSES.ATTACK_COMPLETED'),
            ]);

            if ($isUserAttackingAdventureIsland) {
                // mark island as raided if it doesn't have any resources
                if (!count($cityResources)) {
                    $targetCity->update([
                        'raided' => 1
                    ]);
                }
            }
        }

        BattleLog::create([
            'battle_log_id'    => $newBattleLogId,
            'attacker_user_id' => $userId,
            'defender_user_id' => $targetCityUserId,
            'round'            => $round,
            'city_id'          => $targetCity->id,
            'winner'           => $winner,
            'fortress_percent' => $fortress?->lvl ? $fortress?->lvl + config('constants.FORTRESS_ATTACK_MULTIPLIER') : 0
        ]);

        $fleetDetails = FleetDetail::where('fleet_id', $fleet->id)->get();
        // remove warships from fleet
        for ($i = 0, $iMax = count($fleetDetails); $i < $iMax; $i++) {
            $actualQty = 0;
            for ($j = 0, $jMax = count($attackingFleetDetails); $j < $jMax; $j++) {
                if ($attackingFleetDetails[$j]['id'] === $fleetDetails[$i]->id) {
                    $actualQty = $attackingFleetDetails[$j]['qty'];
                }
            }

            if ($actualQty > 0) {
                $fleetDetails[$i]->update(['qty' => ceil($actualQty)]);
            } else {
                // remove fleet detail
                FleetDetail::where('id', $fleetDetails[$i]->id)->delete();
            }
        }

        // remove fleet without any details
        $updatedFleetDetails = FleetDetail::where('fleet_id', $fleet->id)->get();
        if (!count($updatedFleetDetails)) {
            FleetResource::where('fleet_id', $fleet->id)->delete();
            Fleet::where('id', $fleet->id)->delete();
        }

        // remove destroyed warships from city
        $defendingWarships = $targetCity->warships;
        //dump('$defendingFleetDetails',$defendingFleetDetails);
        //dump('$defendingWarships',$defendingWarships);
        for ($i = 0, $iMax = count($defendingWarships); $i < $iMax; $i++) {
            $actualQty = 0;
            for ($j = 0, $jMax = count($defendingFleetDetails); $j < $jMax; $j++) {
                if ($defendingFleetDetails[$j]['warship_id'] === $defendingWarships[$i]->warship_id) {
                    $actualQty = $defendingFleetDetails[$j]['qty'];
                }
            }

            if ($actualQty > 0) {
                $defendingWarships[$i]->update(['qty' => ceil($actualQty)]);
            } else {
                // remove warships from city
                $defendingWarships[$i]->delete();
            }
        }

        // for attacker
        $messageId = Message::create([
            'user_id'        => $userId,
            'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.BATTLE_ATTACK_HAPPENED'),
            'event_type'     => 'Battle',
            'city_id'        => $city->id,
            'target_city_id' => $targetCity->id,
            'battle_log_id'  => $newBattleLogId
        ])->id;

        (new MessageService())->addMessageAboutResources($fleet, $messageId);
        (new MessageService())->addMessageAboutFleetDetails($attackingFleetDetails, $messageId);

        // for defender
        if ($targetCityUserId) {
            $messageId = Message::create([
                'user_id'        => $targetCityUserId,
                'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.BATTLE_DEFEND_HAPPENED'),
                'event_type'     => 'Battle',
                'city_id'        => $targetCity->id,
                'target_city_id' => $city->id,
            ])->id;

            (new MessageService())->addMessageAboutFleetDetails($defendingFleetDetails, $messageId);
        }

        // TODO: notify user about result somehow (websockets)?
        // ...


        // do i need it? i dont think so
        if ($targetCity->city_dictionary_id === config('constants.CITY_TYPE_ID.ISLAND')) {
            // TODO if we attack player's island
            // get warships in target island
            // summarize all fleets in city, all trade warships if exist (from other player)
            // ...

            // get warships for player's bay
            // TODO: do it later
        }
    }

    /**
     * @param $attackForces
     * @param $targetFleetDetails
     * @param $multipliers
     *
     * We calculate damage from each attacker warship type to defender warship type,
     * we use multipliers to calculate damage from each attacker warship type to defender warship type,
     * we divide damage from each attacker warship type to defender warship type by number of defender warship types,
     * because we want to distribute damage equally between defender warship types
     *
     * @return array
     */
    private function distributeDamage($attackForces, $targetFleetDetails, $multipliers): array
    {
        $damageDistribution = [];
        $targetWarshipTypes = count($targetFleetDetails);

        foreach ($attackForces as $attackerType => $force) {
            foreach ($targetFleetDetails as $target) {
                $multiplier = $multipliers[$attackerType][$target['warship_id']] ?? 1;

                if (!isset($damageDistribution[$target['warship_id']])) {
                    $damageDistribution[$target['warship_id']] = 0;
                }
                $damageDistribution[$target['warship_id']] += ceil($force * $multiplier / $targetWarshipTypes);
            }
        }

        return $damageDistribution;
    }

    private function calculateFleetAttackForEachType($fleetDetails, $fortress = null)
    {
        $forces = [];
        foreach ($fleetDetails as $detail) {
            $forces[$detail['warship_id']] = $this->calculateIndividualWarshipAttack($detail, $fortress);
        }

        return $forces;
    }

    public function calculateIndividualWarshipAttack($fleetDetail, $fortress = null)
    {
        $attackForce = ceil($fleetDetail['qty']) * $fleetDetail['attack'];

        if ($fortress && $fortress->lvl) {
            $attackForce += floor($attackForce * (config('constants.FORTRESS_ATTACK_MULTIPLIER') + $fortress->lvl) / 100);
        }

        return $attackForce;
    }

    // get available capacity of fleet, we check if fleet already had some resources
    // if it had 100 and total capacity was 1000 -> we get 900 available capacity
    public function getAvailableCapacity(Fleet|null $fleet, $fleetDetails): int
    {
        $availableCapacity = 0;

        for ($i = 0, $iMax = count($fleetDetails); $i < $iMax; $i++) {
            $availableCapacity += ceil($fleetDetails[$i]['qty']) * $fleetDetails[$i]['capacity'];
        }

        if (!$fleet) {
            return $availableCapacity;
        }

        // get all resources of fleet
        $fleetResources = FleetResource::where('fleet_id', $fleet->id)->get();

        // subtract resources which we carry on fleet
        foreach ($fleetResources as $fleetResource) {
            $availableCapacity -= $fleetResource->qty;
        }

        return max($availableCapacity, 0);
    }

    public function populateFleetDetailsWithCapacityAndHealth(int $userId, $fleetDetails, $warshipsDictionary)
    {
        foreach ($warshipsDictionary as $warshipDictionary) {
            for ($i = 0, $iMax = count($fleetDetails); $i < $iMax; $i++) {
                if ($fleetDetails[$i]['warship_id'] === $warshipDictionary['id']) {
                    $fleetDetails[$i]['health']   = $warshipDictionary['health'];
                    $fleetDetails[$i]['capacity'] = $warshipDictionary['capacity'];
                    $fleetDetails[$i]['attack']   = $warshipDictionary['attack'];
                    break;
                }
            }
        }

        // get all bonuses
        $researchImprovements = [];
        $warshipImprovements  = WarshipImprovement::where('user_id', $userId)->get()->toArray();

        return $this->addWarshipsImprovementsBonuses($fleetDetails, $warshipsDictionary, $warshipImprovements, $researchImprovements);
    }

    // improve attack / health / capacity of warships by cards bonuses
    public function addWarshipsImprovementsBonuses($fleetDetails, $warshipsDictionary, $warshipImprovements, $researchImprovements)
    {
        // TODO: add researches bonuses

        foreach ($warshipImprovements as $warshipImprovement) {
            for ($i = 0, $iMax = count($fleetDetails); $i < $iMax; $i++) {
                if ($fleetDetails[$i]['warship_id'] === $warshipImprovement['warship_id']) {
                    $fleetDetails[$i][$warshipImprovement['improvement_type']] += floor($fleetDetails[$i][$warshipImprovement['improvement_type']] * $warshipImprovement['percent_improvement'] / 100);
                    break;
                }
            }
        }

        return $fleetDetails;
    }

    public function calculateFleetAttack($fleetDetails, $fortress = null): int
    {
        $attackForce = 0;

        foreach ($fleetDetails as $detail) {
            $attackForce += ceil($detail['qty']) * $detail['attack'];
        }

        if ($fortress && $fortress->lvl) {
            $attackForce += floor($attackForce * (config('constants.FORTRESS_ATTACK_MULTIPLIER') + $fortress->lvl) / 100);
        }

        return $attackForce;
    }

    // move resources from city to fleet (can't move more than capacity of fleet)
    public function moveResourcesToAttackerFleetAndRemoveItFromCity(Fleet $fleet, $fleetDetails, City $city)
    {
        $availableCapacity = $this->getAvailableCapacity($fleet, $fleetDetails);

        // we can take whole 100% of resources for common islands (pirates) and 100% for adventure islands

        $cityResources = $city->resources;

        dump("availableCapacity $availableCapacity");
        dump('cityResources', $cityResources);

        $distributedResources = $this->distributeResources($availableCapacity, $cityResources);

        if (count($distributedResources)) {
            foreach ($distributedResources as $distributedResource) {
                $this->moveResourceToFleet($fleet, $distributedResource);
            }

            $this->subtractResourcesFromCity($city, $distributedResources);
        }

        dump("availableCapacity left $availableCapacity");

        dump('Resources were taken, $distributedResources: ', $distributedResources);
    }

    /**
     * @param array $damageDistribution
     * @param array $warshipGroups
     *
     * @return array
     */
    public function shoot(array $damageDistribution, array $warshipGroups): array
    {
        $log = [];
        for ($i = 0, $iMax = count($warshipGroups); $i < $iMax; $i++) {
            $warshipType        = $warshipGroups[$i]['warship_id'];  // warship type we shoot in now
            $initialQty         = ceil($warshipGroups[$i]['qty']);
            $warshipGroupHealth = $warshipGroups[$i]['qty'] * $warshipGroups[$i]['health'];

            $damage    = $damageDistribution[$warshipGroups[$i]['warship_id']] ?? 0;
            $logDamage = $damage;

            if ($warshipGroupHealth < $damage) {
                $warshipGroupHealth = 0;
            } else {
                $warshipGroupHealth -= $damage;
            }

            $warshipGroups[$i]['qty'] = $warshipGroupHealth / $warshipGroups[$i]['health'];

            $log[] = [
                'qty'        => $initialQty,
                'destroyed'  => $initialQty - ceil($warshipGroups[$i]['qty']),
                'warship_id' => $warshipType,
                'damage'     => $logDamage,
            ];

            if ($warshipGroupHealth === 0) {
                array_splice($warshipGroups, $i, 1);
                $i--;
                $iMax--;

                if (count($warshipGroups) === 0) {
                    break;
                }
            }
        }

        return [$warshipGroups, $log];
    }

    public function distributeResources(int $availableCapacity, $resources)
    {
        $resourcesArray = $resources->toArray();

        // Step 1: Calculate total quantity
        $totalQuantity = array_sum(array_column($resourcesArray, 'qty'));

        // Initialize an array to store the distributed quantities
        $distributedResources = [];

        if ($availableCapacity > $totalQuantity) {
            $availableCapacity = $totalQuantity;
        }

        // Step 2: Calculate the proportion and distribute the available capacity
        foreach ($resourcesArray as $resource) {
            if ($resource['qty'] > 0) {
                // Calculate the proportion for each resource
                $proportion = $resource['qty'] / $totalQuantity;

                // Distribute the available capacity based on the proportion
                $distributedResources[] = [
                    'resource_id' => $resource['resource_id'],
                    'qty'         => (int)($proportion * $availableCapacity),
                ];
            }
        }

        return $distributedResources;
    }

    // $resource = array['resource_id' => 123, 'qty' => 100]
    public function moveResourceToFleet(Fleet $fleet, $resource)
    {
        if ($resource['qty'] < 1) {
            return;
        }

        $fleetResource = $fleet->resource($resource['resource_id']);

        if ($fleetResource) {
            $fleetResource->increment('qty', $resource['qty']);
        } else {
            FleetResource::create([
                'fleet_id'    => $fleet->id,
                'resource_id' => $resource['resource_id'],
                'qty'         => $resource['qty']
            ]);
        }
    }

    public function subtractResourcesFromCity(City $city, $resources): void
    {
        $cityService = new CityService();

        foreach ($resources as $resource) {
            if ($resource['qty'] < 1) {
                continue;
            }

            $cityService->addResourceToCity($city->id, $resource['resource_id'], $resource['qty'] * (-1));
        }
    }
}
