<?php

namespace Database\Seeders;

use App\Models\WarshipImprovementRecipe;
use Illuminate\Database\Seeder;

class WarshipImprovementRecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.LUGGER'),
                'improvement_type'    => 'attack',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_LUGGER_ATTACK'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.LUGGER'),
                'improvement_type'    => 'capacity',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_LUGGER_CAPACITY'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.LUGGER'),
                'improvement_type'    => 'health',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_LUGGER_HEALTH'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);


            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.CARAVEL'),
                'improvement_type'    => 'attack',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_CARAVEL_ATTACK'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.CARAVEL'),
                'improvement_type'    => 'capacity',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_CARAVEL_CAPACITY'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.CARAVEL'),
                'improvement_type'    => 'health',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_CARAVEL_HEALTH'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);


            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.GALERA'),
                'improvement_type'    => 'attack',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_GALERA_ATTACK'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.GALERA'),
                'improvement_type'    => 'capacity',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_GALERA_CAPACITY'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.GALERA'),
                'improvement_type'    => 'health',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_GALERA_HEALTH'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);


            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.FRIGATE'),
                'improvement_type'    => 'attack',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_FRIGATE_ATTACK'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.FRIGATE'),
                'improvement_type'    => 'capacity',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_FRIGATE_CAPACITY'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.FRIGATE'),
                'improvement_type'    => 'health',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_FRIGATE_HEALTH'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);


            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.BATTLESHIP'),
                'improvement_type'    => 'attack',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_BATTLESHIP_ATTACK'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.BATTLESHIP'),
                'improvement_type'    => 'capacity',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_BATTLESHIP_CAPACITY'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);

            WarshipImprovementRecipe::create([
                'warship_id'          => config('constants.WARSHIPS.BATTLESHIP'),
                'improvement_type'    => 'health',
                'level'               => $i,
                'resource_id'         => config('constants.RESOURCE_IDS.CARD_BATTLESHIP_HEALTH'),
                'qty'                 => $i,
                'percent_improvement' => $i,
            ]);
        }
    }
}
