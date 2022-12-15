<?php

namespace App\Services;

use App\Events\CityDataUpdatedEvent;
use App\Events\FleetUpdatedEvent;
use App\Events\TestEvent;
use App\Http\Resources\FleetDetailResource;
use App\Http\Resources\FleetResource;
use App\Http\Resources\WarshipResource;
use App\Jobs\BattleJob;
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
        $targetCity = City::find($fleet->target_city_id);

        $attackingFleetDetails = FleetDetail::getFleetDetails([$fleet->id])->toArray();

        $warshipsDictionary = WarshipDictionary::get();

        if ($targetCity->city_dictionary_id === CityDictionary::PIRATE_BAY) {
            $defendingFleetDetails = [];

            // get warships for pirate bay
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

            $logAttacking  = [];
            $logDefending  = [];
            $round = 0;

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


            dump('LOGS', $logAttacking, $logDefending);
            dd($attackingFleetDetails, $defendingFleetDetails);
            // TODO calculate result of whole battle
            // TODO put logs to db
            // ...
        }

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

        dump('SHOOT');

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
