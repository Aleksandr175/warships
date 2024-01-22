<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.GOLD'),
            'title'       => 'Gold',
            'description' => 'Gold description',
            'slug'        => 'gold'
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.POPULATION'),
            'title'       => 'Population',
            'description' => 'Population description',
            'slug'        => 'population'
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.LOGS'),
            'title'       => 'Logs',
            'description' => 'Logs description',
            'slug'        => 'log'
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.ORE'),
            'title'       => 'Ore',
            'description' => 'Ore description',
            'slug'        => 'ore'
        ]);
    }
}
