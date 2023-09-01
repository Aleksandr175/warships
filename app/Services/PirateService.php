<?php

namespace App\Services;

use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use Carbon\Carbon;

class PirateService
{
    // send fleet to target
    public function handle(City $city)
    {
        dump('pirateservice handle');

        if (!count($city->fleets)) {
            dump('no pirate fleet -> sending new one');

            $timeToTarget = 15;
            $gold         = 0;
            $speed        = 100;

            // create fleet and details
            $fleetId = Fleet::create([
                'city_id'        => $city->id,
                'target_city_id' => 10,//$this->targetCity->id,
                'fleet_task_id'  => 3, //attack
                'speed'          => $speed,
                'time'           => $timeToTarget,
                'gold'           => $gold,
                'repeating'      => 0,
                'status_id'      => 1, // TODO: set default value for fleet status id
                'deadline'       => Carbon::now()->addSeconds($timeToTarget)
            ])->id;

            FleetDetail::create([
                'fleet_id'   => $fleetId,
                'warship_id' => 1,
                'qty'        => 3
            ]);

            FleetDetail::create([
                'fleet_id'   => $fleetId,
                'warship_id' => 3,
                'qty'        => 1
            ]);
        } else {
            dump('try to build new pirate warship');
        }
    }

    // TODO
    public function buildFleet()
    {
        return 0;
    }
}
