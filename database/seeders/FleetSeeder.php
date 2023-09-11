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
        $myCity        = City::find(10);
        $taskTrade     = FleetTaskDictionary::find(1);
        $taskMove      = FleetTaskDictionary::find(2);
        $taskAttack    = FleetTaskDictionary::find(3);
        $taskTransport = FleetTaskDictionary::find(4);

        $statusTrade1     = FleetStatusDictionary::find(1);
        $statusMove1      = FleetStatusDictionary::find(1);
        $statusTransport1 = FleetStatusDictionary::find(1);
        $statusAttack1    = FleetStatusDictionary::find(1);
        $time             = 10;

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
    }
}
