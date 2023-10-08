<?php

namespace Database\Seeders;

use App\Models\BuildingDependency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuildingDependencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BuildingDependency::create([
            'building_id'  => config('constants.BUILDINGS.MAIN'),
            'building_lvl' => 4,

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.MINE'),
            'required_entity_lvl' => 2
        ]);

        BuildingDependency::create([
            'building_id'  => config('constants.BUILDINGS.MINE'),
            'building_lvl' => 2,

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.MAIN'),
            'required_entity_lvl' => 2
        ]);

        BuildingDependency::create([
            'building_id'  => config('constants.BUILDINGS.HOUSES'),
            'building_lvl' => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.MAIN'),
            'required_entity_lvl' => 1
        ]);

        BuildingDependency::create([
            'building_id'  => config('constants.BUILDINGS.HOUSES'),
            'building_lvl' => 2,

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.MAIN'),
            'required_entity_lvl' => 2
        ]);

        BuildingDependency::create([
            'building_id'  => config('constants.BUILDINGS.FORTRESS'),
            'building_lvl' => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.MAIN'),
            'required_entity_lvl' => 5
        ]);

        BuildingDependency::create([
            'building_id'  => config('constants.BUILDINGS.FORTRESS'),
            'building_lvl' => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.MINE'),
            'required_entity_lvl' => 3
        ]);

        BuildingDependency::create([
            'building_id'  => config('constants.BUILDINGS.FORTRESS'),
            'building_lvl' => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.HOUSES'),
            'required_entity_lvl' => 2
        ]);
    }
}
