<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetResource;
use App\Models\FleetStatusDictionary;
use App\Models\FleetTaskDictionary;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myCity         = City::find(config('constants.DEFAULT_USER_CITY_ID'));

        $taskTrade      = FleetTaskDictionary::find(config('constants.FLEET_TASKS.TRADE'));
        $taskMove       = FleetTaskDictionary::find(config('constants.FLEET_TASKS.MOVE'));
        $taskAttack     = FleetTaskDictionary::find(config('constants.FLEET_TASKS.ATTACK'));
        $taskTransport  = FleetTaskDictionary::find(config('constants.FLEET_TASKS.TRANSPORT'));
        $taskExpedition = FleetTaskDictionary::find(config('constants.FLEET_TASKS.EXPEDITION'));

        $statusTrade1                  = FleetStatusDictionary::find(config('constants.FLEET_STATUSES.TRADE_GOING_TO_TARGET'));
        $statusMove1                   = FleetStatusDictionary::find(config('constants.FLEET_STATUSES.MOVING_GOING_TO_TARGET'));
        $statusTransport1              = FleetStatusDictionary::find(config('constants.FLEET_STATUSES.TRANSPORT_GOING_TO_TARGET'));
        $statusAttack1                 = FleetStatusDictionary::find(config('constants.FLEET_STATUSES.ATTACK_GOING_TO_TARGET'));
        $statusExpeditionGoingToTarget = FleetStatusDictionary::find(config('constants.FLEET_STATUSES.EXPEDITION_GOING_TO_TARGET'));
        $time                          = 10;

        $carbon = new Carbon();

        $fleetId = Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => config('constants.DEFAULT_USER_2_CITY_ID'),
            'speed'          => 100,
            'repeating'      => 1,
            'fleet_task_id'  => $taskTrade->id,
            'status_id'      => $statusTrade1->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ])->id;

        FleetResource::create([
            'fleet_id' => $fleetId,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty' => 10
        ]);

        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'qty'        => 3,
        ]);

        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
            'qty'        => 2,
        ]);

        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'qty'        => 10,
        ]);

        $fleetId = Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => config('constants.DEFAULT_USER_CITY_ID_2'),
            'speed'          => 70,
            'repeating'      => 0,
            'fleet_task_id'  => $taskMove->id,
            'status_id'      => $statusMove1->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ])->id;

        FleetResource::create([
            'fleet_id' => $fleetId,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty' => 567
        ]);

        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'qty'        => 5,
        ]);

        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'qty'        => 3,
        ]);

        // try to move warships to not our island -> it should be returned to original island
        $fleetId = Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => config('constants.DEFAULT_USER_2_CITY_ID'),
            'speed'          => 70,
            'repeating'      => 0,
            'fleet_task_id'  => $taskMove->id,
            'status_id'      => $statusMove1->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ])->id;

        FleetResource::create([
            'fleet_id' => $fleetId,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty' => 123
        ]);

        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'qty'        => 5,
        ]);

        $fleetId = Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => config('constants.DEFAULT_USER_CITY_ID_2'),
            'speed'          => 70,
            'repeating'      => 0,
            'fleet_task_id'  => $taskTransport->id,
            'status_id'      => $statusTransport1->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ])->id;

        FleetResource::create([
            'fleet_id' => $fleetId,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty' => 300
        ]);
        FleetResource::create([
            'fleet_id' => $fleetId,
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty' => 10
        ]);

        // attack
        $fleetId = Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => config('constants.DEFAULT_PIRATE_CITY_ID'),
            'speed'          => 70,
            'repeating'      => 0,
            'fleet_task_id'  => $taskAttack->id,
            'status_id'      => $statusAttack1->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ])->id;

        // just for test, we can't send resources if we send fleet to attack
        FleetResource::create([
            'fleet_id' => $fleetId,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty' => 500
        ]);
        FleetResource::create([
            'fleet_id' => $fleetId,
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty' => 600
        ]);

        // fleet details for attack
        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'qty'        => 10,
        ]);
        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
            'qty'        => 5,
        ]);
        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'qty'        => 2,
        ]);

        FleetResource::create([
            'fleet_id' => $fleetId,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty' => 50
        ]);
        FleetResource::create([
            'fleet_id' => $fleetId,
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty' => 50
        ]);

        // expedition
        $fleetId = Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => null,
            'speed'          => 70,
            'repeating'      => 1,
            'fleet_task_id'  => $taskExpedition->id,
            'status_id'      => $statusExpeditionGoingToTarget->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ])->id;

        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'qty'        => 10,
        ]);

        // expedition
        $fleetId = Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => null,
            'speed'          => 70,
            'repeating'      => 0,
            'fleet_task_id'  => $taskExpedition->id,
            'status_id'      => $statusExpeditionGoingToTarget->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ])->id;

        FleetDetail::create([
            'fleet_id'   => $fleetId,
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'qty'        => 10,
        ]);
    }
}
