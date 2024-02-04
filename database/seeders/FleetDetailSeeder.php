<?php

namespace Database\Seeders;

use App\Models\FleetDetail;
use Illuminate\Database\Seeder;

class FleetDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tradeFleetId = 1;

        FleetDetail::create([
            'fleet_id'   => $tradeFleetId,
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'qty'        => 3,
        ]);

        FleetDetail::create([
            'fleet_id'   => $tradeFleetId,
            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
            'qty'        => 2,
        ]);

        FleetDetail::create([
            'fleet_id'   => $tradeFleetId,
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'qty'        => 10,
        ]);

        FleetDetail::create([
            'fleet_id'   => 2,
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'qty'        => 5,
        ]);

        FleetDetail::create([
            'fleet_id'   => 2,
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'qty'        => 3,
        ]);

        FleetDetail::create([
            'fleet_id'   => 3,
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'qty'        => 5,
        ]);

        // fleet details for attack
        FleetDetail::create([
            'fleet_id'   => 4,
            'warship_id' => config('constants.WARSHIPS.LUGGER'),
            'qty'        => 10,
        ]);
        FleetDetail::create([
            'fleet_id'   => 4,
            'warship_id' => config('constants.WARSHIPS.CARAVEL'),
            'qty'        => 5,
        ]);
        FleetDetail::create([
            'fleet_id'   => 4,
            'warship_id' => config('constants.WARSHIPS.GALERA'),
            'qty'        => 2,
        ]);
    }
}
