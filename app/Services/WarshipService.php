<?php

namespace App\Services;

use App\Models\Adventure;
use App\Models\Archipelago;
use App\Models\City;
use App\Models\Warship;
use App\Models\WarshipDictionary;

class WarshipService
{
    public function generateWarshipsForAdventureCities(Adventure $adventure, Archipelago $newArchipelago)
    {
        $adventureLvl = $adventure->adventure_level;
        $difficult = (1.15 ** $adventureLvl);

        $cities = City::where('archipelago_id', $newArchipelago->id)->get();

        foreach ($cities as $city) {
            switch ($city->city_dictionary_id) {
                case config('constants.CITY_TYPE_ID.ADVENTURE_TREASURE'):
                case config('constants.CITY_TYPE_ID.ADVENTURE_EMPTY'):
                    // nothing
                    break;
                case config('constants.CITY_TYPE_ID.ADVENTURE_VILLAGE'):
                    $baseAmount = 3 * $difficult;
                    $luggerAmount    = random_int(1, $baseAmount);
                    $caravelAmount = random_int(1, $baseAmount * 0.7);

                    if ($luggerAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.LUGGER'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $luggerAmount
                        ]);
                    }

                    if ($caravelAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $caravelAmount
                        ]);
                    }

                    break;
                case config('constants.CITY_TYPE_ID.ADVENTURE_RICH_CITY'):
                    $baseAmount = 8 * $difficult;
                    $luggerAmount    = random_int(1, $baseAmount);
                    $caravelAmount = random_int(1, $baseAmount * 0.7);
                    $galeraAmount = random_int(0, $baseAmount * 0.5);
                    $frigateAmount = random_int(0, $baseAmount * 0.3);
                    $battleshipAmount = random_int(0, $baseAmount * 0.1);

                    if ($luggerAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.LUGGER'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $luggerAmount
                        ]);
                    }

                    if ($caravelAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $caravelAmount
                        ]);
                    }

                    if ($galeraAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.GALERA'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $galeraAmount
                        ]);
                    }

                    if ($frigateAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.FRIGATE'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $frigateAmount
                        ]);
                    }

                    if ($battleshipAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.BATTLESHIP'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $battleshipAmount
                        ]);
                    }

                    break;
                case config('constants.CITY_TYPE_ID.ADVENTURE_PIRATE_BAY'):
                    $baseAmount = 5 * $difficult;
                    $luggerAmount    = random_int(1, $baseAmount);
                    $caravelAmount = random_int(1, $baseAmount * 0.7);
                    $galeraAmount = random_int(0, $baseAmount * 0.5);
                    $frigateAmount = random_int(0, $baseAmount * 0.3);

                    if ($luggerAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.LUGGER'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $luggerAmount
                        ]);
                    }

                    if ($caravelAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $caravelAmount
                        ]);
                    }

                    if ($galeraAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.GALERA'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $galeraAmount
                        ]);
                    }

                    if ($frigateAmount) {
                        Warship::create([
                            'warship_id' => config('constants.WARSHIPS.FRIGATE'),
                            'city_id'    => $city->id,
                            'user_id'    => null,
                            'qty'        => $frigateAmount
                        ]);
                    }

                    break;
            }
        }
    }

    // get quantity of warships we can build in city
    public function hasResourceToBuildWarships(City $city, $warshipId, $qtyToBuild) {
        $warshipDict   = WarshipDictionary::find($warshipId)->load('requiredResources');
        $cityResources = $city->resources;

        $maxBuildableQty = $qtyToBuild;

        if ($maxBuildableQty > 10) {
            $maxBuildableQty = 10;
        }

        foreach ($warshipDict->requiredResources as $requiredResource) {
            $maxQtyForResource = 0;

            // Find the corresponding resource in the city resources
            foreach ($cityResources as $cityResource) {
                if ($cityResource->resource_id === $requiredResource->resource_id) {
                    // Calculate the maximum buildable quantity based on this resource
                    $maxQtyForResource = floor($cityResource->qty / $requiredResource->qty);
                }
            }

            // Update the maximum buildable quantity if needed
            $maxBuildableQty = min($maxBuildableQty, $maxQtyForResource);
        }

        return $maxBuildableQty;
    }

    public function subtractResourcesForWarships(int $cityId, WarshipDictionary $warshipDict, int $qty): void {
        $cityService = new CityService();

        foreach ($warshipDict->requiredResources as $requiredResource) {
            $requiredResourceQty = $requiredResource->qty * $qty * (-1);
            $cityService->addResourceToCity($cityId, $requiredResource->resource_id, $requiredResourceQty);
        }
    }
}
