<?php

namespace Database\Seeders;

use App\Models\RefiningQueue;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RefiningQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carbon = new Carbon();

        RefiningQueue::create([
            'city_id'            => config('constants.DEFAULT_USER_CITY_ID'),
            'input_resource_id'  => config('constants.RESOURCE_IDS.LOG'),
            'input_qty'          => 15,
            'output_resource_id' => config('constants.RESOURCE_IDS.PLANK'),
            'output_qty'         => 3,
            'time'               => 30,
            'deadline'           => $carbon::now()->addSeconds(30)
        ]);
    }
}
