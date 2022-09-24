<?php

namespace Database\Seeders;

use App\Models\WarshipDictionary;
use Illuminate\Database\Seeder;

class WarshipDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WarshipDictionary::create([
            'title' => 'Lugger',
            'description' => 'Description for Lugger',
            'attack' => 10,
            'speed' => 10,
            'capacity' => 100,
            'gold' => 100,
            'population' => 10,
            'time' => 10,
            'health' => 100,
        ]);

        WarshipDictionary::create([
            'title' => 'Caravel',
            'description' => 'Description for Caravel',
            'attack' => 20,
            'speed' => 5,
            'capacity' => 500,
            'gold' => 200,
            'population' => 100,
            'time' => 20,
            'health' => 300,
        ]);

        WarshipDictionary::create([
            'title' => 'Galera',
            'description' => 'Description for Galera',
            'attack' => 10,
            'speed' => 8,
            'capacity' => 200,
            'gold' => 300,
            'population' => 100,
            'time' => 40,
            'health' => 200,
        ]);

        WarshipDictionary::create([
            'title' => 'Frigate',
            'description' => 'Description for Frigate',
            'attack' => 10,
            'speed' => 15,
            'capacity' => 1000,
            'gold' => 1500,
            'population' => 200,
            'time' => 100,
            'health' => 1000,
        ]);

        WarshipDictionary::create([
            'title' => 'Battleship',
            'description' => 'Description for Battleship',
            'attack' => 100,
            'speed' => 5,
            'capacity' => 1000,
            'gold' => 1000,
            'population' => 500,
            'time' => 1000,
            'health' => 5000,
        ]);
    }
}
