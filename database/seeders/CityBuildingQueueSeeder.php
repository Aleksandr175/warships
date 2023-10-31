<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\User;
use Illuminate\Database\Seeder;

class CityBuildingQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user         = User::where('id', config('constants.DEFAULT_USER_ID'))->first();
        $buildingMine = Building::where('id', config('constants.BUILDINGS.MINE'))->first();
        $city         = $user->cities()->first();

        $nextLvl = 1;

        /*$cityBuilding = $city->buildings()->where('id', $buildingMine->id)->first();

        if ($cityBuilding && $cityBuilding->id) {
            $nextLvl = $cityBuilding->lvl + 1;
        }

        $buildingResources = BuildingResource::where('building_id', $buildingMine->id)->where('lvl', $nextLvl)->first();

        if ($buildingResources && $buildingResources->id) {
            $time = $buildingResources->time;

            CityBuildingQueue::create([
                'city_id'     => $city->id,
                'building_id' => $buildingMine->id,
                'gold'        => $buildingResources->gold,
                'population'  => $buildingResources->population,
                'lvl'         => $nextLvl,
                'time'        => $time,
                'deadline'    => Carbon::now()->addSeconds($time)
            ]);
        }*/
    }
}
