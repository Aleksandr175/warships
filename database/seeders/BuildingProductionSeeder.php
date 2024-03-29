<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\BuildingProduction;
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

        $productionGold       = [100, 200, 300, 500, 700, 1000, 1400, 1900, 2500, 3200];
        $productionPopulation = [30, 50, 80, 120, 160, 200, 250, 300, 360, 440];

        $lvl = 1;
        foreach ($productionGold as $gold) {
            BuildingProduction::create([
                'building_id' => $buildingMine->id,
                'lvl'         => $lvl,
                'resource'    => 'gold',
                'qty'         => $gold
            ]);

            $lvl++;
        }

        $lvl = 1;
        foreach ($productionPopulation as $population) {
            BuildingProduction::create([
                'building_id' => $buildingHouse->id,
                'lvl'         => $lvl,
                'resource'    => 'population',
                'qty'         => $population
            ]);

            $lvl++;
        }
    }
}
