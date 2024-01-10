<?php

namespace App\Services;

use App\Models\Adventure;
use App\Models\Archipelago;
use App\Models\City;
use App\Models\Warship;

class WarshipService
{
    public function generateWarshipsForAdventureCities(Adventure $adventure, Archipelago $newArchipelago)
    {
        // TODO: add increment coefficient for number of warships in city
        $adventureLvl = $adventure->adventure_level;

        $cities = City::where('archipelago_id', $newArchipelago->id)->get();

        foreach ($cities as $city) {
            // TODO: check points for island and generate enough warships
            switch ($city->city_dictionary_id) {
                case config('constants.CITY_TYPE_ID.ADVENTURE_TREASURE'):
                case config('constants.CITY_TYPE_ID.ADVENTURE_EMPTY'):
                    // nothing
                    break;
                case config('constants.CITY_TYPE_ID.ADVENTURE_VILLAGE'):
                    Warship::create([
                        'warship_id' => config('constants.WARSHIPS.LUGGER'),
                        'city_id'    => $city->id,
                        'user_id'    => null,
                        'qty'        => 2
                    ]);
                    break;
                case config('constants.CITY_TYPE_ID.ADVENTURE_RICH_CITY'):
                    Warship::create([
                        'warship_id' => config('constants.WARSHIPS.LUGGER'),
                        'city_id'    => $city->id,
                        'user_id'    => null,
                        'qty'        => 5
                    ]);
                    Warship::create([
                        'warship_id' => config('constants.WARSHIPS.CARAVEL'),
                        'city_id'    => $city->id,
                        'user_id'    => null,
                        'qty'        => 5
                    ]);

                    Warship::create([
                        'warship_id' => config('constants.WARSHIPS.GALERA'),
                        'city_id'    => $city->id,
                        'user_id'    => null,
                        'qty'        => 3
                    ]);
                    break;
                case config('constants.CITY_TYPE_ID.ADVENTURE_PIRATE_BAY'):
                    Warship::create([
                        'warship_id' => config('constants.WARSHIPS.LUGGER'),
                        'city_id'    => $city->id,
                        'user_id'    => null,
                        'qty'        => 4
                    ]);
                    Warship::create([
                        'warship_id' => config('constants.WARSHIPS.GALERA'),
                        'city_id'    => $city->id,
                        'user_id'    => null,
                        'qty'        => 2
                    ]);
                    break;
            }
        }
    }
}
