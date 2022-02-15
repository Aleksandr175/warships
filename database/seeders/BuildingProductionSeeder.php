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
        $buildingMiner = Building::where('id', 2)->first();
        $buildingHouse = Building::where('id', 3)->first();

        BuildingProduction::create([
            'building_id' => $buildingMiner->id,
            'lvl' => 1,
            'resource' => 'gold',
            'qty' => 100
        ]);

        BuildingProduction::create([
            'building_id' => $buildingMiner->id,
            'lvl' => 2,
            'resource' => 'gold',
            'qty' => 200
        ]);

        BuildingProduction::create([
            'building_id' => $buildingMiner->id,
            'lvl' => 3,
            'resource' => 'gold',
            'qty' => 300
        ]);

        BuildingProduction::create([
            'building_id' => $buildingHouse->id,
            'lvl' => 1,
            'resource' => 'population',
            'qty' => 100
        ]);

        BuildingProduction::create([
            'building_id' => $buildingHouse->id,
            'lvl' => 2,
            'resource' => 'population',
            'qty' => 120
        ]);

        BuildingProduction::create([
            'building_id' => $buildingHouse->id,
            'lvl' => 3,
            'resource' => 'population',
            'qty' => 250
        ]);

    }
}
