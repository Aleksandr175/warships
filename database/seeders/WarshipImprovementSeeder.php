<?php

namespace Database\Seeders;

use App\Models\WarshipImprovement;
use Illuminate\Database\Seeder;

class WarshipImprovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WarshipImprovement::create([
            'user_id'             => config('constants.DEFAULT_USER_ID'),
            'warship_id'          => config('constants.WARSHIPS.LUGGER'),
            'improvement_type'    => 'attack',
            'level'               => 2,
            'percent_improvement' => 2
        ]);

        WarshipImprovement::create([
            'user_id'             => config('constants.DEFAULT_USER_ID'),
            'warship_id'          => config('constants.WARSHIPS.LUGGER'),
            'improvement_type'    => 'health',
            'level'               => 1,
            'percent_improvement' => 1
        ]);

        WarshipImprovement::create([
            'user_id'             => config('constants.DEFAULT_USER_ID'),
            'warship_id'          => config('constants.WARSHIPS.CARAVEL'),
            'improvement_type'    => 'capacity',
            'level'               => 1,
            'percent_improvement' => 1
        ]);
    }
}
