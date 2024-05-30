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
            'id'          => config('constants.WARSHIPS.LUGGER'),
            'title'       => 'Lugger',
            'description' => 'A swift, cost-effective warship with modest HP and attack capabilities, designed for agility and economy. A swift, cost-effective warship with modest HP and attack capabilities, designed for agility and economy.',
            'attack'      => 50,
            'speed'       => 10,
            'capacity'    => 100,
            'time'        => (100 + 10) / 10,
            'health'      => 100,
        ]);

        WarshipDictionary::create([
            'id'          => config('constants.WARSHIPS.CARAVEL'),
            'title'       => 'Caravel',
            'description' => 'A capacious, versatile warship, ideal for trading and resource transportation across islands in your maritime domain.',
            'attack'      => 70,
            'speed'       => 5,
            'capacity'    => 500,
            'time'        => (200 + 30) / 10,
            'health'      => 300,
        ]);

        WarshipDictionary::create([
            'id'          => config('constants.WARSHIPS.GALERA'),
            'title'       => 'Galera',
            'description' => 'A swift warship tailored for rapid island assaults and lightning-fast strikes on enemy territories.',
            'attack'      => 150,
            'speed'       => 12,
            'capacity'    => 200,
            'time'        => (300 + 50) / 10,
            'health'      => 200,
        ]);

        WarshipDictionary::create([
            'id'          => config('constants.WARSHIPS.FRIGATE'),
            'title'       => 'Frigate',
            'description' => 'A versatile, formidable warship boasting exceptional speed and attack capabilities, the optimal choice for any mission.',
            'attack'      => 300,
            'speed'       => 8,
            'capacity'    => 300,
            'time'        => (1000 + 200) / 10,
            'health'      => 1000,
        ]);

        WarshipDictionary::create([
            'id'          => config('constants.WARSHIPS.BATTLESHIP'),
            'title'       => 'Battleship',
            'description' => 'A colossal warship, renowned for its overwhelming firepower and impervious defenses, though its speed is sluggish.',
            'attack'      => 700,
            'speed'       => 5,
            'capacity'    => 1000,
            'time'        => (3000 + 500) / 10,
            'health'      => 5000,
        ]);
    }
}
