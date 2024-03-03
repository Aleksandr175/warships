<?php

namespace Database\Seeders;

use App\Models\BuildingQueueSlot;
use Illuminate\Database\Seeder;

class BuildingQueueSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BuildingQueueSlot::create([
            'building_id' => config('constants.BUILDINGS.SHIPYARD'),
            'building_lvl' => 1,
            'slots' => 1,
        ]);

        BuildingQueueSlot::create([
            'building_id' => config('constants.BUILDINGS.SHIPYARD'),
            'building_lvl' => 3,
            'slots' => 2,
        ]);

        BuildingQueueSlot::create([
            'building_id' => config('constants.BUILDINGS.SHIPYARD'),
            'building_lvl' => 5,
            'slots' => 3,
        ]);

        BuildingQueueSlot::create([
            'building_id' => config('constants.BUILDINGS.SHIPYARD'),
            'building_lvl' => 10,
            'slots' => 4,
        ]);
    }
}
