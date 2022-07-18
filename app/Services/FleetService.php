<?php

namespace App\Services;

use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetTaskDictionary;
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
                'city_id' => $userCity->id,
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
                    'fleet_id' => $fleetId,
                    'warship_id' => $fleetDetail['warshipId'],
                    'qty' => $fleetDetail['qty']
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
}
