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
    private $cityId              = null;
    private $coordX              = null;
    private $coordY              = null;
    private $fleetDetails        = [];
    private $updatedFleetDetails = [];
    private $repeating           = false;
    private $taskType            = null;
    private $targetCity          = null;
    private $taskTypeId          = null;

    // handle battle process
    public function handle(Fleet $fleet)
    {
        $targetCity = City::find($fleet->target_city_id);

        $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

        $warshipsDictionary = WarshipDictionary::get();

        if ($targetCity->city_dictionary_id === CityDictionary::PIRATE_BAY) {
            dump('pirate bay');

            // get warships for pirate bay
            $enemyWarships = $targetCity->warships;
            foreach ($enemyWarships as $warship) {
                $enemyFleetDetails[] = [
                    'warship_id' => $warship['warship_id'],
                    'qty'        => $warship['qty']
                ];
            }

            // set needed data, like health
            foreach ($warshipsDictionary as $warshipDictionary) {
                for ($i = 0, $iMax = count($fleetDetails); $i < $iMax; $i++) {
                    if ($fleetDetails[$i]['warship_id'] === $warshipDictionary['id']) {
                        $fleetDetails[$i]['health'] = $warshipDictionary['health'];
                        break;
                    }
                }

                for ($i = 0, $iMax = count($enemyFleetDetails); $i < $iMax; $i++) {
                    if ($enemyFleetDetails[$i]['warship_id'] === $warshipDictionary['id']) {
                        $enemyFleetDetails[$i]['health'] = $warshipDictionary['health'];
                        break;
                    }
                }
            }

            // calculate rounds while we have warships on each side
            do {
                $fleetForce            = 0;
                $enemyForce            = 0;
                $fleetTypes            = 0;
                $pirateBayWarshipTypes = 0;

                foreach ($warshipsDictionary as $warshipDictionary) {
                    foreach ($fleetDetails as $fleetDetail) {
                        if ($fleetDetail['warship_id'] === $warshipDictionary['id']) {
                            $fleetForce += $fleetDetail['qty'] * $warshipDictionary['attack'];
                            ++$fleetTypes;
                            break;
                        }
                    }

                    foreach ($enemyFleetDetails as $fleetDetail) {
                        if ($fleetDetail['warship_id'] === $warshipDictionary['id']) {
                            $enemyForce += $fleetDetail['qty'] * $warshipDictionary['attack'];
                            ++$pirateBayWarshipTypes;
                            break;
                        }
                    }
                }

                $damageToEachType = $fleetForce / $pirateBayWarshipTypes;

                [$enemyFleetDetails, $pirateBayWarshipTypes] = $this->shoot($damageToEachType, $enemyFleetDetails, $pirateBayWarshipTypes);

                $enemyDamageToEachType = $enemyForce / $fleetTypes;

                [$fleetDetails, $fleetTypes] = $this->shoot($enemyDamageToEachType, $fleetDetails, $fleetTypes);
            } while ($pirateBayWarshipTypes > 0 && $fleetTypes > 0);


            dd($fleetDetails, $enemyFleetDetails);
            // TODO calculate result of whole battle
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

    public function shoot($damage, $warships, $warshipTypes)
    {
        for ($i = 0, $iMax = count($warships); $i < $iMax; $i++) {
            $wholeHealth = $warships[$i]['qty'] * $warships[$i]['health'];

            $wholeHealth -= $damage;

            if ($wholeHealth < 0) {
                $wholeHealth = 0;
            }

            $warships[$i]['qty'] = ceil($wholeHealth / $warships[$i]['health']);

            if ($warships[$i]['qty'] < 1) {
                --$warshipTypes;
                array_splice($warships, $i, 1);
                $i--;
                $iMax--;
            }
        }

        return [$warships, $warshipTypes];
    }
}
