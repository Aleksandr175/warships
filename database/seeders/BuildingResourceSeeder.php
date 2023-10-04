<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\BuildingDictionary;
use App\Models\BuildingResource;
use Illuminate\Database\Seeder;

class BuildingResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $building1 = BuildingDictionary::where('id', config('constants.BUILDINGS.MAIN'))->first();
        $building2 = BuildingDictionary::where('id', config('constants.BUILDINGS.MINE'))->first();
        $building3 = BuildingDictionary::where('id', config('constants.BUILDINGS.HOUSES'))->first();
        $building4 = BuildingDictionary::where('id', config('constants.BUILDINGS.TAVERN'))->first();
        $building5 = BuildingDictionary::where('id', config('constants.BUILDINGS.FARM'))->first();
        $building6 = BuildingDictionary::where('id', config('constants.BUILDINGS.SHIPYARD'))->first();
        $building7 = BuildingDictionary::where('id', config('constants.BUILDINGS.DOCK'))->first();
        $building8 = BuildingDictionary::where('id', config('constants.BUILDINGS.FORTRESS'))->first();

        $buildingResources1[0] = BuildingResource::create([
            'building_id' => $building1->id,
            'gold' => 100,
            'population' => 20,
            'lvl' => 1,
            'time' => 10,
        ]);

        $buildingResources1[1] = BuildingResource::create([
            'building_id' => $building1->id,
            'gold' => 200,
            'population' => 30,
            'lvl' => 2,
            'time' => 20,
        ]);

        $buildingResources1[2] = BuildingResource::create([
            'building_id' => $building1->id,
            'gold' => 500,
            'population' => 50,
            'lvl' => 3,
            'time' => 50,
        ]);

        $buildingResources2[0] = BuildingResource::create([
            'building_id' => $building2->id,
            'gold' => 200,
            'population' => 50,
            'lvl' => 1,
            'time' => 25,
        ]);
        $buildingResources2[1] = BuildingResource::create([
            'building_id' => $building2->id,
            'gold' => 300,
            'population' => 70,
            'lvl' => 2,
            'time' => 30,
        ]);
        $buildingResources2[2] = BuildingResource::create([
            'building_id' => $building2->id,
            'gold' => 500,
            'population' => 100,
            'lvl' => 3,
            'time' => 50,
        ]);

        $buildingResources3[0] = BuildingResource::create([
            'building_id' => $building3->id,
            'gold' => 100,
            'population' => 0,
            'lvl' => 1,
            'time' => 10,
        ]);
        $buildingResources3[1] = BuildingResource::create([
            'building_id' => $building3->id,
            'gold' => 200,
            'population' => 0,
            'lvl' => 2,
            'time' => 20,
        ]);
        $buildingResources3[2] = BuildingResource::create([
            'building_id' => $building3->id,
            'gold' => 400,
            'population' => 0,
            'lvl' => 3,
            'time' => 40
        ]);

        BuildingResource::create([
            'building_id' => $building4->id,
            'gold' => 100,
            'population' => 0,
            'lvl' => 1,
            'time' => 10
        ]);

        BuildingResource::create([
            'building_id' => $building5->id,
            'gold' => 100,
            'population' => 0,
            'lvl' => 1,
            'time' => 10
        ]);

        BuildingResource::create([
            'building_id' => $building6->id,
            'gold' => 100,
            'population' => 0,
            'lvl' => 1,
            'time' => 10
        ]);

        BuildingResource::create([
            'building_id' => $building7->id,
            'gold' => 100,
            'population' => 0,
            'lvl' => 1,
            'time' => 10
        ]);

        BuildingResource::create([
            'building_id' => $building8->id,
            'gold' => 1000,
            'population' => 100,
            'lvl' => 1,
            'time' => 400
        ]);

        BuildingResource::create([
            'building_id' => $building8->id,
            'gold' => 1500,
            'population' => 150,
            'lvl' => 2,
            'time' => 600
        ]);
    }
}
