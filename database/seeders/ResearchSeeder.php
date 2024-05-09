<?php

namespace Database\Seeders;

use App\Models\Research;
use Illuminate\Database\Seeder;

class ResearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Research::create([
            'user_id'     => config('constants.DEFAULT_USER_ID'),
            'research_id' => config('constants.RESEARCHES.SHIP_TECHNOLOGIES'),
            'lvl'         => 1
        ]);

        Research::create([
            'user_id'     => config('constants.DEFAULT_USER_ID'),
            'research_id' => config('constants.RESEARCHES.SHIP_SAILS'),
            'lvl'         => 1
        ]);

        Research::create([
            'user_id'     => config('constants.DEFAULT_USER_ID'),
            'research_id' => config('constants.RESEARCHES.TRADE_SYSTEM'),
            'lvl'         => 2
        ]);

        Research::create([
            'user_id'     => config('constants.DEFAULT_USER_ID'),
            'research_id' => config('constants.RESEARCHES.EXPEDITION_SYSTEM'),
            'lvl'         => 2
        ]);

        Research::create([
            'user_id'     => config('constants.DEFAULT_USER_ID'),
            'research_id' => config('constants.RESEARCHES.GOVERNMENTAL_SYSTEM'),
            'lvl'         => 6
        ]);
    }
}
