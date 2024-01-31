<?php

namespace Database\Seeders;

use App\Models\WarshipResource;
use Illuminate\Database\Seeder;

class WarshipResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.LUGGER'),
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => 100
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.LUGGER'),
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'         => 10
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.LUGGER'),
            'resource_id' => config('constants.RESOURCE_IDS.LOG'),
            'qty'         => 10
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.CARAVEL'),
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => 200
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.CARAVEL'),
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'         => 30
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.CARAVEL'),
            'resource_id' => config('constants.RESOURCE_IDS.LOG'),
            'qty'         => 30
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.GALERA'),
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => 300
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.GALERA'),
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'         => 50
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.FRIGATE'),
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => 1000
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.FRIGATE'),
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'         => 200
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.BATTLESHIP'),
            'resource_id' => config('constants.RESOURCE_IDS.GOLD'),
            'qty'         => 3000
        ]);

        WarshipResource::create([
            'warship_id'  => config('constants.WARSHIPS.BATTLESHIP'),
            'resource_id' => config('constants.RESOURCE_IDS.POPULATION'),
            'qty'         => 500
        ]);
    }
}
