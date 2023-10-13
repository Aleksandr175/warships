<?php

namespace Database\Seeders;

use App\Models\FleetTaskDictionary;
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
        $this->call(CityDictionarySeeder::class);

        // pirates id 1
        \App\Models\User::factory(1)->create([
            'id'   => config('constants.DEFAULT_PIRATE_ID'),
            'name' => 'Pirates'
        ]);

        \App\Models\User::factory(3)->create();

        // first player
        \App\Models\User::factory(1)->create([
            'id'       => config('constants.DEFAULT_USER_ID'),
            'name'     => 'Alex',
            'password' => Hash::make('123123'),
            'email'    => 'alex@test.ru'
        ]);

        // second player
        \App\Models\User::factory(1)->create([
            'id'       => config('constants.DEFAULT_USER_ID_2'),
            'name'     => 'Alex2',
            'password' => Hash::make('123123'),
            'email'    => 'alex2@test.ru'
        ]);

        \App\Models\User::factory(10)->create();

        \App\Models\City::factory(1)->create([
            'id'         => config('constants.DEFAULT_USER_CITY_ID'),
            'user_id'    => config('constants.DEFAULT_USER_ID'),
            'title'      => 'My Island',
            'coord_x'    => 1,
            'coord_y'    => 1,
            'gold'       => 2000,
            'population' => 700
        ]);

        \App\Models\City::factory(1)->create([
            'id'         => config('constants.DEFAULT_USER_CITY_ID_2'),
            'user_id'    => config('constants.DEFAULT_USER_ID'),
            'title'      => 'Volcano',
            'coord_x'    => 3,
            'coord_y'    => 5,
            'gold'       => 1500,
            'population' => 300
        ]);

        \App\Models\City::factory(1)->create([
            'id'         => config('constants.DEFAULT_USER_2_CITY_ID'),
            'user_id'    => config('constants.DEFAULT_USER_ID_2'),
            'title'      => 'Island Bla',
            'coord_x'    => 4,
            'coord_y'    => 4,
            'gold'       => 1500,
            'population' => 300
        ]);

        // TODO: Change coordinates
        for ($i = 0; $i < 200; $i++) {
            \App\Models\City::factory(1)->create([
                'user_id'    => random_int(config('constants.DEFAULT_USER_ID_2') + 1, config('constants.DEFAULT_USER_ID_2') + 10),
                'title'      => 'Island',
                'coord_x'    => $i + 5,
                'coord_y'    => $i + 5,
                'gold'       => 500,
                'population' => 300
            ]);
        }

        \App\Models\City::factory(1)->create([
            'user_id'            => config('constants.DEFAULT_PIRATE_ID'),
            'city_dictionary_id' => 2,
            'title'              => 'Pirate Bay',
            'coord_x'            => 2,
            'coord_y'            => 1,
            'gold'               => 2000,
            'population'         => 500
        ]);

        $this->call(BuildingDictionarySeeder::class);

        $this->call(BuildingSeeder::class);

        $this->call(BuildingResourceSeeder::class);

        $this->call(CityBuildingQueueSeeder::class);

        $this->call(BuildingProductionSeeder::class);

        $this->call(ResearchDictionarySeeder::class);

        $this->call(ResearchResourceSeeder::class);

        $this->call(ResearchSeeder::class);

        $this->call(ResearchQueueSeeder::class);

        $this->call(WarshipDictionarySeeder::class);

        $this->call(WarshipSeeder::class);

        $this->call(FleetTaskDictionarySeeder::class);
        $this->call(FleetStatusDictionarySeeder::class);

        $this->call(FleetDetailSeeder::class);
        $this->call(FleetSeeder::class);

        $this->call(BuildingDependencySeeder::class);
        $this->call(ResearchDependencySeeder::class);
        $this->call(WarshipDependencySeeder::class);
    }
}
