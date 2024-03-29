<?php

namespace App\Services;

use App\Models\BattleLog;
use App\Models\BattleLogDetail;
use App\Models\City;
use App\Models\CityDictionary;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\Message;
use App\Models\WarshipDictionary;

class BattleService
{
    // handle battle process
    public function handle(Fleet $fleet): void
    {
        $targetCity       = (new City)->find($fleet->target_city_id);
        $city             = (new City)->find($fleet->city_id);
        $userId           = $city->user_id;
        $targetCityUserId = $targetCity->user_id;

        $attackingFleetDetails = FleetDetail::getFleetDetails([$fleet->id])->toArray();

        $warshipsDictionary = WarshipDictionary::get();

        dump("Battle logic. FleetID: $fleet->id, CityId: $city->id, TargetCityId: $targetCity->id, UserId: $userId");

        dump('AttackingFleetDetails', $attackingFleetDetails);

        // TODO: do i need it?
        if ($targetCity->city_dictionary_id === CityDictionary::PIRATE_BAY) {
            $attackingUserId = $userId;
            $defendingUserId = $targetCityUserId;
        } else {
            $attackingUserId = $targetCityUserId;
            $defendingUserId = $userId;
        }

        dump("Attacker is $attackingUserId, Defender is: $defendingUserId");

        $defendingFleetDetails = [];

        // TODO if we attack player's island
        // get warships in target island
        // summarize all fleets in city, all trade warships if exist (from other player)
        // ...

        //  get warships for player's bay
        // TODO: do it later

        $defendingWarships = $targetCity->warships;
        foreach ($defendingWarships as $warship) {
            $defendingFleetDetails[] = [
                'warship_id' => $warship['warship_id'],
                'qty'        => $warship['qty']
            ];
        }

        // set needed data, like health
        $attackingFleetDetails = $this->populateFleetDetailsWithCapacityAndHealth($attackingFleetDetails, $warshipsDictionary);
        $defendingFleetDetails = $this->populateFleetDetailsWithCapacityAndHealth($defendingFleetDetails, $warshipsDictionary);

        $logAttacking = [];
        $logDefending = [];
        $round        = 0;

        // calculate rounds while we have warships on each side
        do {
            $attackingForce = $this->calculateFleetAttack($attackingFleetDetails, $warshipsDictionary);
            // TODO: add Fortress attack value
            $defendingForce = $this->calculateFleetAttack($attackingFleetDetails, $warshipsDictionary);

            $attackingDamageToEachType = $attackingForce / count($defendingWarships);
            $defendingDamageToEachType = $defendingForce / count($attackingFleetDetails);

            [$defendingFleetDetails, $logAttacking[$round]] = $this->shoot($attackingDamageToEachType, $defendingFleetDetails);
            [$attackingFleetDetails, $logDefending[$round]] = $this->shoot($defendingDamageToEachType, $attackingFleetDetails);

            $round++;
        } while (count($defendingFleetDetails) > 0 && count($attackingFleetDetails) > 0);

        // get latest battle id
        $battleLog = (new \App\Models\BattleLog)->latest()->first();

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

        // $logAttacking - information about how much damage was dealt to the opposing side.
        dump('LOGS', 'Attack log: ', $logAttacking, 'Defence log: ', $logDefending);

        $takeGold       = 0;
        $takePopulation = 0;

        // calculate resources if attacker wins
        if ($winner === 'attacker') {
            [$takeGold, $takePopulation] = $this->moveResourcesToAttackerFleet($fleet, $attackingFleetDetails, $targetCity);
            $this->removeResourcesFromCity($targetCity, $takeGold, $takePopulation);
        }

        BattleLog::create([
            'battle_log_id'    => $newBattleLogId,
            'attacker_user_id' => $userId,
            'defender_user_id' => $targetCityUserId,
            'round'            => $round,
            'city_id'          => $targetCity->id,
            'winner'           => $winner,
            'gold'             => $takeGold,
            'population'       => $takePopulation
        ]);

        $fleetDetails = FleetDetail::where('fleet_id', $fleet->id)->get();
        // remove warships from fleet
        for ($i = 0, $iMax = count($fleetDetails); $i < $iMax; $i++) {
            $actualFleetDetails = null;
            for ($j = 0, $jMax = count($attackingFleetDetails); $j < $jMax; $j++) {
                if ($attackingFleetDetails[$j]['id'] === $fleetDetails[$i]->id) {
                    $actualFleetDetails = $attackingFleetDetails[$j];
                }
            }

            if ($actualFleetDetails) {
                $fleetDetails[$i]->update(['qty' => ceil($actualFleetDetails['qty'])]);
            } else {
                // remove fleet detail
                FleetDetail::where('id', $fleetDetails[$i]->id)->delete();
            }
        }

        // remove fleet without any details
        $updatedFleetDetails = FleetDetail::where('fleet_id', $fleet->id)->get();
        if (!count($updatedFleetDetails)) {
            Fleet::where('id', $fleet->id)->delete();
        }

        // remove destroyed warships from city
        $defendingWarships = $targetCity->warships;
        //dump('$defendingFleetDetails',$defendingFleetDetails);
        //dump('$defendingWarships',$defendingWarships);
        for ($i = 0, $iMax = count($defendingWarships); $i < $iMax; $i++) {
            $actualDefendingWarships = null;
            for ($j = 0, $jMax = count($defendingFleetDetails); $j < $jMax; $j++) {
                if ($defendingFleetDetails[$j]['warship_id'] === $defendingWarships[$i]->warship_id) {
                    $actualDefendingWarships = $defendingFleetDetails[$j];
                }
            }

            if ($actualDefendingWarships) {
                $defendingWarships[$i]->update(['qty' => ceil($actualDefendingWarships['qty'])]);
            } else {
                // remove warships from city
                $defendingWarships[$i]->delete();
            }
        }

        // for attacker
        Message::create([
            'user_id' => $userId,
            'content' => 'Battle happened.',
            'template_id' => config('constants.MESSAGE_TEMPLATE_IDS.BATTLE_ATTACK_HAPPENED'),
            'event_type' => 'Battle',
            'city_id' => $city->id,
            'target_city_id' => $targetCity->id,
            'battle_log_id' => $newBattleLogId
        ]);

        // for defender
        Message::create([
            'user_id' => $targetCityUserId,
            'content' => 'Battle happened.',
            'template_id' => config('constants.MESSAGE_TEMPLATE_IDS.BATTLE_DEFEND_HAPPENED'),
            'event_type' => 'Battle',
            'city_id' => $targetCity->id,
            'target_city_id' => $city->id,
        ]);


        // TODO: notify user about result somehow (websockets)?
        // ...


        // do i need it? i dont think so
        if ($targetCity->city_dictionary_id === CityDictionary::PLAYERS_ISLAND) {
            // TODO if we attack player's island
            // get warships in target island
            // summarize all fleets in city, all trade warships if exist (from other player)
            // ...

            // get warships for player's bay
            // TODO: do it later
        }

    }

    public function getAvailableCapacity(Fleet $fleet, $fleetDetails): int
    {
        $availableCapacity = 0;

        for ($i = 0, $iMax = count($fleetDetails); $i < $iMax; $i++) {
            $availableCapacity += ceil($fleetDetails[$i]['qty']) * $fleetDetails[$i]['capacity'];
        }

        return $availableCapacity - $fleet->gold - $fleet->population;
    }

    public function populateFleetDetailsWithCapacityAndHealth($fleetDetails, $warshipsDictionary)
    {
        foreach ($warshipsDictionary as $warshipDictionary) {
            for ($i = 0, $iMax = count($fleetDetails); $i < $iMax; $i++) {
                if ($fleetDetails[$i]['warship_id'] === $warshipDictionary['id']) {
                    $fleetDetails[$i]['health']   = $warshipDictionary['health'];
                    $fleetDetails[$i]['capacity'] = $warshipDictionary['capacity'];
                    break;
                }
            }
        }

        return $fleetDetails;
    }

    public function calculateFleetAttack($fleetDetails, $warshipsDictionary): int
    {
        $attackForce = 0;

        foreach ($warshipsDictionary as $warshipDictionary) {
            foreach ($fleetDetails as $detail) {
                if ($detail['warship_id'] === $warshipDictionary['id']) {
                    $attackForce += ceil($detail['qty']) * $warshipDictionary['attack'];
                    break;
                }
            }
        }

        return $attackForce;
    }

    // move resources from city to fleet (can't move more than capacity of fleet)
    public function moveResourcesToAttackerFleet(Fleet $fleet, $fleetDetails, City $city): array
    {
        $availableCapacity = $this->getAvailableCapacity($fleet, $fleetDetails);

        // we can take only 50% or resources
        $cityGold       = floor($city->gold / 2);
        $cityPopulation = floor($city->population / 2);

        dump("availableCapacity $availableCapacity, cityGold $cityGold, cityPopulation $cityPopulation");
        $takeGold       = 0;
        $takePopulation = 0;

        if ($cityGold > $availableCapacity) {
            $takeGold          = $availableCapacity;
            $availableCapacity = 0;
        } else {
            $availableCapacity -= $cityGold;
            $takeGold          = $cityGold;
        }

        if ($availableCapacity) {
            if ($cityPopulation > $availableCapacity) {
                $takePopulation    = $availableCapacity;
                $availableCapacity = 0;
            } else {
                $availableCapacity -= $cityPopulation;
                $takePopulation    = $cityPopulation;
            }
        }

        $fleet->increment('gold', $takeGold);
        $fleet->increment('population', $takePopulation);

        dump("availableCapacity left $availableCapacity, takeGold $takeGold, takePopulation $takePopulation");

        return [$takeGold, $takePopulation];
    }

    public function removeResourcesFromCity($city, $gold, $population)
    {
        $city->decrement('gold', $gold);
        $city->decrement('population', $population);
    }

    /**
     * @param $damageToEachWarshipType - int
     * @param $warships
     *
     * @return array
     */
    public function shoot($damageToEachWarshipType, $warshipGroups): array
    {
        $restDamage = 0;
        $log        = [];

        //dump('SHOOT');

        for ($i = 0, $iMax = count($warshipGroups); $i < $iMax; $i++) {
            $initialQty  = ceil($warshipGroups[$i]['qty']);
            $wholeHealth = $warshipGroups[$i]['qty'] * $warshipGroups[$i]['health'];
            $restDamage  += $damageToEachWarshipType;
            $logDamage   = $restDamage;

            // if we did 100 damage, but health was 80 -> it means that 20 damage will be done to next warship type
            // we save 20 to $restDamage
            if ($wholeHealth < $restDamage) {
                $restDamage  -= $wholeHealth;
                $wholeHealth = 0;
            } else {
                $wholeHealth -= $restDamage;
                $restDamage  = 0;
            }

            $warshipGroups[$i]['qty'] = $wholeHealth / $warshipGroups[$i]['health'];
            //dump('$warshipGroups[$i]', $warshipGroups[$i], $wholeHealth, $restDamage);

            $log[] = [
                'qty'        => $initialQty,
                'destroyed'  => $initialQty - ceil($warshipGroups[$i]['qty']),
                'warship_id' => $warshipGroups[$i]['warship_id'],
                'damage'     => $logDamage,
            ];

            if ($warshipGroups[$i]['qty'] === 0) {
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
}
