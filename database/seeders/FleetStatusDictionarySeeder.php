<?php

namespace Database\Seeders;

use App\Models\FleetStatusDictionary;
use Illuminate\Database\Seeder;

class FleetStatusDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FleetStatusDictionary::create([
            'title'       => 'On way',
            'description' => 'On way to target'
        ]);

        FleetStatusDictionary::create([
            'title'       => 'Trading',
            'description' => 'Trading'
        ]);

        FleetStatusDictionary::create([
            'title'       => 'On way back',
            'description' => 'On way back'
        ]);

        FleetStatusDictionary::create([
            'title'       => 'Attack',
            'description' => 'Attack in progress'
        ]);

        FleetStatusDictionary::create([
            'id'          => config('constants.FLEET_STATUSES.EXPEDITION_GOING_TO_TARGET'),
            'title'       => 'Expedition',
            'description' => 'Expedition is going'
        ]);

        FleetStatusDictionary::create([
            'id'          => config('constants.FLEET_STATUSES.EXPEDITION_IN_PROGRESS'),
            'title'       => 'Expedition',
            'description' => 'Expedition in progress'
        ]);

        FleetStatusDictionary::create([
            'id'          => config('constants.FLEET_STATUSES.EXPEDITION_GOING_BACK'),
            'title'       => 'Expedition',
            'description' => 'Expedition is going back'
        ]);
    }
}
