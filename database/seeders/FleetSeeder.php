<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Fleet;
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
        $myCity    = City::find(10);
        $taskTrade = FleetTaskDictionary::find(1);
        $taskMove = FleetTaskDictionary::find(2);
        $time      = 10;

        Fleet::create([
            'city_id'         => $myCity->id,
            'target_city_id'  => 12,
            'speed'           => 100,
            'gold'            => 1000,
            'recursive'       => 1,
            'fleet_task_id'   => $taskTrade->id,
            'time'            => $time,
            'deadline'        => Carbon::now()->addSeconds($time)
        ]);

        Fleet::create([
            'city_id'         => $myCity->id,
            'target_city_id'  => 11,
            'speed'           => 70,
            'gold'            => 567,
            'recursive'       => 0,
            'fleet_task_id'   => $taskMove->id,
            'time'            => $time,
            'deadline'        => Carbon::now()->addSeconds($time)
        ]);
    }
}
