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
            'slug'        => 'gold',
            'value'       => 1,
            'type'        => config('constants.RESOURCE_TYPE_IDS.COMMON'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.POPULATION'),
            'title'       => 'Population',
            'description' => 'Population description',
            'slug'        => 'population',
            'value'       => 3,
            'type'        => config('constants.RESOURCE_TYPE_IDS.COMMON'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.LOG'),
            'title'       => 'Logs',
            'description' => 'Logs description',
            'slug'        => 'log',
            'value'       => 10,
            'type'        => config('constants.RESOURCE_TYPE_IDS.COMMON'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.PLANK'),
            'title'       => 'Plank',
            'description' => 'Plank description',
            'slug'        => 'plank',
            'value'       => 30,
            'type'        => config('constants.RESOURCE_TYPE_IDS.COMMON'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.LUMBER'),
            'title'       => 'Lumber',
            'description' => 'Lumber description',
            'slug'        => 'lumber',
            'value'       => 100,
            'type'        => config('constants.RESOURCE_TYPE_IDS.COMMON'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.ORE'),
            'title'       => 'Ore',
            'description' => 'Ore description',
            'slug'        => 'ore',
            'value'       => 10,
            'type'        => config('constants.RESOURCE_TYPE_IDS.COMMON'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.IRON'),
            'title'       => 'Iron',
            'description' => 'Iron description',
            'slug'        => 'iron',
            'value'       => 30,
            'type'        => config('constants.RESOURCE_TYPE_IDS.COMMON'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.STEEL'),
            'title'       => 'Steel',
            'description' => 'Steel description',
            'slug'        => 'steel',
            'value'       => 100,
            'type'        => config('constants.RESOURCE_TYPE_IDS.COMMON'),
        ]);


        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_LUGGER_ATTACK'),
            'title'       => 'Attack Card For Lugger',
            'description' => 'Attack Card',
            'slug'        => 'lugger_attack_card',
            'value'       => 500,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_LUGGER_CAPACITY'),
            'title'       => 'Capacity Card For Lugger',
            'description' => 'Capacity Card',
            'slug'        => 'lugger_capacity_card',
            'value'       => 500,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_LUGGER_HEALTH'),
            'title'       => 'Health Card For Lugger',
            'description' => 'Health Card',
            'slug'        => 'lugger_health_card',
            'value'       => 500,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_CARAVEL_ATTACK'),
            'title'       => 'Attack Card For Caravel',
            'description' => 'Attack Card',
            'slug'        => 'caravel_attack_card',
            'value'       => 600,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_CARAVEL_CAPACITY'),
            'title'       => 'Capacity Card For Caravel',
            'description' => 'Capacity Card',
            'slug'        => 'caravel_capacity_card',
            'value'       => 600,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_CARAVEL_HEALTH'),
            'title'       => 'Health Card For Caravel',
            'description' => 'Health Card',
            'slug'        => 'caravel_health_card',
            'value'       => 600,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);


        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_GALERA_ATTACK'),
            'title'       => 'Attack Card For Galera',
            'description' => 'Attack Card',
            'slug'        => 'galera_attack_card',
            'value'       => 700,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_GALERA_CAPACITY'),
            'title'       => 'Capacity Card For Galera',
            'description' => 'Capacity Card',
            'slug'        => 'galera_capacity_card',
            'value'       => 700,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_GALERA_HEALTH'),
            'title'       => 'Health Card For Galera',
            'description' => 'Health Card',
            'slug'        => 'galera_health_card',
            'value'       => 700,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_FRIGATE_ATTACK'),
            'title'       => 'Attack Card For Frigate',
            'description' => 'Attack Card',
            'slug'        => 'frigate_attack_card',
            'value'       => 800,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_FRIGATE_CAPACITY'),
            'title'       => 'Capacity Card For Frigate',
            'description' => 'Capacity Card',
            'slug'        => 'frigate_capacity_card',
            'value'       => 800,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_FRIGATE_HEALTH'),
            'title'       => 'Health Card For Frigate',
            'description' => 'Health Card',
            'slug'        => 'frigate_health_card',
            'value'       => 800,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_BATTLESHIP_ATTACK'),
            'title'       => 'Attack Card For Battleship',
            'description' => 'Attack Card',
            'slug'        => 'battleship_attack_card',
            'value'       => 1000,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_BATTLESHIP_CAPACITY'),
            'title'       => 'Capacity Card For Battleship',
            'description' => 'Capacity Card',
            'slug'        => 'battleship_capacity_card',
            'value'       => 1000,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.CARD_BATTLESHIP_HEALTH'),
            'title'       => 'Health Card For Battleship',
            'description' => 'Health Card',
            'slug'        => 'battleship_health_card',
            'value'       => 1000,
            'type'        => config('constants.RESOURCE_TYPE_IDS.CARD'),
        ]);

        Resource::create([
            'id'          => config('constants.RESOURCE_IDS.KNOWLEDGE'),
            'title'       => 'Knowledge',
            'description' => 'Knowledge needs for researches',
            'slug'        => 'knowledge',
            'value'       => 1000,
            'type'        => config('constants.RESOURCE_TYPE_IDS.RESEARCH'),
        ]);
    }
}
