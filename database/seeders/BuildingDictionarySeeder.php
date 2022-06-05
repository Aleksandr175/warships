<?php

namespace Database\Seeders;

use App\Models\BuildingDictionary;
use Illuminate\Database\Seeder;

class BuildingDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BuildingDictionary::create(['title' => 'Headquarters', 'description' => 'Main building on the island']);
        BuildingDictionary::create(['title' => 'Mine', 'description' => 'It produces gold']);
        BuildingDictionary::create(['title' => 'Houses', 'description' => 'The more houses, the more workers!']);
        BuildingDictionary::create(['title' => 'Tavern', 'description' => 'Increases the prestige of the island and increases population']);
        BuildingDictionary::create(['title' => 'Farm', 'description' => 'it produces food']);
        BuildingDictionary::create(['title' => 'Shipyard', 'description' => 'It produces warships']);
        BuildingDictionary::create(['title' => 'Dock', 'description' => 'Allows sea merchants to trade on the island']);
        BuildingDictionary::create(['title' => 'Fort', 'description' => 'The main defensive structure']);
    }
}
