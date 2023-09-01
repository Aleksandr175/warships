<?php

namespace App\Services;

use App\Events\CityDataUpdatedEvent;
use App\Events\FleetUpdatedEvent;
use App\Events\TestEvent;
use App\Http\Resources\FleetDetailResource;
use App\Http\Resources\FleetResource;
use App\Http\Resources\WarshipResource;
use App\Jobs\BattleJob;
use App\Models\BattleLog;
use App\Models\BattleLogDetail;
use App\Models\City;
use App\Models\CityDictionary;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetTaskDictionary;
use App\Models\User;
use App\Models\Warship;
use App\Models\WarshipDictionary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BattleService
{
    // handle battle process
    public function handle(Fleet $fleet)
    {
        $targetCity       = City::find($fleet->target_city_id);
        $city             = City::find($fleet->city_id);
        $userId           = $city->user_id;
        $targetCityUserId = $targetCity->user_id;

        $attackingFleetDetails = FleetDetail::getFleetDetails([$fleet->id])->toArray();

        $warshipsDictionary = WarshipDictionary::get();

        dump('Battle logic...');

        if ($targetCity->city_dictionary_id === CityDictionary::PIRATE_BAY) {
            $attackingUserId = $userId;
            $defendingUserId = $targetCityUserId;
        } else {
            $attackingUserId = $targetCityUserId;
            $defendingUserId = $userId;
        }

        //if ($targetCity->city_dictionary_id === CityDictionary::PIRATE_BAY) {
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
        foreach ($warshipsDictionary as $warshipDictionary) {
            for ($i = 0, $iMax = count($attackingFleetDetails); $i < $iMax; $i++) {
                if ($attackingFleetDetails[$i]['warship_id'] === $warshipDictionary['id']) {
                    $attackingFleetDetails[$i]['health'] = $warshipDictionary['health'];
                    break;
                }
            }

            for ($i = 0, $iMax = count($defendingFleetDetails); $i < $iMax; $i++) {
                if ($defendingFleetDetails[$i]['warship_id'] === $warshipDictionary['id']) {
                    $defendingFleetDetails[$i]['health'] = $warshipDictionary['health'];
                    break;
                }
            }
        }

        $logAttacking = [];
        $logDefending = [];
        $round        = 0;

        // calculate rounds while we have warships on each side
        do {
            $attackingForce = 0;
            $defendingForce = 0;

            foreach ($warshipsDictionary as $warshipDictionary) {
                foreach ($attackingFleetDetails as $detail) {
                    if ($detail['warship_id'] === $warshipDictionary['id']) {
                        $attackingForce += ceil($detail['qty']) * $warshipDictionary['attack'];
                        break;
                    }
                }

                foreach ($defendingFleetDetails as $detail) {
                    if ($detail['warship_id'] === $warshipDictionary['id']) {
                        $defendingForce += ceil($detail['qty']) * $warshipDictionary['attack'];
                        break;
                    }
                }
            }

            $attackingDamageToEachType = $attackingForce / count($defendingWarships);
            $defendingDamageToEachType = $defendingForce / count($attackingFleetDetails);

            [$defendingFleetDetails, $logAttacking[$round]] = $this->shoot($attackingDamageToEachType, $defendingFleetDetails);
            [$attackingFleetDetails, $logDefending[$round]] = $this->shoot($defendingDamageToEachType, $attackingFleetDetails);

            $round++;
        } while (count($defendingFleetDetails) > 0 && count($attackingFleetDetails) > 0);

        // get battle id
        $battleLog = BattleLog::latest()->first();

        if ($battleLog) {
            $battleLogId = $battleLog->battle_log_id + 1;
        } else {
            $battleLogId = 1;
        }

        for ($i = 0; $i < $round; $i++) {
            $data = [];
            for ($logIndex = 0, $logIndexMax = count($logAttacking[$i]); $logIndex < $logIndexMax; $logIndex++) {
                $newLogRow = [
                    'warship_id'    => $logAttacking[$i][$logIndex]['warship_id'],
                    'qty'           => $logAttacking[$i][$logIndex]['qty'],
                    'destroyed'     => $logAttacking[$i][$logIndex]['destroyed'],
                    'battle_log_id' => $battleLogId,
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
                    'battle_log_id' => $battleLogId,
                    'round'         => $i + 1,
                    'user_id'       => $defendingUserId
                ];

                $data[] = $newLogRow;
            }

            BattleLogDetail::insert($data);
        }

        BattleLog::create([
            'battle_log_id'    => $battleLogId,
            'attacker_user_id' => $userId,
            'defender_user_id' => $targetCityUserId,
            'round'            => $round,
            'city_id'          => $targetCity->id,
            'winner'           => count($attackingFleetDetails) > 0 ? 'attacker' : 'defender'
        ]);

        dump('LOGS', $logAttacking, $logDefending);

        if ($targetCity->city_dictionary_id === CityDictionary::PLAYERS_ISLAND) {
            // TODO if we attack player's island
            // get warships in target island
            // summarize all fleets in city, all trade warships if exist (from other player)
            // ...

            // get warships for player's bay
            // TODO: do it later
        }

    }

    /**
     * @param $damage - int
     * @param $warships
     *
     * @return array
     */
    public function shoot($damage, $warships): array
    {
        $restDamage = 0;
        $log        = [];

        //dump('SHOOT');

        for ($i = 0, $iMax = count($warships); $i < $iMax; $i++) {
            $wholeHealth = $warships[$i]['qty'] * $warships[$i]['health'];
            $restDamage  += $damage;
            $logDamage   = $restDamage;

            $startQty = ceil($warships[$i]['qty']);

            // if we did 100 damage, but health was 80 -> it means that 20 damage will be done to another warship type
            // we save 20 to $restDamage
            if ($wholeHealth < $restDamage) {
                $restDamage  -= $wholeHealth;
                $wholeHealth = 0;
            } else {
                $wholeHealth -= $restDamage;
                $restDamage  = 0;
            }

            $warships[$i]['qty'] = $wholeHealth / $warships[$i]['health'];
            //dump('$warships[$i]', $warships[$i], $wholeHealth, $restDamage);

            $log[] = [
                'qty'        => $startQty,
                'destroyed'  => $startQty - ceil($warships[$i]['qty']),
                'warship_id' => $warships[$i]['warship_id'],
                'damage'     => $logDamage,
            ];

            if ($warships[$i]['qty'] === 0) {
                array_splice($warships, $i, 1);
                $i--;
                $iMax--;

                if (count($warships) === 0) {
                    break;
                }
            }
        }

        return [$warships, $log];
    }
}
