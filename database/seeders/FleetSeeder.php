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

        $statusTrade1                  = FleetStatusDictionary::find(1);
        $statusMove1                   = FleetStatusDictionary::find(1);
        $statusTransport1              = FleetStatusDictionary::find(1);
        $statusAttack1                 = FleetStatusDictionary::find(1);
        $statusExpeditionGoingToTarget = FleetStatusDictionary::find(config('constants.FLEET_STATUSES.EXPEDITION_GOING_TO_TARGET'));
        $time                          = 10;

        Fleet::create([
            'city_id'        => $myCity->id,
            'target_city_id' => 12,
            'speed'          => 100,
            'gold'           => 1000,
            'population'     => 0,
            'repeating'      => 1,
            'fleet_task_id'  => $taskTrade->id,
            'status_id'      => $statusTrade1->id,
            'time'           => $time,
            'deadline'       => Carbon::now()->addSeconds($time)
        ]);

        Fleet::create([
            'city_id'        => $myCity->id,
            'target_city_id' => 11,
            'speed'          => 70,
            'gold'           => 567,
            'population'     => 0,
            'repeating'      => 0,
            'fleet_task_id'  => $taskMove->id,
            'status_id'      => $statusMove1->id,
            'time'           => $time,
            'deadline'       => Carbon::now()->addSeconds($time)
        ]);

        Fleet::create([
            'city_id'        => $myCity->id,
            'target_city_id' => 11,
            'speed'          => 70,
            'gold'           => 300,
            'population'     => 0,
            'repeating'      => 0,
            'fleet_task_id'  => $taskTransport->id,
            'status_id'      => $statusTransport1->id,
            'time'           => $time,
            'deadline'       => Carbon::now()->addSeconds($time)
        ]);

        // attack
        Fleet::create([
            'city_id'        => $myCity->id,
            'target_city_id' => 212,
            'speed'          => 70,
            'gold'           => 50,
            'population'     => 50,
            'repeating'      => 0,
            'fleet_task_id'  => $taskAttack->id,
            'status_id'      => $statusAttack1->id,
            'time'           => $time,
            'deadline'       => Carbon::now()->addSeconds($time)
        ]);

        // expedition
        Fleet::create([
            'city_id'        => $myCity->id,
            'target_city_id' => null,
            'speed'          => 70,
            'gold'           => 0,
            'population'     => 0,
            'repeating'      => 1,
            'fleet_task_id'  => $taskExpedition->id,
            'status_id'      => $statusExpeditionGoingToTarget->id,
            'time'           => $time,
            'deadline'       => Carbon::now()->addSeconds($time)
        ]);
    }
}
