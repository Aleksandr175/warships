<?php

namespace Database\Seeders;

use App\Models\RefiningRecipe;
use Illuminate\Database\Seeder;

class RefiningRecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RefiningRecipe::create([
            'input_resource_id'       => config('constants.RESOURCE_IDS.LOG'),
            'input_qty'               => 5,
            'output_resource_id'      => config('constants.RESOURCE_IDS.PLANK'),
            'output_qty'              => 1,
            'refining_level_required' => 1,
            'time'                    => 10
        ]);

        RefiningRecipe::create([
            'input_resource_id'       => config('constants.RESOURCE_IDS.PLANK'),
            'input_qty'               => 3,
            'output_resource_id'      => config('constants.RESOURCE_IDS.LUMBER'),
            'output_qty'              => 1,
            'refining_level_required' => 2,
            'time'                    => 25
        ]);

        RefiningRecipe::create([
            'input_resource_id'       => config('constants.RESOURCE_IDS.ORE'),
            'input_qty'               => 5,
            'output_resource_id'      => config('constants.RESOURCE_IDS.IRON'),
            'output_qty'              => 1,
            'refining_level_required' => 1,
            'time'                    => 15
        ]);

        RefiningRecipe::create([
            'input_resource_id'       => config('constants.RESOURCE_IDS.IRON'),
            'input_qty'               => 3,
            'output_resource_id'      => config('constants.RESOURCE_IDS.STEEL'),
            'output_qty'              => 1,
            'refining_level_required' => 1,
            'time'                    => 50
        ]);
    }
}
