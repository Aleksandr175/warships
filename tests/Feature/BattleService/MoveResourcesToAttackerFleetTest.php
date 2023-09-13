<?php

namespace Tests\Feature\BattleService;

use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\WarshipDictionary;
use App\Services\BattleService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoveResourcesToAttackerFleetTest extends TestCase
{
    public function testMoveResourcesToAttackerFleet()
    {
        // Create instances of Fleet and City as needed for the test
        $fleet = Fleet::create([
            'gold'           => 50,
            'population'     => 30,
            'repeating'      => 1,
            'deadline'       => 0
        ]);

        FleetDetail::create([
            'fleet_id'   => 1,
            'warship_id' => 1,
            'qty'        => 3,
        ]);

        FleetDetail::create([
            'fleet_id'   => 1,
            'warship_id' => 2,
            'qty'        => 2,
        ]);

        FleetDetail::create([
            'fleet_id'   => 1,
            'warship_id' => 3,
            'qty'        => 10,
        ]);

        $warshipsDictionary = WarshipDictionary::get();
        $capacity = 0;
        $fleetDetails = FleetDetail::where('fleet_id', 1)->get();

        foreach ($warshipsDictionary as $warshipDictionary) {
            for ($i = 0, $iMax = count($fleetDetails); $i < $iMax; $i++) {
                if ($fleetDetails[$i]['warship_id'] === $warshipDictionary['id']) {
                    $capacity += $warshipDictionary['capacity'] * $fleetDetails[$i]['qty'];
                    break;
                }
            }
        }

        dump($capacity);

        $city = \App\Models\City::factory(1)->create([
            'id'         => 212,
            'user_id'    => 1,
            'title'      => '',
            'coord_x'    => 1,
            'coord_y'    => 1,
            'gold'       => 2500,
            'population' => 2500
        ]);

        $battleService = new BattleService(); // Replace with the actual class containing the function

        [$takeGold, $takePopulation] = $battleService->moveResourcesToAttackerFleet($fleet, $fleetDetails, $city);

        // Perform assertions to check the expected behavior of the function
        $this->assertEquals($expectedTakeGold, $takeGold);
        $this->assertEquals($expectedTakePopulation, $takePopulation);
        $this->assertEquals($expectedFleetGold, $fleet->gold);
        $this->assertEquals($expectedFleetPopulation, $fleet->population);
        $this->assertEquals($expectedCityGold, $city->gold);
        $this->assertEquals($expectedCityPopulation, $city->population);
    }
}
