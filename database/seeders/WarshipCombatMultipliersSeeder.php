<?php

namespace Database\Seeders;

use App\Models\WarshipCombatMultiplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarshipCombatMultipliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $multipliers = [
            ['warship_attacker_id' => config('constants.WARSHIPS.LUGGER'), 'warship_defender_id' => config('constants.WARSHIPS.CARAVEL'), 'multiplier' => 2.0],

            ['warship_attacker_id' => config('constants.WARSHIPS.GALERA'), 'warship_defender_id' => config('constants.WARSHIPS.LUGGER'), 'multiplier' => 2.0],
            ['warship_attacker_id' => config('constants.WARSHIPS.GALERA'), 'warship_defender_id' => config('constants.WARSHIPS.CARAVEL'), 'multiplier' => 3.0],

            ['warship_attacker_id' => config('constants.WARSHIPS.FRIGATE'), 'warship_defender_id' => config('constants.WARSHIPS.LUGGER'), 'multiplier' => 3.0],
            ['warship_attacker_id' => config('constants.WARSHIPS.FRIGATE'), 'warship_defender_id' => config('constants.WARSHIPS.CARAVEL'), 'multiplier' => 2.0],
            ['warship_attacker_id' => config('constants.WARSHIPS.FRIGATE'), 'warship_defender_id' => config('constants.WARSHIPS.GALERA'), 'multiplier' => 2.0],
            ['warship_attacker_id' => config('constants.WARSHIPS.FRIGATE'), 'warship_defender_id' => config('constants.WARSHIPS.BATTLESHIP'), 'multiplier' => 2.0],

            ['warship_attacker_id' => config('constants.WARSHIPS.FRIGATE'), 'warship_defender_id' => config('constants.WARSHIPS.LUGGER'), 'multiplier' => 10.0],
            ['warship_attacker_id' => config('constants.WARSHIPS.FRIGATE'), 'warship_defender_id' => config('constants.WARSHIPS.CARAVEL'), 'multiplier' => 5.0],
            ['warship_attacker_id' => config('constants.WARSHIPS.FRIGATE'), 'warship_defender_id' => config('constants.WARSHIPS.GALERA'), 'multiplier' => 5.0],
        ];

        foreach ($multipliers as $multiplier) {
            WarshipCombatMultiplier::create($multiplier);
        }
    }
}
