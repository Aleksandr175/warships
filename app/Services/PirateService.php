<?php

namespace App\Services;

use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\WarshipDictionary;
use Carbon\Carbon;

class PirateService
{
    // send fleet to target
    public function handle(City $city)
    {
        if (!count($city->fleets)) {
            dump('no pirate fleet -> sending new one to some player');

            $timeToTarget = 1000;
            $gold         = 0;
            $speed        = 100;

            $warshipGroupsInCity = $city->warships;

            dump('warshipGroupsInCity', $warshipGroupsInCity);

            $fleetDetails = $this->getAvailableWarshipsForSending($warshipGroupsInCity);

            if (!count($fleetDetails)) {
                dump('Not enough warships for fleet');

                return false;
            }

            // TODO: get random user
            $targetCityId = 10;

            // create fleet and details
            $fleetId = (new Fleet)->create([
                'city_id'        => $city->id,
                'target_city_id' => $targetCityId,
                'fleet_task_id'  => 3, //attack
                'speed'          => $speed,
                'time'           => $timeToTarget,
                'gold'           => $gold,
                'repeating'      => 0,
                'status_id'      => 1, // TODO: set default value for fleet status id
                'deadline'       => Carbon::now()->addSeconds($timeToTarget)
            ])->id;

            (new FleetService)->moveWarshipsFromCityToFleet($warshipGroupsInCity, $fleetId, $fleetDetails);

            dump('Pirate fleet has been sent to targetCityId: ' . $targetCityId);
        } else {
            dump('Try to build new pirate warship');

            $totalWarships = $city->warships()->sum('qty');

            dump('Total warships in city: ' . $totalWarships);

            if (count($city->warshipQueues) > 0) {
                dump('Pirates has warship queue, skip...');

                return;
            }

            // Pirate island cant have more than 100 warships
            if ($totalWarships < 100) {
                // Find the most expensive warship based on both gold and population costs
                $mostExpensiveWarship = WarshipDictionary::find(4); // frigate

                dump($city->gold, $city->population, $mostExpensiveWarship->gold, $mostExpensiveWarship->population);
                // Check if you have more resources (both gold and population) than required for the most expensive warship
                if (
                    $city->gold >= $mostExpensiveWarship->gold &&
                    $city->population >= $mostExpensiveWarship->population
                ) {
                    // Define the chances of building each type of warship
                    $chances = [
                        1 => 50,  // lugger: 50% chance
                        2 => 25,  // caravel: 25% chance
                        3 => 20,  // galera: 20% chance
                        4 => 5,   // frigate: 5% chance
                        5 => 0    // battleship: 0% chance
                    ];

                    // Calculate a random number between 1 and 100
                    $randomNumber = mt_rand(1, 100);

                    // Determine which type of warship to create based on chances
                    $chosenWarshipId = null;
                    $chanceSum       = 0;

                    foreach ($chances as $warshipId => $chance) {
                        $chanceSum += $chance;

                        if ($randomNumber <= $chanceSum) {
                            $chosenWarshipId = $warshipId;
                            break;
                        }
                    }

                    if ($chosenWarshipId !== null) {
                        // Check if there are enough resources (both gold and population) to create this type of warship
                        $warshipData = WarshipDictionary::find($chosenWarshipId);

                        if (
                            $city->gold >= $warshipData->gold &&
                            $city->population >= $warshipData->population
                        ) {
                            $warshipQueueService = new WarshipQueueService();
                            $warshipQueueService->orderWarship(1, [
                                'cityId'    => $city->id,
                                'qty'       => 1,
                                'warshipId' => $chosenWarshipId
                            ]);

                            dump("Pirates is building a warship with id: $chosenWarshipId in cityId: $city->id");

                            // Deduct the resources from the city
                            $city->decrement('gold', $warshipData->gold);
                            $city->decrement('population', $warshipData->population);
                        }
                    }
                }
            }
        }
    }

    public function getAvailableWarshipsForSending($warshipGroupsInCity)
    {
        // Calculate the total number of warships in the city
        $totalWarships = $warshipGroupsInCity->sum('qty');

        // Check if there are at least 20 warships to send a fleet
        if ($totalWarships < 20) {
            dump('Not enough warships for fleet, $totalWarships < 20');

            return [];
        }

        // Define the proportions for each warship type
        $proportions = [
            1 => 0.5,  // id 1: 50% proportion
            2 => 0.25, // id 2: 25% proportion
            3 => 0.2,  // id 3: 20% proportion
            4 => 0.05, // id 4: 5% proportion
        ];

        // Define the maximum number of warships for each type
        $maxLimits = [
            1 => 10,   // id 1: Max limit is 10
            2 => 5,    // id 2: Max limit is 5
            3 => 4,    // id 3: Max limit is 4
            4 => 1,    // id 4: Max limit is 1
        ];

        // Calculate the maximum number of warships to send for each type
        $selectedWarships = [];
        foreach ($warshipGroupsInCity as $warship) {
            $warshipId    = $warship->warship_id;
            $availableQty = $warship->qty;
            $qty          = min(
                floor($availableQty * $proportions[$warshipId]),
                $maxLimits[$warshipId]
            );

            if ($qty > 0) {
                $selectedWarships[] = [
                    'warship_id' => $warshipId,
                    'qty'        => $qty
                ];
            }
        }

        // Send the selected warships as the fleet
        return $selectedWarships;
    }
}
