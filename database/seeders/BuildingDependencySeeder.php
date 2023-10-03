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
            'building_id'  => 1,
            'building_lvl' => 4,

            'required_entity'     => 'building',
            'required_entity_id'  => 2,
            'required_entity_lvl' => 2
        ]);

        BuildingDependency::create([
            'building_id'           => 2,
            'building_lvl'          => 2,

            'required_entity'     => 'building',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 2
        ]);

        BuildingDependency::create([
            'building_id'           => 3,
            'building_lvl'          => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 1
        ]);

        BuildingDependency::create([
            'building_id'           => 3,
            'building_lvl'          => 2,

            'required_entity'     => 'building',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 2
        ]);

        BuildingDependency::create([
            'building_id'           => 8,
            'building_lvl'          => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 5
        ]);

        BuildingDependency::create([
            'building_id'           => 8,
            'building_lvl'          => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => 2,
            'required_entity_lvl' => 3
        ]);

        BuildingDependency::create([
            'building_id'           => 8,
            'building_lvl'          => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => 3,
            'required_entity_lvl' => 2
        ]);
    }
}
