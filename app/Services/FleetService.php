<?php

namespace App\Services;

use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetTaskDictionary;
use App\Models\Warship;
use Carbon\Carbon;

class FleetService
{
    private $cityId              = null;
    private $coordX              = null;
    private $coordY              = null;
    private $fleetDetails        = [];
    private $updatedFleetDetails = [];
    private $recursive           = false;
    private $taskType            = null;
    private $targetCity          = null;
    private $taskTypeId          = null;

    // send fleet to target
    public function send($params, $user)
    {
        $this->cityId              = $params->cityId;
        $this->coordX              = $params->coordX;
        $this->coordY              = $params->coordY;
        $this->fleetDetails        = $params->fleetDetails;
        $this->recursive           = $params->recursive ? 1 : 0;
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

            // TODO calculate gold for sending fleet
            // ...

            // calculate time to target
            $distance = abs($userCity->coord_x - $this->coordX) + abs($userCity->coord_y - $this->coordY);
            // TODO: add speed param for time
            $timeToTarget = $distance * 5; // in seconds


            // create fleet and details
            $fleetId = Fleet::create([
                'city_id'        => $userCity->id,
                'target_city_id' => $this->targetCity->id,
                'fleet_task_id'  => $this->taskTypeId,
                'speed'          => 100,
                'time'           => $timeToTarget,
                'gold'           => 0,
                'recursive'      => $this->recursive,
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
        }
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
            $recursive         = null;
            $shouldDeleteFleet = false;

            // task: trade
            if ($fleet->isTradeFleet()) {
                if ($fleet->isTradeGoingToTarget()) {
                    dump('fleet trade');
                    $statusId = 2;
                    // how long?
                    $deadline = Carbon::create($fleet->deadline)->addSecond(10);

                    // check user of island (we can trade only with foreign islands)
                    $city       = City::find($fleet->city_id);
                    $targetCity = City::find($fleet->target_city_id);

                    if ($city->user_id === $targetCity->user_id) {
                        // send fleet back because we cant trade with ourselves
                        $statusId  = 3;
                        $deadline  = Carbon::create($fleet->deadline)->addSecond(10);
                        $gold      = 0;
                        $recursive = 0;
                    }
                }

                if ($fleet->isTrading()) {
                    dump('fleet trading...');
                    $statusId = 3;
                    // how long?
                    $deadline = Carbon::create($fleet->deadline)->addSecond(5);
                    // add gold to fleet? Formula?
                    $gold = 100;
                }

                if ($fleet->isTradeGoingBack()) {
                    dump('fleet returns');

                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);
                    $city->increment('gold', $fleet->gold);

                    if ($fleet->recursive) {
                        // just repeat task
                        $gold     = 0;
                        $statusId = 1;
                        // TODO: how long? // distance?
                        $deadline = Carbon::create($fleet->deadline)->addSecond(10);
                    } else {
                        // transfer fleet to warships in the island
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

                        $shouldDeleteFleet = true;
                    }
                }
            }

            // TODO: task: move fleet to other island
            if ($fleet->isMovingFleet()) {
                if ($fleet->isMovingFleetGoingToTarget()) {
                    // check user of island (we can move fleet between not ours islands)
                    $city         = City::find($fleet->city_id);
                    $targetCity   = City::find($fleet->target_city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    if ($city->user_id === $targetCity->user_id) {
                        // transfer fleet to warships in the island
                        foreach ($fleetDetails as $fleetDetail) {
                            $warship = $targetCity->warship($fleetDetail->warship_id);

                            if (!$warship) {
                                Warship::create([
                                    'warship_id' => $fleetDetail->warship_id,
                                    'qty'        => $fleetDetail->qty,
                                    'city_id'    => $targetCity->id,
                                    'user_id'    => $targetCity->user_id
                                ]);
                            } else {
                                $targetCity->warship($fleetDetail->warship_id)->increment('qty', $fleetDetail->qty);
                            }
                            $fleetDetail->delete();
                        }

                        $shouldDeleteFleet = true;
                    } else {
                        // return fleet back
                        $statusId = 3;
                        // TODO: calculate distance and secs
                        $deadline  = Carbon::create($fleet->deadline)->addSecond(10);
                        $gold      = 0;
                        $recursive = 0;
                    }

                }
            }

            if ($deadline && $statusId) {
                // update fleet
                $fleet->update([
                    'status_id' => $statusId,
                    'gold'      => $gold !== null ? $gold : $fleet->gold,
                    'deadline'  => $deadline,
                    'recursive' => $recursive !== null ? $recursive : $fleet->recursive
                ]);
            }

            if ($shouldDeleteFleet) {
                $fleet->delete();
            }

            // task: attack?

            // task: transport
        }

    }
}
