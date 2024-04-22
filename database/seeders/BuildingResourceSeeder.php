<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\BuildingDictionary;
use App\Models\BuildingResource;
use Illuminate\Database\Seeder;

class BuildingResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $building4 = BuildingDictionary::where('id', config('constants.BUILDINGS.TAVERN'))->first();
        $building5 = BuildingDictionary::where('id', config('constants.BUILDINGS.FARM'))->first();
        $building7 = BuildingDictionary::where('id', config('constants.BUILDINGS.DOCK'))->first();


        // Define the base quantities and increase factor
        $baseResourceQty = [
            config('constants.BUILDINGS.MAIN')     => [
                config('constants.RESOURCE_IDS.GOLD')       => [
                    'qty'             => 200,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.4 // Factor by which to increase quantities for each level
                ],
                config('constants.RESOURCE_IDS.POPULATION') => [
                    'qty'             => 100,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.3
                ],
                config('constants.RESOURCE_IDS.LOG')        => [
                    'qty'             => 10,
                    'start_lvl'       => 5,
                    'increase_factor' => 1.27
                ],
                config('constants.RESOURCE_IDS.PLANK')      => [
                    'qty'             => 5,
                    'start_lvl'       => 8,
                    'increase_factor' => 1.25
                ],
                config('constants.RESOURCE_IDS.LUMBER')     => [
                    'qty'             => 2,
                    'start_lvl'       => 11,
                    'increase_factor' => 1.2
                ],
                config('constants.RESOURCE_IDS.ORE')        => [
                    'qty'             => 8,
                    'start_lvl'       => 8,
                    'increase_factor' => 1.27
                ],
                config('constants.RESOURCE_IDS.IRON')       => [
                    'qty'             => 4,
                    'start_lvl'       => 14,
                    'increase_factor' => 1.25
                ],
                config('constants.RESOURCE_IDS.STEEL')      => [
                    'qty'             => 1,
                    'start_lvl'       => 17,
                    'increase_factor' => 1.2
                ],
            ],
            config('constants.BUILDINGS.MINE')     => [
                config('constants.RESOURCE_IDS.GOLD')       => [
                    'qty'             => 300,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.4 // Factor by which to increase quantities for each level
                ],
                config('constants.RESOURCE_IDS.POPULATION') => [
                    'qty'             => 100,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.3
                ],
                config('constants.RESOURCE_IDS.LOG')        => [
                    'qty'             => 5,
                    'start_lvl'       => 4,
                    'increase_factor' => 1.27
                ],
                config('constants.RESOURCE_IDS.PLANK')      => [
                    'qty'             => 4,
                    'start_lvl'       => 6,
                    'increase_factor' => 1.25
                ],
                config('constants.RESOURCE_IDS.LUMBER')     => [
                    'qty'             => 3,
                    'start_lvl'       => 10,
                    'increase_factor' => 1.2
                ],
                config('constants.RESOURCE_IDS.ORE')        => [
                    'qty'             => 5,
                    'start_lvl'       => 8,
                    'increase_factor' => 1.27
                ],
                config('constants.RESOURCE_IDS.IRON')       => [
                    'qty'             => 4,
                    'start_lvl'       => 13,
                    'increase_factor' => 1.25
                ],
                config('constants.RESOURCE_IDS.STEEL')      => [
                    'qty'             => 3,
                    'start_lvl'       => 19,
                    'increase_factor' => 1.2
                ],
            ],
            config('constants.BUILDINGS.HOUSES')   => [
                config('constants.RESOURCE_IDS.GOLD')   => [
                    'qty'             => 150,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.4 // Factor by which to increase quantities for each level
                ],
                config('constants.RESOURCE_IDS.LOG')    => [
                    'qty'             => 5,
                    'start_lvl'       => 4,
                    'increase_factor' => 1.27
                ],
                config('constants.RESOURCE_IDS.PLANK')  => [
                    'qty'             => 2,
                    'start_lvl'       => 10,
                    'increase_factor' => 1.25
                ],
                config('constants.RESOURCE_IDS.LUMBER') => [
                    'qty'             => 1,
                    'start_lvl'       => 15,
                    'increase_factor' => 1.2
                ],
                config('constants.RESOURCE_IDS.ORE')    => [
                    'qty'             => 3,
                    'start_lvl'       => 13,
                    'increase_factor' => 1.27
                ],
                config('constants.RESOURCE_IDS.IRON')   => [
                    'qty'             => 1,
                    'start_lvl'       => 20,
                    'increase_factor' => 1.25
                ],
            ],
            config('constants.BUILDINGS.FORTRESS') => [
                config('constants.RESOURCE_IDS.GOLD')       => [
                    'qty'             => 1000,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.4
                ],
                config('constants.RESOURCE_IDS.POPULATION') => [
                    'qty'             => 100,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.45
                ],
                config('constants.RESOURCE_IDS.LOG')        => [
                    'qty'             => 100,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.5
                ],
                config('constants.RESOURCE_IDS.PLANK')      => [
                    'qty'             => 20,
                    'start_lvl'       => 3,
                    'increase_factor' => 1.25
                ],
                config('constants.RESOURCE_IDS.LUMBER')     => [
                    'qty'             => 10,
                    'start_lvl'       => 8,
                    'increase_factor' => 1.5
                ],
                config('constants.RESOURCE_IDS.ORE')        => [
                    'qty'             => 50,
                    'start_lvl'       => 3,
                    'increase_factor' => 1.5
                ],
                config('constants.RESOURCE_IDS.IRON')       => [
                    'qty'             => 20,
                    'start_lvl'       => 5,
                    'increase_factor' => 1.5
                ],
                config('constants.RESOURCE_IDS.STEEL')      => [
                    'qty'             => 20,
                    'start_lvl'       => 12,
                    'increase_factor' => 1.5
                ],
            ],
            config('constants.BUILDINGS.SHIPYARD') => [
                config('constants.RESOURCE_IDS.GOLD')       => [
                    'qty'             => 500,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.4
                ],
                config('constants.RESOURCE_IDS.POPULATION') => [
                    'qty'             => 300,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.4
                ],
                config('constants.RESOURCE_IDS.LOG')        => [
                    'qty'             => 10,
                    'start_lvl'       => 4,
                    'increase_factor' => 1.27
                ],
                config('constants.RESOURCE_IDS.PLANK')      => [
                    'qty'             => 10,
                    'start_lvl'       => 10,
                    'increase_factor' => 1.25
                ],
                config('constants.RESOURCE_IDS.LUMBER')     => [
                    'qty'             => 4,
                    'start_lvl'       => 15,
                    'increase_factor' => 1.2
                ],
                config('constants.RESOURCE_IDS.ORE')        => [
                    'qty'             => 7,
                    'start_lvl'       => 13,
                    'increase_factor' => 1.27
                ],
                config('constants.RESOURCE_IDS.IRON')       => [
                    'qty'             => 3,
                    'start_lvl'       => 18,
                    'increase_factor' => 1.25
                ],
            ],
            config('constants.BUILDINGS.WORKSHOP') => [
                config('constants.RESOURCE_IDS.GOLD')       => [
                    'qty'             => 500,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.5
                ],
                config('constants.RESOURCE_IDS.POPULATION') => [
                    'qty'             => 300,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.5
                ],
                config('constants.RESOURCE_IDS.LOG')        => [
                    'qty'             => 100,
                    'start_lvl'       => 3,
                    'increase_factor' => 1.3
                ],
                config('constants.RESOURCE_IDS.PLANK')      => [
                    'qty'             => 50,
                    'start_lvl'       => 4,
                    'increase_factor' => 1.5
                ],
                config('constants.RESOURCE_IDS.LUMBER')     => [
                    'qty'             => 4,
                    'start_lvl'       => 5,
                    'increase_factor' => 1.5
                ],
                config('constants.RESOURCE_IDS.ORE')        => [
                    'qty'             => 300,
                    'start_lvl'       => 4,
                    'increase_factor' => 1.5
                ],
                config('constants.RESOURCE_IDS.IRON')       => [
                    'qty'             => 300,
                    'start_lvl'       => 5,
                    'increase_factor' => 1.5
                ],
            ],
        ];

        // Define the number of levels for each building
        $buildingLevels = [
            config('constants.BUILDINGS.MAIN')     => 45,
            config('constants.BUILDINGS.MINE')     => 40,
            config('constants.BUILDINGS.HOUSES')   => 40,
            config('constants.BUILDINGS.FORTRESS') => 15,
            config('constants.BUILDINGS.SHIPYARD') => 20,
            config('constants.BUILDINGS.WORKSHOP') => 6,
            // Define levels for other buildings
            // ...
        ];

        // Loop through each building and generate resource requirements for each level
        foreach ($buildingLevels as $buildingId => $maxLevel) {
            // Calculate resource requirements for each level
            for ($level = 1; $level <= $maxLevel; $level++) {
                $resources = [];

                // Calculate time for building
                $qty = 0;
                foreach ($baseResourceQty[$buildingId] as $resourceId => $resourceData) {
                    if ($resourceData['start_lvl'] <= $level) {
                        // Calculate quantity for the current level
                        $qty += floor($resourceData['qty'] * pow($resourceData['increase_factor'], $level - 1));
                    }
                }

                $requiredTime = floor($qty / 10);

                // Calculate resource quantities based on the increase factor
                foreach ($baseResourceQty[$buildingId] as $resourceId => $resourceData) {
                    if ($resourceData['start_lvl'] <= $level) {
                        $qty = floor($resourceData['qty'] * pow($resourceData['increase_factor'], $level - 1));

                        // Calculate quantity for the current level
                        $resources[] = [
                            'building_id'   => $buildingId, // Building ID
                            'lvl'           => $level, // Level
                            'resource_id'   => $resourceId, // Resource ID
                            'qty'           => $qty, // Quantity
                            'time_required' => $requiredTime
                        ];
                    }
                }

                if ($resources) {
                    // Insert resource requirements into the database
                    BuildingResource::insert($resources);
                }
            }
        }

        BuildingResource::create([
            'building_id'   => $building4->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 10,
        ]);


        BuildingResource::create([
            'building_id'   => $building5->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 100,
            'lvl'           => 1,
            'time_required' => 10,
        ]);

        BuildingResource::create([
            'building_id'   => $building7->id,
            'resource_id'   => config('constants.RESOURCE_IDS.GOLD'),
            'qty'           => 2000,
            'lvl'           => 1,
            'time_required' => 200,
        ]);
    }
}
