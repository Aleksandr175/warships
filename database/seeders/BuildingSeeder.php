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
            'city_id'     => config('constants.DEFAULT_USER_CITY_ID'),
            'building_id' => config('constants.BUILDINGS.MAIN'),
            'lvl'         => 3
        ]);

        Building::create([
            'city_id'     => config('constants.DEFAULT_USER_CITY_ID'),
            'building_id' => config('constants.BUILDINGS.MINE'),
            'lvl'         => 1
        ]);

        Building::create([
            'city_id'     => config('constants.DEFAULT_USER_CITY_ID'),
            'building_id' => config('constants.BUILDINGS.HOUSES'),
            'lvl'         => 2
        ]);

        Building::create([
            'city_id'     => config('constants.DEFAULT_USER_CITY_ID'),
            'building_id' => config('constants.BUILDINGS.SHIPYARD'),
            'lvl'         => 1
        ]);

        Building::create([
            'city_id'     => config('constants.DEFAULT_USER_CITY_ID'),
            'building_id' => config('constants.BUILDINGS.WORKSHOP'),
            'lvl'         => 2
        ]);

        Building::create([
            'city_id'     => config('constants.DEFAULT_USER_CITY_ID_2'),
            'building_id' => config('constants.BUILDINGS.MAIN'),
            'lvl'         => 25
        ]);

        Building::create([
            'city_id'     => config('constants.DEFAULT_USER_CITY_ID_2'),
            'building_id' => config('constants.BUILDINGS.MINE'),
            'lvl'         => 25
        ]);
    }
}
