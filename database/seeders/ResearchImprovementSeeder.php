<?php

namespace Database\Seeders;

use App\Models\ResearchImprovement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResearchImprovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResearchImprovement::create([
            'user_id'             => config('constants.DEFAULT_USER_ID'),
            'research_id'         => config('constants.RESEARCHES.SHIP_GUNS'),
            'improvement_type'    => 'attack',
            'level'               => 2,
            'percent_improvement' => 20
        ]);

        ResearchImprovement::create([
            'user_id'             => config('constants.DEFAULT_USER_ID'),
            'research_id'         => config('constants.RESEARCHES.SHIP_HOLD'),
            'improvement_type'    => 'capacity',
            'level'               => 1,
            'percent_improvement' => 10
        ]);
    }
}
