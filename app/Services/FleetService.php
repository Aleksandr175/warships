<?php

namespace App\Services;

use App\Events\FleetUpdatedEvent;
use App\Events\TestEvent;
use App\Http\Resources\FleetDetailResource;
use App\Http\Resources\FleetResource;
use App\Http\Resources\WarshipResource;
use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetTaskDictionary;
use App\Models\User;
use App\Models\Warship;
use App\Models\WarshipDictionary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FleetService
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

    // send fleet to target
    public function send($params, $user)
    {
        $this->cityId              = $params->cityId;
        $this->coordX              = $params->coordX;
        $this->coordY              = $params->coordY;
        $this->gold                = $params->gold;
        $this->fleetDetails        = $params->fleetDetails;
        $this->repeating           = $params->repeating ? 1 : 0;
        $this->taskType            = $params->taskType;
        $this->updatedFleetDetails = [];

        // check target coords - that it exists
        $this->targetCity = $this->getCityByCoords($this->coordX, $this->coordY);

        if (!$this->isCity($this->targetCity)) {
            return 'there is no city';
        }

        if (!$this->hasTaskType($this->taskType)) {
            return 'no such task type';
        }

        $this->taskTypeId = FleetTaskDictionary::where('slug', $this->taskType)->first()->id;

        // check details
        if ($this->fleetDetails && count($this->fleetDetails)) {
            // get player's city
            $userCity = $user->city($this->cityId);

            if (!($userCity && $userCity->id)) {
                return 'it is not city of user';
            }

            // get warships in city
            $warships = $userCity->warships()->where('user_id', $user->id)->get();

            // check and correct fleet details
            foreach ($warships as $warship) {
                foreach ($this->fleetDetails as $fleetDetail) {
                    if ($fleetDetail['warshipId'] === $warship->id && $fleetDetail['qty'] > 0 && $warship->qty > 0) {
                        $detail = [
                            'qty'       => min($warship->qty, $fleetDetail['qty']),
                            'warshipId' => $warship->id
                        ];

                        array_push($this->updatedFleetDetails, $detail);
                    }
                }
            }

            if (!count($this->updatedFleetDetails)) {
                return 'no warships selected';
            }

            // calculate time to target
            $distance = abs($userCity->coord_x - $this->coordX) + abs($userCity->coord_y - $this->coordY);
            // TODO: add speed param for time
            $timeToTarget = $distance * 5; // in seconds

            if (($this->gold && !is_numeric($this->gold))) {
                return 'Wrong gold number';
            }

            $gold = $this->handleGold($this->gold, $userCity, $this->updatedFleetDetails);

            // update gold for island
            $userCity->increment('gold', -$gold);

            // create fleet and details
            $fleetId = Fleet::create([
                'city_id'        => $userCity->id,
                'target_city_id' => $this->targetCity->id,
                'fleet_task_id'  => $this->taskTypeId,
                'speed'          => 100,
                'time'           => $timeToTarget,
                'gold'           => $gold,
                'repeating'      => $this->repeating,
                'status_id'      => 1, // TODO: set default value for fleet status id
                'deadline'       => Carbon::now()->addSeconds($timeToTarget)
            ])->id;

            foreach ($this->updatedFleetDetails as $fleetDetail) {
                FleetDetail::create([
                    'fleet_id'   => $fleetId,
                    'warship_id' => $fleetDetail['warshipId'],
                    'qty'        => $fleetDetail['qty']
                ]);

                // remove warships from city
                $warships->where('warship_id', $fleetDetail['warshipId'])->first()->increment('qty', -$fleetDetail['qty']);
            }

            return [
                'success'  => true,
                'warships' => WarshipResource::collection($warships)
            ];
        } else {
            return 'No warships in fleet';
        }
    }

    // get maximum gold which we can carry
    // - gold should be not more than we have on island
    // - gold should be not more than fleet can carry
    public function handleGold($gold, $city, $fleetDetails): int {
        $actualGold = $gold;

        if ($city->gold < $actualGold) {
            $actualGold = $city->gold;
        }

        $warshipsDictionary = WarshipDictionary::get();

        $capacity = 0;

        foreach ($warshipsDictionary as $warshipDictionary) {
            foreach ($fleetDetails as $fleetDetail) {
                if ($fleetDetail['warshipId'] == $warshipDictionary['id']) {
                    $capacity += $fleetDetail['qty'] * $warshipDictionary['capacity'];
                    break;
                }
            }
        }

        if ($capacity < $actualGold) {
            $actualGold = $capacity;
        }

        return $actualGold;
    }

    public function isCity($city): bool
    {
        return $city && isset($city->id);
    }

    public function hasTaskType($taskType): bool
    {
        $t = FleetTaskDictionary::where('slug', $taskType)->first();

        return $t && isset($t->id);
    }

    public function getCityByCoords($coordX, $coordY)
    {
        return City::where('coord_x', $coordX)->where('coord_y', $coordY)->first();
    }

    public function handleFleet($fleet)
    {
        // only if deadline is expired
        if ($fleet->deadline < Carbon::now()) {
            $statusId          = null;
            $deadline          = null;
            $gold              = null;
            $repeating         = null;
            $shouldDeleteFleet = false;

            $city = City::find($fleet->city_id);

            // task: trade
            if ($fleet->isTradeFleet()) {
                if ($fleet->isTradeGoingToTarget()) {
                    dump('trade: fleet starts to trade');
                    $statusId = Fleet::FLEET_STATUS_TRADING_ID;
                    // how long?
                    $deadline = Carbon::create($fleet->deadline)->addSecond(10);

                    // check user of island (we can trade only with foreign islands)
                    $city       = City::find($fleet->city_id);
                    $targetCity = City::find($fleet->target_city_id);

                    if ($city->user_id === $targetCity->user_id) {
                        // send fleet back because we cant trade with ourselves
                        $statusId  = Fleet::FLEET_STATUS_TRADE_GOING_BACK_ID;
                        $deadline  = Carbon::create($fleet->deadline)->addSecond(10);
                        $repeating = 0;
                    }
                }

                if ($fleet->isTrading()) {
                    dump('trade: fleet completed trading');
                    $statusId = Fleet::FLEET_STATUS_TRADE_GOING_BACK_ID;;
                    // how long?
                    $deadline = Carbon::create($fleet->deadline)->addSecond(5);
                    // add gold to fleet? Formula?
                    $gold = 100;
                }

                if ($fleet->isTradeGoingBack()) {
                    dump('trade: fleet has returned to home');

                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);
                    $city->increment('gold', $fleet->gold);

                    if ($fleet->repeating) {
                        dump('trade: fleet repeats trade task, going to target');
                        // just repeat task
                        $gold     = 0;
                        $statusId = Fleet::FLEET_STATUS_TRADE_GOING_TO_TARGET_ID;
                        // TODO: how long? // distance?
                        $deadline = Carbon::create($fleet->deadline)->addSecond(10);
                    } else {
                        // transfer fleet to warships in the island
                        $this->convertFleetDetailsToWarships($fleetDetails, $city);

                        $shouldDeleteFleet = true;
                    }
                }
            }

            // task: move fleet to another island
            if ($fleet->isMovingFleet()) {
                if ($fleet->isMovingFleetGoingToTarget()) {
                    // check user of island (we can move fleet between not ours islands)
                    $city         = City::find($fleet->city_id);
                    $targetCity   = City::find($fleet->target_city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    if ($city->user_id === $targetCity->user_id) {
                        dump('move: fleet moved to another island');
                        // transfer fleet to warships in the island
                        $this->convertFleetDetailsToWarships($fleetDetails, $targetCity);

                        $shouldDeleteFleet = true;
                    } else {
                        dump('move: fleet is returning to original island');
                        // return fleet back
                        $statusId = Fleet::FLEET_STATUS_MOVING_GOING_BACK_ID;
                        // TODO: calculate distance and secs
                        $deadline  = Carbon::create($fleet->deadline)->addSecond(10);
                        $repeating = 0;
                    }

                }

                if ($fleet->isMovingFleetGoingBack()) {
                    dump('move: fleet has returned');
                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    // transfer fleet to warships in the island
                    $this->convertFleetDetailsToWarships($fleetDetails, $city);

                    $shouldDeleteFleet = true;
                }
            }

            // TODO: task: transport
            if ($fleet->isTrasnsportFleet()) {
                if ($fleet->isTransportFleetGoingToTarget()) {
                    dump('transport: fleet delivered resource, fleet is going back');
                    $statusId = Fleet::FLEET_STATUS_TRANSPORT_GOING_BACK_ID;
                    // TODO: how long? // distance?
                    $deadline = Carbon::create($fleet->deadline)->addSecond(10);

                    $gold = 0;

                    $targetCity = City::find($fleet->target_city_id);
                    $targetCity->increment('gold', $fleet->gold);
                }

                if ($fleet->isTransportFleetGoingBack()) {
                    dump('transport: fleet has returned to original island');

                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    $this->convertFleetDetailsToWarships($fleetDetails, $city);

                    $shouldDeleteFleet = true;
                }
            }


            // ----------------------------------
            // TODO: task: attack? // DO it later
            // ----------------------------------

            if ($deadline && $statusId) {
                // update fleet
                $fleet->update([
                    'status_id' => $statusId,
                    'gold'      => $gold !== null ? $gold : $fleet->gold,
                    'deadline'  => $deadline,
                    'repeating' => $repeating !== null ? $repeating : $fleet->repeating
                ]);
            }

            if ($shouldDeleteFleet) {
                $fleet->delete();
            }

            if ($statusId || $deadline || $shouldDeleteFleet) {
                $fleets        = $city->fleets;
                $fleetsDetails = FleetDetail::getFleetDetails($fleets->pluck('id'));

                $cityIds = $fleets->pluck('city_id')->toArray();;
                $targetCityIds = $fleets->pluck('target_city_id')->toArray();;

                $cities = City::whereIn('id', array_merge($cityIds, $targetCityIds))->get();

                dump('Dispatch new fleet event');
                FleetUpdatedEvent::dispatch($fleets, $fleetsDetails, $cities);
            }
        }

    }

    public function convertFleetDetailsToWarships($fleetDetails, $city): void
    {
        foreach ($fleetDetails as $fleetDetail) {
            $warship = $city->warship($fleetDetail->warship_id);

            if (!$warship) {
                Warship::create([
                    'warship_id' => $fleetDetail->warship_id,
                    'qty'        => $fleetDetail->qty,
                    'city_id'    => $city->id,
                    'user_id'    => $city->user_id
                ]);
            } else {
                $city->warship($fleetDetail->warship_id)->increment('qty', $fleetDetail->qty);
            }

            $fleetDetail->delete();
        }
    }
}
