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
        $adventureLvl = $adventure->adventure_level;
        $baseAmount = (1.12 ** $adventureLvl * 500);

        City::create([
            'title'              => 'Empty Island',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_EMPTY'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 2,
            'coord_y'            => 2,
            'gold'               => random_int($baseAmount / 100, $baseAmount / 10),
            'population'         => 0
        ]);

        City::create([
            'title'              => 'Village',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_VILLAGE'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 3,
            'coord_y'            => 4,
            'gold'               => random_int($baseAmount / 3, $baseAmount),
            'population'         => random_int($baseAmount / 10, $baseAmount / 3)
        ]);

        City::create([
            'title'              => 'Rich City',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_RICH_CITY'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 5,
            'coord_y'            => 4,
            'gold'               => random_int($baseAmount, $baseAmount * 2),
            'population'         => random_int($baseAmount / 3, $baseAmount / 2)
        ]);

        City::create([
            'title'              => 'Pirate Bay',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_PIRATE_BAY'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 2,
            'coord_y'            => 4,
            'gold'               => random_int($baseAmount / 2, $baseAmount),
            'population'         => random_int($baseAmount / 5, $baseAmount / 3)
        ]);

        City::create([
            'title'              => 'Village',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_TREASURE'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 1,
            'coord_y'            => 3,
            'gold'               => random_int($baseAmount * 2, $baseAmount * 3),
            'population'         => 0
        ]);
    }
}
