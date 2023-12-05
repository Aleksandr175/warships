<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Fleet;
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

        Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => config('constants.DEFAULT_USER_2_CITY_ID'),
            'speed'          => 100,
            'gold'           => 1000,
            'population'     => 0,
            'repeating'      => 1,
            'fleet_task_id'  => $taskTrade->id,
            'status_id'      => $statusTrade1->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ]);

        Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => config('constants.DEFAULT_USER_CITY_ID_2'),
            'speed'          => 70,
            'gold'           => 567,
            'population'     => 0,
            'repeating'      => 0,
            'fleet_task_id'  => $taskMove->id,
            'status_id'      => $statusMove1->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ]);

        Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => config('constants.DEFAULT_USER_CITY_ID_2'),
            'speed'          => 70,
            'gold'           => 300,
            'population'     => 0,
            'repeating'      => 0,
            'fleet_task_id'  => $taskTransport->id,
            'status_id'      => $statusTransport1->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ]);

        // attack
        Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => config('constants.DEFAULT_PIRATE_CITY_ID'),
            'speed'          => 70,
            'gold'           => 50,
            'population'     => 50,
            'repeating'      => 0,
            'fleet_task_id'  => $taskAttack->id,
            'status_id'      => $statusAttack1->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ]);

        // expedition
        Fleet::create([
            'city_id'        => config('constants.DEFAULT_USER_CITY_ID'),
            'target_city_id' => null,
            'speed'          => 70,
            'gold'           => 0,
            'population'     => 0,
            'repeating'      => 1,
            'fleet_task_id'  => $taskExpedition->id,
            'status_id'      => $statusExpeditionGoingToTarget->id,
            'time'           => $time,
            'deadline'       => $carbon::now()->addSeconds($time)
        ]);
    }
}
