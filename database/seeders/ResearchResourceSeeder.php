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
            'research_id' => 1,
            'gold'        => 100,
            'population'  => 10,
            'lvl'         => 1,
            'time'        => 5,
        ]);

        ResearchResource::create([
            'research_id' => 1,
            'gold'        => 300,
            'population'  => 40,
            'lvl'         => 2,
            'time'        => 10,
        ]);

        ResearchResource::create([
            'research_id' => 2,
            'gold'        => 222,
            'population'  => 10,
            'lvl'         => 1,
            'time'        => 20,
        ]);

        ResearchResource::create([
            'research_id' => 3,
            'gold'        => 333,
            'population'  => 10,
            'lvl'         => 1,
            'time'        => 30,
        ]);

        ResearchResource::create([
            'research_id' => 4,
            'gold'        => 1000,
            'population'  => 500,
            'lvl'         => 1,
            'time'        => 20,
        ]);
    }
}
