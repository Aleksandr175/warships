<?php

namespace Database\Seeders;

use App\Models\ShipDictionary;
use Illuminate\Database\Seeder;

class ShipDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShipDictionary::create([
            'title' => 'Ship 1',
            'description' => 'Description ship 1',
            'attack' => 10,
            'speed' => 10,
            'capacity' => 100,
            'gold' => 100,
            'population' => 10,
            'time' => 10,
        ]);

        ShipDictionary::create([
            'title' => 'Ship 2',
            'description' => 'Description ship 2',
            'attack' => 20,
            'speed' => 5,
            'capacity' => 200,
            'gold' => 200,
            'population' => 100,
            'time' => 20,
        ]);

        ShipDictionary::create([
            'title' => 'Ship 3',
            'description' => 'Description ship 3',
            'attack' => 10,
            'speed' => 8,
            'capacity' => 500,
            'gold' => 300,
            'population' => 100,
            'time' => 40,
        ]);

        ShipDictionary::create([
            'title' => 'Ship 4',
            'description' => 'Description ship 4',
            'attack' => 10,
            'speed' => 15,
            'capacity' => 1000,
            'gold' => 1500,
            'population' => 200,
            'time' => 100,
        ]);

        ShipDictionary::create([
            'title' => 'Ship 5',
            'description' => 'Description ship 5',
            'attack' => 100,
            'speed' => 5,
            'capacity' => 1000,
            'gold' => 1000,
            'population' => 500,
            'time' => 1000,
        ]);
    }
}
