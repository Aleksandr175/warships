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
        ResearchDictionary::create(['title' => 'Improved sails', 'description' => '+10% ship speed']);
        ResearchDictionary::create(['title' => 'Ship technologies', 'description' => 'Allows you to build a shipyard']);
        ResearchDictionary::create(['title' => 'Improved guns', 'description' => '+10% to ship attack, allows you to build warships']);
        ResearchDictionary::create(['title' => 'Ship hold', 'description' => 'Increases the capacity of merchant ships by 10%']);
    }
}
