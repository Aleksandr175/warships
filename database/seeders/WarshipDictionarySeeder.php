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
            'id' => config('constants.WARSHIPS.LUGGER'),
            'title' => 'Lugger',
            'description' => 'Description for Lugger',
            'attack' => 50,
            'speed' => 10,
            'capacity' => 100,
            'gold' => 100,
            'population' => 10,
            'time' => (100 + 10) / 10,
            'health' => 100,
        ]);

        WarshipDictionary::create([
            'id' => config('constants.WARSHIPS.CARAVEL'),
            'title' => 'Caravel',
            'description' => 'Description for Caravel',
            'attack' => 70,
            'speed' => 5,
            'capacity' => 500,
            'gold' => 200,
            'population' => 30,
            'time' => (200 + 30) / 10,
            'health' => 300,
        ]);

        WarshipDictionary::create([
            'id' => config('constants.WARSHIPS.GALERA'),
            'title' => 'Galera',
            'description' => 'Description for Galera',
            'attack' => 150,
            'speed' => 8,
            'capacity' => 200,
            'gold' => 300,
            'population' => 50,
            'time' => (300 + 50) / 10,
            'health' => 200,
        ]);

        WarshipDictionary::create([
            'id' => config('constants.WARSHIPS.FRIGATE'),
            'title' => 'Frigate',
            'description' => 'Description for Frigate',
            'attack' => 300,
            'speed' => 15,
            'capacity' => 300,
            'gold' => 1000,
            'population' => 200,
            'time' => (1000 + 200) / 10,
            'health' => 1000,
        ]);

        WarshipDictionary::create([
            'id' => config('constants.WARSHIPS.BATTLESHIP'),
            'title' => 'Battleship',
            'description' => 'Description for Battleship',
            'attack' => 700,
            'speed' => 5,
            'capacity' => 1000,
            'gold' => 3000,
            'population' => 500,
            'time' => (3000 + 500) / 10,
            'health' => 5000,
        ]);
    }
}
