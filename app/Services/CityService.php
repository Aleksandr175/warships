<?php

namespace App\Services;

use App\Models\Adventure;
use App\Models\Archipelago;
use App\Models\City;

class CityService
{
    public function generateCitiesForAdventure(Adventure $adventure, Archipelago $archipelago)
    {
        // TODO: add improved logic for calculation power of islands for adventure
        // change resources
        // TODO: add warships to islands

        City::create([
            'title'              => 'Empty Island',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_EMPTY'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 2,
            'coord_y'            => 2,
            'gold'               => 100,
            'population'         => 0
        ]);

        City::create([
            'title'              => 'Village',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_VILLAGE'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 3,
            'coord_y'            => 4,
            'gold'               => 300,
            'population'         => 100
        ]);

        City::create([
            'title'              => 'Rich City',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_RICH_CITY'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 5,
            'coord_y'            => 4,
            'gold'               => 1500,
            'population'         => 500
        ]);

        City::create([
            'title'              => 'Pirate Bay',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_PIRATE_BAY'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 2,
            'coord_y'            => 4,
            'gold'               => 1000,
            'population'         => 400
        ]);

        City::create([
            'title'              => 'Village',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_TREASURE'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 1,
            'coord_y'            => 3,
            'gold'               => 3000,
            'population'         => 0
        ]);
    }
}
