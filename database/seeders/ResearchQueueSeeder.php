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

        ResearchQueue::create([
            'city_id' => 10,
            'user_id' => 5,
            'research_id' => 3,
            'gold' => 100,
            'population' => 5,
            'lvl' => 2,
            'time' => $time,
            'deadline' => Carbon::now()->addSeconds($time)
        ]);
    }
}
