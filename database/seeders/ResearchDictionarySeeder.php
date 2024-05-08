<?php

namespace Database\Seeders;

use App\Models\ResearchDictionary;
use Illuminate\Database\Seeder;

class ResearchDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResearchDictionary::create(['id' => config('constants.RESEARCHES.SHIP_TECHNOLOGIES'), 'title' => 'Ship technologies', 'description' => 'Allows you to build a shipyard']);
        ResearchDictionary::create(['id' => config('constants.RESEARCHES.SHIP_SAILS'), 'title' => 'Improved sails', 'description' => '+10% ship speed']);
        ResearchDictionary::create(['id' => config('constants.RESEARCHES.SHIP_GUNS'), 'title' => 'Improved guns', 'description' => '+10% to ship attack, allows you to build warships']);
        ResearchDictionary::create(['id' => config('constants.RESEARCHES.SHIP_HOLD'), 'title' => 'Ship hold', 'description' => 'Increases the capacity of merchant ships by 10%']);
        ResearchDictionary::create(['id' => config('constants.RESEARCHES.TRADE_SYSTEM'), 'title' => 'Trade System', 'description' => 'Increases maximum number of trading fleets by 1']);
        ResearchDictionary::create(['id' => config('constants.RESEARCHES.EXPEDITION_SYSTEM'), 'title' => 'Expedition System', 'description' => 'Increases maximum number of expedition fleets by 1']);
        ResearchDictionary::create(['id' => config('constants.RESEARCHES.GOVERNMENTAL_SYSTEM'), 'title' => 'Governmental System', 'description' => 'Increases maximum number of available islands']);
    }
}
