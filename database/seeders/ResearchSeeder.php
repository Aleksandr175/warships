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
            'user_id' => 5,
            'research_id' => 1,
            'lvl' => 1
        ]);

        Research::create([
            'user_id' => 5,
            'research_id' => 2,
            'lvl' => 1
        ]);
    }
}
