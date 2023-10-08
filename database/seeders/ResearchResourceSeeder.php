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
            'research_id' => config('constants.RESEARCHES.SHIP_SAILS'),
            'gold'        => 100,
            'population'  => 10,
            'lvl'         => 1,
            'time'        => 5,
        ]);

        ResearchResource::create([
            'research_id' => config('constants.RESEARCHES.SHIP_SAILS'),
            'gold'        => 300,
            'population'  => 40,
            'lvl'         => 2,
            'time'        => 10,
        ]);

        ResearchResource::create([
            'research_id' => config('constants.RESEARCHES.SHIP_TECHNOLOGIES'),
            'gold'        => 222,
            'population'  => 10,
            'lvl'         => 1,
            'time'        => 20,
        ]);

        ResearchResource::create([
            'research_id' => config('constants.RESEARCHES.SHIP_GUNS'),
            'gold'        => 333,
            'population'  => 10,
            'lvl'         => 1,
            'time'        => 30,
        ]);

        ResearchResource::create([
            'research_id' => config('constants.RESEARCHES.SHIP_HOLD'),
            'gold'        => 1000,
            'population'  => 500,
            'lvl'         => 1,
            'time'        => 20,
        ]);
    }
}
