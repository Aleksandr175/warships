<?php

namespace Database\Seeders;

use App\Models\WarshipDependency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarshipDependencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WarshipDependency::create([
            'warship_id' => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => 6,
            'required_entity_lvl' => 1
        ]);

        WarshipDependency::create([
            'warship_id' => 2,

            'required_entity'     => 'building',
            'required_entity_id'  => 6,
            'required_entity_lvl' => 2
        ]);

        WarshipDependency::create([
            'warship_id' => 2,

            'required_entity'     => 'research',
            'required_entity_id'  => 4,
            'required_entity_lvl' => 3
        ]);

        WarshipDependency::create([
            'warship_id' => 3,

            'required_entity'     => 'building',
            'required_entity_id'  => 6,
            'required_entity_lvl' => 4
        ]);

        WarshipDependency::create([
            'warship_id' => 3,

            'required_entity'     => 'research',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 2
        ]);

        WarshipDependency::create([
            'warship_id' => 4,

            'required_entity'     => 'building',
            'required_entity_id'  => 6,
            'required_entity_lvl' => 7
        ]);

        WarshipDependency::create([
            'warship_id' => 4,

            'required_entity'     => 'research',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 3
        ]);

        WarshipDependency::create([
            'warship_id' => 4,

            'required_entity'     => 'research',
            'required_entity_id'  => 3,
            'required_entity_lvl' => 3
        ]);

        WarshipDependency::create([
            'warship_id' => 5,

            'required_entity'     => 'building',
            'required_entity_id'  => 6,
            'required_entity_lvl' => 10
        ]);

        WarshipDependency::create([
            'warship_id' => 5,

            'required_entity'     => 'research',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 5
        ]);

        WarshipDependency::create([
            'warship_id' => 5,

            'required_entity'     => 'research',
            'required_entity_id'  => 3,
            'required_entity_lvl' => 5
        ]);

        WarshipDependency::create([
            'warship_id' => 5,

            'required_entity'     => 'research',
            'required_entity_id'  => 4,
            'required_entity_lvl' => 3
        ]);
    }
}
