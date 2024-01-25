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

        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 5,
        ]);
        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 20,
            'lvl'           => 1,
            'time_required' => 5,
        ]);

        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 200,
            'lvl'           => 2,
            'time_required' => 50,
        ]);
        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 30,
            'lvl'           => 2,
            'time_required' => 50,
        ]);

        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 400,
            'lvl'           => 3,
            'time_required' => 100,
        ]);
        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 50,
            'lvl'           => 3,
            'time_required' => 100,
        ]);

        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 1500,
            'lvl'           => 4,
            'time_required' => 250,
        ]);
        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 250,
            'lvl'           => 4,
            'time_required' => 250,
        ]);

        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 10000,
            'lvl'           => 5,
            'time_required' => 500,
        ]);
        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 2000,
            'lvl'           => 5,
            'time_required' => 500,
        ]);

        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 15000,
            'lvl'           => 6,
            'time_required' => 800,
        ]);
        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 5000,
            'lvl'           => 6,
            'time_required' => 800,
        ]);

        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 50000,
            'lvl'           => 7,
            'time_required' => 1200,
        ]);
        BuildingResource::create([
            'building_id'   => $building1->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 20000,
            'lvl'           => 7,
            'time_required' => 1200,
        ]);


        BuildingResource::create([
            'building_id'   => $building2->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 5,
        ]);
        BuildingResource::create([
            'building_id'   => $building2->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 20,
            'lvl'           => 1,
            'time_required' => 5,
        ]);

        BuildingResource::create([
            'building_id'   => $building2->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 200,
            'lvl'           => 2,
            'time_required' => 50,
        ]);
        BuildingResource::create([
            'building_id'   => $building2->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 30,
            'lvl'           => 2,
            'time_required' => 50,
        ]);

        BuildingResource::create([
            'building_id'   => $building2->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 400,
            'lvl'           => 3,
            'time_required' => 100,
        ]);
        BuildingResource::create([
            'building_id'   => $building2->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 50,
            'lvl'           => 3,
            'time_required' => 100,
        ]);


        BuildingResource::create([
            'building_id'   => $building3->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 5,
        ]);
        BuildingResource::create([
            'building_id'   => $building3->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 20,
            'lvl'           => 1,
            'time_required' => 5,
        ]);

        BuildingResource::create([
            'building_id'   => $building3->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 200,
            'lvl'           => 2,
            'time_required' => 50,
        ]);
        BuildingResource::create([
            'building_id'   => $building3->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 30,
            'lvl'           => 2,
            'time_required' => 50,
        ]);

        BuildingResource::create([
            'building_id'   => $building3->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 400,
            'lvl'           => 3,
            'time_required' => 100,
        ]);
        BuildingResource::create([
            'building_id'   => $building3->id,
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 50,
            'lvl'           => 3,
            'time_required' => 100,
        ]);


        BuildingResource::create([
            'building_id'   => $building4->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 10,
        ]);


        BuildingResource::create([
            'building_id'   => $building5->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 10,
        ]);

        BuildingResource::create([
            'building_id'   => $building6->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 1000,
            'lvl'           => 1,
            'time_required' => 100,
        ]);

        BuildingResource::create([
            'building_id'   => $building7->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 2000,
            'lvl'           => 1,
            'time_required' => 200,
        ]);

        BuildingResource::create([
            'building_id'   => $building8->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 3000,
            'lvl'           => 1,
            'time_required' => 300,
        ]);

    }
}
