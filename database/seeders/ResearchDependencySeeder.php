<?php

namespace Database\Seeders;

use App\Models\ResearchDependency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResearchDependencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResearchDependency::create([
            'research_id'  => config('constants.RESEARCHES.SHIP_TECHNOLOGIES'),
            'research_lvl' => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.MINE'),
            'required_entity_lvl' => 2,
        ]);

        ResearchDependency::create([
            'research_id'  => config('constants.RESEARCHES.SHIP_SAILS'),
            'research_lvl' => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => config('constants.BUILDINGS.MAIN'),
            'required_entity_lvl' => 2
        ]);

        ResearchDependency::create([
            'research_id'  => config('constants.RESEARCHES.SHIP_GUNS'),
            'research_lvl' => 1,

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_HOLD'),
            'required_entity_lvl' => 1,
        ]);

        ResearchDependency::create([
            'research_id'  => config('constants.RESEARCHES.SHIP_GUNS'),
            'research_lvl' => 1,

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_TECHNOLOGIES'),
            'required_entity_lvl' => 1
        ]);

        ResearchDependency::create([
            'research_id'  => config('constants.RESEARCHES.SHIP_HOLD'),
            'research_lvl' => 1,

            'required_entity'     => 'research',
            'required_entity_id'  => config('constants.RESEARCHES.SHIP_TECHNOLOGIES'),
            'required_entity_lvl' => 1
        ]);
    }
}
