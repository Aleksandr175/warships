<?php

namespace Database\Seeders;

use App\Models\CityDictionary;
use Illuminate\Database\Seeder;

class CityDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CityDictionary::create([
            'id' => config('constants.CITY_TYPE_ID.ISLAND'),
            'title' => 'Island',
            'description' => 'Island of player'
        ]);

        CityDictionary::create([
            'id' => config('constants.CITY_TYPE_ID.PIRATE_BAY'),
            'title' => 'Pirate Bay',
            'description' => 'This island is full of pirates'
        ]);

        CityDictionary::create([
            'id' => config('constants.CITY_TYPE_ID.COLONY'),
            'title' => 'Colony',
            'description' => 'This island is full of resources and can provide you resources'
        ]);

        CityDictionary::create([
            'id' => config('constants.CITY_TYPE_ID.ADVENTURE_EMPTY'),
            'title' => 'Empty Island',
            'description' => 'This island has just a few resources'
        ]);

        CityDictionary::create([
            'id' => config('constants.CITY_TYPE_ID.ADVENTURE_VILLAGE'),
            'title' => 'Village',
            'description' => 'This village is pretty small, it has small amount of resources and several warships'
        ]);

        CityDictionary::create([
            'id' => config('constants.CITY_TYPE_ID.ADVENTURE_RICH_CITY'),
            'title' => 'Rich City',
            'description' => 'This island has a lot of resources and a lot of warships'
        ]);

        CityDictionary::create([
            'id' => config('constants.CITY_TYPE_ID.ADVENTURE_PIRATE_BAY'),
            'title' => 'Pirate Bay',
            'description' => 'Pirate Bay has a good resources and a lot of warships'
        ]);

        CityDictionary::create([
            'id' => config('constants.CITY_TYPE_ID.ADVENTURE_TREASURE'),
            'title' => 'Treasure Island',
            'description' => 'Island has a lot of rare resources!'
        ]);
    }
}
