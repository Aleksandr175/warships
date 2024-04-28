<?php

namespace App\Services;

use App\Models\Adventure;
use App\Models\Archipelago;
use App\Models\City;
use App\Models\CityResource;
use App\Models\Resource;

class CityService
{
    public function generateCitiesForAdventure(Adventure $adventure, Archipelago $archipelago)
    {
        $resourcesCardsDict = Resource::where('type', config('constants.RESOURCE_TYPE_IDS.CARD'))
            ->get()->toArray();

        // TODO: add improved logic for calculation power of islands for adventure
        // change resources
        $adventureLvl = $adventure->adventure_level;
        $baseAmount   = (1.12 ** $adventureLvl * 500);

        $resources   = Resource::get();
        $baseAmounts = [];

        foreach ($resources as $resource) {
            $baseAmounts[$resource->id] = $baseAmount * (1 / $resource->value);
        }

        $city = City::create([
            'title'              => 'Empty Island',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_EMPTY'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 2,
            'coord_y'            => 2,
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => random_int($baseAmount / 100, $baseAmount / 10)
        ]);

        $city = City::create([
            'title'              => 'Village',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_VILLAGE'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 3,
            'coord_y'            => 4,
        ]);

        $coefficient = 0.5;
        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => random_int($baseAmounts[config('constants.RESOURCE_IDS.GOLD')] * $coefficient / 2, $baseAmounts[config('constants.RESOURCE_IDS.GOLD')] * $coefficient),
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'         => random_int($baseAmounts[config('constants.RESOURCE_IDS.POPULATION')] * $coefficient / 2, $baseAmounts[config('constants.RESOURCE_IDS.POPULATION')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.LOG'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.LOG')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.IRON'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.IRON')] * $coefficient)
        ]);



        $city = City::create([
            'title'              => 'Rich City',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_RICH_CITY'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 5,
            'coord_y'            => 4,
        ]);

        $coefficient = 2;
        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => random_int($baseAmounts[config('constants.RESOURCE_IDS.GOLD')] * $coefficient / 2, $baseAmounts[config('constants.RESOURCE_IDS.GOLD')] * $coefficient),
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'         => random_int($baseAmounts[config('constants.RESOURCE_IDS.POPULATION')] * $coefficient / 2, $baseAmounts[config('constants.RESOURCE_IDS.POPULATION')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.LOG'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.LOG')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.PLANK'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.PLANK')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.ORE'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.ORE')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.IRON'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.IRON')] * $coefficient)
        ]);

        $numberOfWarships = random_int(1, 3);
        $this->generateWarshipCardsForCity($city, $numberOfWarships, $resourcesCardsDict);


        $city = City::create([
            'title'              => 'Pirate Bay',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_PIRATE_BAY'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 2,
            'coord_y'            => 4,
        ]);

        $coefficient = 1;
        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => random_int($baseAmounts[config('constants.RESOURCE_IDS.GOLD')] * $coefficient / 2, $baseAmounts[config('constants.RESOURCE_IDS.GOLD')] * $coefficient),
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'         => random_int($baseAmounts[config('constants.RESOURCE_IDS.POPULATION')] * $coefficient / 2, $baseAmounts[config('constants.RESOURCE_IDS.POPULATION')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.LOG'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.LOG')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.ORE'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.ORE')] * $coefficient)
        ]);

        $numberOfWarships = 1;
        $this->generateWarshipCardsForCity($city, $numberOfWarships, $resourcesCardsDict);


        $city = City::create([
            'title'              => 'Treasure Island',
            'adventure_id'       => $adventure->id,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.ADVENTURE_TREASURE'),
            'archipelago_id'     => $archipelago->id,
            'coord_x'            => 1,
            'coord_y'            => 3,
        ]);

        $coefficient = 3;
        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => random_int($baseAmounts[config('constants.RESOURCE_IDS.GOLD')] * $coefficient / 2, $baseAmounts[config('constants.RESOURCE_IDS.GOLD')] * $coefficient),
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.LOG'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.LOG')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.PLANK'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.PLANK')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.ORE'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.ORE')] * $coefficient)
        ]);

        CityResource::create([
            'city_id'     => $city->id,
            'resource_id' => config('constants.RESOURCE_IDS.IRON'),
            'qty'         => random_int(0, $baseAmounts[config('constants.RESOURCE_IDS.IRON')] * $coefficient)
        ]);

        $numberOfWarships = random_int(1, 3);
        $this->generateWarshipCardsForCity($city, $numberOfWarships, $resourcesCardsDict);
    }

    public function addResourceToCity(int $cityId, int $resourceId, int $qty): void
    {
        $resource = CityResource::where('city_id', $cityId)->where('resource_id', $resourceId)->first();

        if ($resource) {
            $resource->increment('qty', $qty);
        } else {
            CityResource::create([
                'city_id'     => $cityId,
                'resource_id' => $resourceId,
                'qty'         => $qty
            ]);
        }
    }

    public function subtractResourceFromCity(int $cityId, int $resourceId, int $qty): void {
        $this->addResourceToCity($cityId, $resourceId, $qty * (-1));
    }

    public function generateWarshipCards($resourcesCards)
    {
        $randomArrayIndex = array_rand($resourcesCards, 1);
        $randomCard       = $resourcesCards[$randomArrayIndex];
        $randomQty        = random_int(0, 3);

        return [
            'resource_id' => $randomCard['id'],
            'qty'         => $randomQty
        ];
    }

    public function generateWarshipCardsForCity(City $city, int $numberOfWarships, $resourcesCardsDict): void
    {
        // generate some cards for adventure
        for ($i = 0; $i < $numberOfWarships; $i++) {
            $cardInfo = $this->generateWarshipCards($resourcesCardsDict);

            if ($cardInfo['qty'] > 0) {
                $resourceExist = CityResource::where('city_id', $city->id)->where('resource_id', $cardInfo['resource_id'])->first();

                if ($resourceExist) {
                    $resourceExist->increment('qty', $cardInfo['qty']);
                } else {
                    CityResource::create([
                        'city_id'     => $city->id,
                        'resource_id' => $cardInfo['resource_id'],
                        'qty'         => $cardInfo['qty']
                    ]);
                }
            }
        }
    }
}
