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
        BuildingDictionary::create(['id' => config('constants.BUILDINGS.MAIN'), 'title' => 'Headquarters', 'description' => 'Main building on the island']);
        BuildingDictionary::create(['id' => config('constants.BUILDINGS.MINE'), 'title' => 'Mine', 'description' => 'It produces gold']);
        BuildingDictionary::create(['id' => config('constants.BUILDINGS.HOUSES'), 'title' => 'Houses', 'description' => 'The more houses, the more workers!']);
        BuildingDictionary::create(['id' => config('constants.BUILDINGS.TAVERN'), 'title' => 'Tavern', 'description' => 'Increases the prestige of the island and increases population']);
        BuildingDictionary::create(['id' => config('constants.BUILDINGS.FARM'), 'title' => 'Farm', 'description' => 'it produces food']);
        BuildingDictionary::create(['id' => config('constants.BUILDINGS.SHIPYARD'), 'title' => 'Shipyard', 'description' => 'It produces warships']);
        BuildingDictionary::create(['id' => config('constants.BUILDINGS.DOCK'), 'title' => 'Dock', 'description' => 'Allows sea merchants to trade on the island']);
        BuildingDictionary::create(['id' => config('constants.BUILDINGS.FORTRESS'), 'title' => 'Fort', 'description' => 'The main defensive structure']);
    }
}
