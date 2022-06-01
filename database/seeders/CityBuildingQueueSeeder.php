<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\BuildingResource;
use App\Models\CityBuildingQueue;
use App\Models\User;
use Carbon\Carbon;
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
        $user = User::where('id', 5)->first();
        $building2 = Building::where('id', 2)->first();
        $city = $user->cities()->first();

        $nextLvl = 1;

        $cityBuilding = $city->buildings()->where('id', $building2->id)->first();

        if ($cityBuilding && $cityBuilding->id) {
            $nextLvl = $cityBuilding->lvl + 1;
        }

        $buildingResources = BuildingResource::where('building_id', $building2->id)->where('lvl', $nextLvl)->first();

        if ($buildingResources && $buildingResources->id) {
            $time = $buildingResources->time;

            CityBuildingQueue::create([
                'city_id' => $city->id,
                'building_id' => $building2->id,
                'gold' => $buildingResources->gold,
                'population' => $buildingResources->population,
                'lvl' => $nextLvl,
                'time' => $time,
                'deadline' => Carbon::now()->addSeconds($time)
            ]);
        }
    }
}
