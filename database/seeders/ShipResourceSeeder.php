<?php

namespace Database\Seeders;

use App\Models\ShipResource;
use Illuminate\Database\Seeder;

class ShipResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShipResource::create([
            'ship_id' => 1,
            'gold' => 100,
            'population' => 10,
            'time' => 10,
        ]);

        ShipResource::create([
            'ship_id' => 2,
            'gold' => 200,
            'population' => 100,
            'time' => 20,
        ]);

        ShipResource::create([
            'ship_id' => 3,
            'gold' => 300,
            'population' => 100,
            'time' => 40,
        ]);

        ShipResource::create([
            'ship_id' => 4,
            'gold' => 1500,
            'population' => 200,
            'time' => 100,
        ]);

        ShipResource::create([
            'ship_id' => 5,
            'gold' => 1000,
            'population' => 500,
            'time' => 1000,
        ]);
    }
}
