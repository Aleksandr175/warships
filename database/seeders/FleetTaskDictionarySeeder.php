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
            'title' => 'Trade',
            'slug' => 'trade',
            'description' => 'Send warships to trade with other islands'
        ]);

        FleetTaskDictionary::create([
            'title' => 'Move',
            'slug' => 'move',
            'description' => 'Move warships from one island to another'
        ]);

        FleetTaskDictionary::create([
            'title' => 'Attack',
            'slug' => 'attack',
            'description' => 'Attack some island'
        ]);

        FleetTaskDictionary::create([
            'title' => 'Transport',
            'slug' => 'transport',
            'description' => 'Transport resources to island and come back'
        ]);

        FleetTaskDictionary::create([
            'title' => 'Expedition',
            'slug' => 'expedition',
            'description' => 'Expedition'
        ]);
    }
}
