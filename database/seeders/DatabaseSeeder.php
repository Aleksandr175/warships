<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(4)->create();

        \App\Models\User::factory(1)->create([
            'name' => 'Alex',
            'password' => Hash::make('123123'),
            'email' => 'alex@test.ru'
        ]);

        \App\Models\User::factory(10)->create();

        \App\Models\City::factory(1)->create([
            'id' => 10,
            'user_id' => 5,
            'title' => 'Остров Alex-a',
            'coord_x' => 1,
            'coord_y' => 1,
            'gold' => 1000,
            'population' => 200
        ]);

        \App\Models\City::factory(1)->create([
            'id' => 11,
            'user_id' => 5,
            'title' => 'Остров Alex-a N2',
            'coord_x' => 12,
            'coord_y' => 5,
            'gold' => 1500,
            'population' => 300
        ]);

        $this->call(BuildingDictionarySeeder::class);

        $this->call(BuildingSeeder::class);

        $this->call(BuildingResourceSeeder::class);

        $this->call(CityBuildingQueueSeeder::class);

        $this->call(BuildingProductionSeeder::class);
    }
}
