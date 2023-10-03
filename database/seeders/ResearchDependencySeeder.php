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
            'research_id'  => 2,
            'research_lvl' => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 2
        ]);

        ResearchDependency::create([
            'research_id'  => 1,
            'research_lvl' => 1,

            'required_entity'     => 'building',
            'required_entity_id'  => 2,
            'required_entity_lvl' => 2,
        ]);

        ResearchDependency::create([
            'research_id'  => 1,
            'research_lvl' => 1,

            'required_entity'     => 'research',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 1,
        ]);

        ResearchDependency::create([
            'research_id'  => 3,
            'research_lvl' => 1,

            'required_entity'     => 'research',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 1
        ]);

        ResearchDependency::create([
            'research_id'  => 4,
            'research_lvl' => 1,

            'required_entity'     => 'research',
            'required_entity_id'  => 1,
            'required_entity_lvl' => 1
        ]);
    }
}
