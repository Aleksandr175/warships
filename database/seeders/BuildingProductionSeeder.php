<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\BuildingProduction;
use Illuminate\Database\Seeder;

class BuildingProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $buildingMine  = Building::where('id', config('constants.BUILDINGS.MINE'))->first();
        $buildingHouse = Building::where('id', config('constants.BUILDINGS.HOUSES'))->first();

        $mainProductionAllResources = [100, 200, 300, 500, 700, 1000, 1400, 1900, 2500, 3200];
        $productionPopulation       = [30, 50, 80, 120, 160, 200, 250, 300, 360, 440];

        // Define the base quantities and increase factor
        $baseResourceQty = [
            config('constants.BUILDINGS.MINE')   => [
                config('constants.RESOURCE_IDS.GOLD') => [
                    'qty'             => 100,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.2
                ],
                config('constants.RESOURCE_IDS.LOG')  => [
                    'qty'             => 100,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.2
                ],
                config('constants.RESOURCE_IDS.ORE')  => [
                    'qty'             => 100,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.2
                ],
            ],
            config('constants.BUILDINGS.HOUSES') => [
                config('constants.RESOURCE_IDS.POPULATION') => [
                    'qty'             => 50,
                    'start_lvl'       => 1,
                    'increase_factor' => 1.18
                ],
            ],
        ];

        // Define the number of levels for each building
        $buildingLevels = [
            config('constants.BUILDINGS.MINE')   => 40,
            config('constants.BUILDINGS.HOUSES') => 40,
            // Define levels for other buildings
            // ...
        ];

        // Loop through each building and generate resource requirements for each level
        foreach ($buildingLevels as $buildingId => $maxLevel) {
            // Calculate resource requirements for each level
            for ($level = 1; $level <= $maxLevel; $level++) {
                $resources = [];

                // Calculate resource quantities based on the increase factor
                foreach ($baseResourceQty[$buildingId] as $resourceId => $resourceData) {
                    if ($resourceData['start_lvl'] <= $level) {
                        $qty = floor($resourceData['qty'] * pow($resourceData['increase_factor'], $level - 1));

                        // Calculate quantity for the current level
                        $resources[] = [
                            'building_id' => $buildingId,
                            'lvl'         => $level,
                            'resource_id' => $resourceId,
                            'qty'         => $qty
                        ];
                    }
                }

                if ($resources) {
                    // Insert resource requirements into the database
                    BuildingProduction::insert($resources);
                }
            }
        }
    }
}
