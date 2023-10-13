<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Building::create([
            'city_id' => config('constants.DEFAULT_USER_CITY_ID'),
            'building_id' => 1,
            'lvl' => 3
        ]);

        Building::create([
            'city_id' => config('constants.DEFAULT_USER_CITY_ID'),
            'building_id' => 2,
            'lvl' => 1
        ]);

        Building::create([
            'city_id' => config('constants.DEFAULT_USER_CITY_ID'),
            'building_id' => 3,
            'lvl' => 2
        ]);

        Building::create([
            'city_id' => config('constants.DEFAULT_USER_CITY_ID'),
            'building_id' => 1,
            'lvl' => 1
        ]);
    }
}
