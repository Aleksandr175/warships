<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityDictionary;
use App\Models\Warship;
use Illuminate\Database\Seeder;

class WarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warship::create([
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'city_id' => 10,
            'user_id' => config('constants.DEFAULT_USER_ID'),
            'qty' => 30
        ]);

        Warship::create([
            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
            'city_id' => 10,
            'user_id' => config('constants.DEFAULT_USER_ID'),
            'qty' => 20
        ]);

        Warship::create([
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'city_id' => 10,
            'user_id' => config('constants.DEFAULT_USER_ID'),
            'qty' => 10
        ]);

        // set some warships for pirate bays
        $pirateBays = City::where('city_dictionary_id', CityDictionary::PIRATE_BAY)->get();

        foreach ($pirateBays as $pirateBay) {
            Warship::create([
                'warship_id' => config('constants.WARSHIPS.LUGGER'),
                'city_id' => $pirateBay->id,
                'user_id' => null,
                'qty' => 15
            ]);

            Warship::create([
                'warship_id' => config('constants.WARSHIPS.CARAVEL'),
                'city_id' => $pirateBay->id,
                'user_id' => null,
                'qty' => 7
            ]);

            Warship::create([
                'warship_id' => config('constants.WARSHIPS.GALERA'),
                'city_id' => $pirateBay->id,
                'user_id' => null,
                'qty' => 2
            ]);
        }
    }
}
