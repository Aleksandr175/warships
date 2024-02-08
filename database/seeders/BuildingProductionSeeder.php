<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\BuildingProduction;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class BuildingProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $buildingMine  = Building::where('id', config('constants.BUILDINGS.MINE'))->first();
        $buildingHouse = Building::where('id', config('constants.BUILDINGS.HOUSES'))->first();

        $mainProductionAllResources = [100, 200, 300, 500, 700, 1000, 1400, 1900, 2500, 3200];
        $productionPopulation       = [30, 50, 80, 120, 160, 200, 250, 300, 360, 440];

        $lvl = 1;
        foreach ($mainProductionAllResources as $gold) {
            BuildingProduction::create([
                'building_id' => $buildingMine->id,
                'lvl'         => $lvl,
                'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
                'qty'         => $gold
            ]);

            BuildingProduction::create([
                'building_id' => $buildingMine->id,
                'lvl'         => $lvl,
                'resource_id' => config('constants.RESOURCE_IDS.LOG'),
                'qty'         => $gold
            ]);

            BuildingProduction::create([
                'building_id' => $buildingMine->id,
                'lvl'         => $lvl,
                'resource_id' => config('constants.RESOURCE_IDS.ORE'),
                'qty'         => $gold
            ]);

            $lvl++;
        }

        $lvl = 1;
        foreach ($productionPopulation as $population) {
            BuildingProduction::create([
                'building_id' => $buildingHouse->id,
                'lvl'         => $lvl,
                'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
                'qty'         => $population
            ]);

            $lvl++;
        }
    }
}
