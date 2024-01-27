<?php

namespace Database\Seeders;

use App\Models\ResearchQueue;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ResearchQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = 100;

        /*ResearchQueue::create([
            'city_id'       => config('constants.DEFAULT_USER_CITY_ID'),
            'user_id'       => config('constants.DEFAULT_USER_ID'),
            'research_id'   => config('constants.RESEARCHES.SHIP_GUNS'),
            'lvl'           => 2,
            'time_required' => $time,
            'deadline'      => Carbon::now()->addSeconds($time)
        ]);*/
    }
}
