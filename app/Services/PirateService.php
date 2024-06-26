<?php

namespace App\Services;

use App\Models\Archipelago;
use App\Models\City;
use App\Models\Fleet;
use App\Models\WarshipDictionary;
use Carbon\Carbon;

class PirateService
{
    // send fleet to target
    public function handle(City $city)
    {
        if (!count($city->fleets)) {
            dump('no active pirate fleets -> sending new one to player in archipelago');

            $timeToTarget = 1000;
            $speed        = 100;

            $warshipGroupsInCity = $city->warships;

            dump('warshipGroupsInCity', $warshipGroupsInCity);

            $fleetDetails = $this->getAvailableWarshipsForSending($warshipGroupsInCity);

            if (!count($fleetDetails)) {
                dump('Not enough warships for fleet');

                return false;
            }

            $archipelagoId = $city->archipelago_id;
            $archipelago = Archipelago::find($archipelagoId);

            if (!$archipelago) {
                dump('No arhipelago');
                return false;
            }

            $userCities = $archipelago->userCities;

            if (!count($userCities)) {
                dump('No user islands in arhipelago');
                return false;
            }

            $targetCityId = $userCities->random()->id;

            // create fleet and details
            $fleetId = (new Fleet)->create([
                'city_id'        => $city->id,
                'target_city_id' => $targetCityId,
                'fleet_task_id'  => config('constants.FLEET_TASKS.ATTACK'),
                'speed'          => $speed,
                'time'           => $timeToTarget,
                'repeating'      => 0,
                'status_id'      => config('constants.FLEET_STATUSES.ATTACK_GOING_TO_TARGET'),
                'deadline'       => Carbon::now()->addSeconds($timeToTarget)
            ])->id;

            (new FleetService)->moveWarshipsFromCityToFleet($warshipGroupsInCity, $fleetId, $fleetDetails);

            dump('Pirate fleet has been sent to targetCityId: ' . $targetCityId);
        } else {
            dump('Try to build new pirate warship');

            $totalWarships = $city->warships()->sum('qty');

            dump('Total warships in city: ' . $totalWarships);

            if (count($city->warshipQueue) > 0) {
                dump('Pirates has warship queue, skip...');

                return;
            }

            // Pirate island cant have more than 100 warships
            if ($totalWarships < 100) {
                $warshipService = new WarshipService();
                // Find the most expensive warship based on resources it requires
                $warshipQtyToBuild = $warshipService->hasResourceToBuildWarships($city, config('constants.WARSHIPS.FRIGATE'), 1);

                // If we can build at least one the most expensive warship
                // it means we can start process of building some warships for pirate island
                if ($warshipQtyToBuild !== 0) {
                    // Define the chances of building each type of warship
                    $chances = [
                        1 => 50,  // lugger: 50% chance
                        2 => 25,  // caravel: 25% chance
                        3 => 20,  // galera: 20% chance
                        4 => 5,   // frigate: 5% chance
                        5 => 0    // battleship: 0% chance
                    ];

                    // Calculate a random number between 1 and 100
                    $randomNumber = random_int(1, 100);

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
                        // Find can we build chosen warship
                        $warshipQtyToBuild = $warshipService->hasResourceToBuildWarships($city, config('constants.WARSHIPS.FRIGATE'), 1);

                        if ($warshipQtyToBuild > 0) {
                            // Check if there are enough resources (both gold and population) to create this type of warship
                            $warshipData = WarshipDictionary::find($chosenWarshipId);

                            $warshipQueueService = new WarshipQueueService();
                            $warshipQueueService->orderWarship(1, [
                                'cityId'    => $city->id,
                                'qty'       => 1,
                                'warshipId' => $chosenWarshipId
                            ]);

                            dump("Pirates is building a warship with id: $chosenWarshipId in cityId: $city->id");

                            // Subtract the required amount of each resource from the city for warship
                            $warshipService->subtractResourcesForWarships($city->id, $warshipData, 1);
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
