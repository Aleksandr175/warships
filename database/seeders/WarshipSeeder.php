<?php

namespace Database\Seeders;

use App\Models\Warship;
use Illuminate\Database\Seeder;

class WarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warship::create([
            'warship_id' => 1,
            'city_id' => 10,
            'user_id' => 5,
            'qty' => 3
        ]);

        Warship::create([
            'warship_id' => 2,
            'city_id' => 10,
            'user_id' => 5,
            'qty' => 2
        ]);

        Warship::create([
            'warship_id' => 3,
            'city_id' => 10,
            'user_id' => 5,
            'qty' => 1
        ]);
    }
}
