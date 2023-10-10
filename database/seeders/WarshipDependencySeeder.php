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
            'warship_id' => config('constants.WARSHIPS.LUGGER'),

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.WARSHIPS.LUGGER'),
            'required_entity_lvl' => 1
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.CARAVEL'),

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.SHIPYARD'),
            'required_entity_lvl' => 2
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.CARAVEL'),

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_HOLD'),
            'required_entity_lvl' => 3
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.GALERA'),

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.SHIPYARD'),
            'required_entity_lvl' => 4
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.GALERA'),

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_SAILS'),
            'required_entity_lvl' => 2
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.FRIGATE'),

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.SHIPYARD'),
            'required_entity_lvl' => 7
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.FRIGATE'),

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_SAILS'),
            'required_entity_lvl' => 3
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.FRIGATE'),

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_GUNS'),
            'required_entity_lvl' => 3
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.BATTLESHIP'),

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.SHIPYARD'),
            'required_entity_lvl' => 10
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.BATTLESHIP'),

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_SAILS'),
            'required_entity_lvl' => 5
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.BATTLESHIP'),

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_GUNS'),
            'required_entity_lvl' => 5
        ]);

        WarshipDependency::create([
            'warship_id' => config('constants.WARSHIPS.BATTLESHIP'),

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_HOLD'),
            'required_entity_lvl' => 3
        ]);
    }
}
