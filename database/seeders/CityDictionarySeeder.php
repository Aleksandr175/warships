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
    }
}
