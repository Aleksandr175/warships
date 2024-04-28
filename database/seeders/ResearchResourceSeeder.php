<?php

namespace Database\Seeders;

use App\Models\ResearchResource;
use Illuminate\Database\Seeder;

class ResearchResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_SAILS'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 5,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_SAILS'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 20,
            'lvl'           => 1,
            'time_required' => 5,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_SAILS'),
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 10,
            'lvl'           => 1,
            'time_required' => 5,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_SAILS'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 300,
            'lvl'           => 2,
            'time_required' => 15,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_SAILS'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 100,
            'lvl'           => 2,
            'time_required' => 15,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_SAILS'),
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 40,
            'lvl'           => 2,
            'time_required' => 15,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_TECHNOLOGIES'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 222,
            'lvl'           => 1,
            'time_required' => 20,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_TECHNOLOGIES'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 20,
            'lvl'           => 1,
            'time_required' => 5,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_GUNS'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 333,
            'lvl'           => 1,
            'time_required' => 30,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_GUNS'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 20,
            'lvl'           => 1,
            'time_required' => 30,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_HOLD'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 1000,
            'lvl'           => 1,
            'time_required' => 20,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_HOLD'),
            'resource_id'   => config('constants.RESOURCE_IDS.LOG'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 20,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.SHIP_HOLD'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 20,
            'lvl'           => 1,
            'time_required' => 20,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 300,
            'lvl'           => 1,
            'time_required' => 20,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 20,
            'lvl'           => 1,
            'time_required' => 20,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 1000,
            'lvl'           => 2,
            'time_required' => 100,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 400,
            'lvl'           => 2,
            'time_required' => 100,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 50,
            'lvl'           => 2,
            'time_required' => 100,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 10000,
            'lvl'           => 3,
            'time_required' => 300,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 800,
            'lvl'           => 3,
            'time_required' => 300,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.ORE'),
            'qty'           => 100,
            'lvl'           => 3,
            'time_required' => 300,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 150,
            'lvl'           => 3,
            'time_required' => 300,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.EXPEDITION_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 300,
            'lvl'           => 1,
            'time_required' => 100,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.EXPEDITION_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 100,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.EXPEDITION_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 50,
            'lvl'           => 2,
            'time_required' => 400,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.EXPEDITION_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 1000,
            'lvl'           => 2,
            'time_required' => 400,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.EXPEDITION_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'qty'           => 150,
            'lvl'           => 3,
            'time_required' => 1000,
        ]);

        ResearchResource::create([
            'research_id'   => config('constants.RESEARCHES.EXPEDITION_SYSTEM'),
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 4000,
            'lvl'           => 3,
            'time_required' => 1000,
        ]);
    }
}
