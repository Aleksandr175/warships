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
        FleetDetail::create([
            'fleet_id' => 1,
            'warship_id' => 1,
            'qty' => 3,
        ]);

        FleetDetail::create([
            'fleet_id' => 1,
            'warship_id' => 2,
            'qty' => 2,
        ]);

        FleetDetail::create([
            'fleet_id' => 1,
            'warship_id' => 3,
            'qty' => 10,
        ]);

        FleetDetail::create([
            'fleet_id' => 2,
            'warship_id' => 1,
            'qty' => 5,
        ]);

        FleetDetail::create([
            'fleet_id' => 2,
            'warship_id' => 3,
            'qty' => 3,
        ]);
    }
}
