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

            $timeToTarget = 10;
            $gold         = 0;
            $speed        = 100;

            // TODO: move warships from city to fleet
            // create fleet and details
            $fleetId = Fleet::create([
                'city_id'        => $city->id,
                'target_city_id' => 10,//$this->targetCity->id,
                'fleet_task_id'  => 3, //attack
                'speed'          => $speed,
                'time'           => $timeToTarget,
                'gold'           => $gold,
                'repeating'      => 0,
                'status_id'      => 1, // TODO: set default value for fleet status id
                'deadline'       => Carbon::now()->addSeconds($timeToTarget)
            ])->id;

            // TODO: send warships in proportions below
            FleetDetail::create([
                'fleet_id'   => $fleetId,
                'warship_id' => 1,
                'qty'        => 3
            ]);

            FleetDetail::create([
                'fleet_id'   => $fleetId,
                'warship_id' => 3,
                'qty'        => 1
            ]);
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
}
