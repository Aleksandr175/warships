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

        \App\Models\Archipelago::create([
            'id'   => 1,
            'type' => 'usual'
        ]);

        \App\Models\City::factory(1)->create([
            'id'             => config('constants.DEFAULT_USER_CITY_ID'),
            'user_id'        => config('constants.DEFAULT_USER_ID'),
            'title'          => 'My Island',
            'archipelago_id' => 1,
            'coord_x'        => 3,
            'coord_y'        => 3,
            'gold'           => 2000,
            'population'     => 700
        ]);

        \App\Models\City::factory(1)->create([
            'id'                 => config('constants.DEFAULT_USER_CITY_ID_2'),
            'user_id'            => config('constants.DEFAULT_USER_ID'),
            'title'              => 'Volcano',
            'archipelago_id'     => 1,
            'coord_x'            => 3,
            'coord_y'            => 5,
            'gold'               => 1500,
            'population'         => 300,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.COLONY')
        ]);


        \App\Models\Archipelago::create([
            'id'   => 2,
            'type' => 'usual'
        ]);

        \App\Models\City::factory(1)->create([
            'id'             => config('constants.DEFAULT_USER_2_CITY_ID'),
            'user_id'        => config('constants.DEFAULT_USER_ID_2'),
            'title'          => 'Island Bla',
            'archipelago_id' => 2,
            'coord_x'        => 4,
            'coord_y'        => 4,
            'gold'           => 1500,
            'population'     => 300
        ]);

        // TODO: Change coordinates
        for ($i = 0; $i < 150; $i++) {
            $archipelago = \App\Models\Archipelago::create([
                'type' => 'usual'
            ]);

            \App\Models\City::factory(1)->create([
                'user_id'        => random_int(config('constants.DEFAULT_USER_ID_2') + 1, config('constants.DEFAULT_USER_ID_2') + 10),
                'title'          => 'Island',
                'archipelago_id' => $archipelago->id,
                'coord_x'        => 3,
                'coord_y'        => 3,
                'gold'           => 500,
                'population'     => 300
            ]);
        }

        \App\Models\City::factory(1)->create([
            'id'                 => config('constants.DEFAULT_PIRATE_CITY_ID'),
            'user_id'            => config('constants.DEFAULT_PIRATE_ID'),
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.PIRATE_BAY'),
            'title'              => 'Pirate Bay',
            'appearance_id'      => 2,
            'archipelago_id'     => 1,
            'coord_x'            => 2,
            'coord_y'            => 1,
            'gold'               => 2000,
            'population'         => 500
        ]);

        // Colonies for test

        \App\Models\City::factory(1)->create([
            'user_id'            => null,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.COLONY'),
            'title'              => 'Colony',
            'archipelago_id'     => 1,
            'coord_x'            => 4,
            'coord_y'            => 2,
            'appearance_id'      => 3,
            'gold'               => 2000,
            'population'         => 700
        ]);

        \App\Models\City::factory(1)->create([
            'user_id'            => null,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.COLONY'),
            'title'              => 'Colony',
            'archipelago_id'     => 1,
            'coord_x'            => 5,
            'coord_y'            => 3,
            'appearance_id'      => 3,
            'gold'               => 2000,
            'population'         => 700
        ]);

        \App\Models\City::factory(1)->create([
            'user_id'            => null,
            'city_dictionary_id' => config('constants.CITY_TYPE_ID.COLONY'),
            'title'              => 'Colony',
            'archipelago_id'     => 1,
            'coord_x'            => 1,
            'coord_y'            => 4,
            'appearance_id'      => 4,
            'gold'               => 2000,
            'population'         => 700
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

        $this->call(MessageSeeder::class);
    }
}
