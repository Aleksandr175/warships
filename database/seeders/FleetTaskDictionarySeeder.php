<?php

namespace Database\Seeders;

use App\Models\FleetTaskDictionary;
use Illuminate\Database\Seeder;

class FleetTaskDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FleetTaskDictionary::create([
            'id' => config('constants.FLEET_TASKS.TRADE'),
            'title' => 'Trade',
            'slug' => 'trade',
            'description' => 'Send warships to trade with other islands'
        ]);

        FleetTaskDictionary::create([
            'id' => config('constants.FLEET_TASKS.MOVE'),
            'title' => 'Move',
            'slug' => 'move',
            'description' => 'Move warships from one island to another'
        ]);

        FleetTaskDictionary::create([
            'id' => config('constants.FLEET_TASKS.ATTACK'),
            'title' => 'Attack',
            'slug' => 'attack',
            'description' => 'Attack some island'
        ]);

        FleetTaskDictionary::create([
            'id' => config('constants.FLEET_TASKS.TRANSPORT'),
            'title' => 'Transport',
            'slug' => 'transport',
            'description' => 'Transport resources to island and come back'
        ]);

        FleetTaskDictionary::create([
            'id' => config('constants.FLEET_TASKS.EXPEDITION'),
            'title' => 'Expedition',
            'slug' => 'expedition',
            'description' => 'Expedition'
        ]);
    }
}
