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
            'city_id'    => config('constants.DEFAULT_USER_CITY_ID'),
            'user_id'    => config('constants.DEFAULT_USER_ID'),
            'qty'        => 30
        ]);

        Warship::create([
            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
            'city_id'    => config('constants.DEFAULT_USER_CITY_ID'),
            'user_id'    => config('constants.DEFAULT_USER_ID'),
            'qty'        => 20
        ]);

        Warship::create([
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'city_id'    => config('constants.DEFAULT_USER_CITY_ID'),
            'user_id'    => config('constants.DEFAULT_USER_ID'),
            'qty'        => 10
        ]);

        // set some warships for pirate bays
        $pirateBays = City::where('city_dictionary_id', config('constants.CITY_TYPE_ID.PIRATE_BAY'))->get();

        foreach ($pirateBays as $pirateBay) {
            Warship::create([
                'warship_id' => config('constants.WARSHIPS.LUGGER'),
                'city_id'    => $pirateBay->id,
                'user_id'    => config('constants.DEFAULT_PIRATE_ID'),
                'qty'        => 15
            ]);

            Warship::create([
                'warship_id' => config('constants.WARSHIPS.CARAVEL'),
                'city_id'    => $pirateBay->id,
                'user_id'    => config('constants.DEFAULT_PIRATE_ID'),
                'qty'        => 7
            ]);

            Warship::create([
                'warship_id' => config('constants.WARSHIPS.GALERA'),
                'city_id'    => $pirateBay->id,
                'user_id'    => config('constants.DEFAULT_PIRATE_ID'),
                'qty'        => 2
            ]);
        }
    }
}
