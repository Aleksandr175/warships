<?php

namespace Tests\Feature\BattleService;

use App\Models\City;
use App\Models\CityDictionary;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\WarshipDictionary;
use App\Services\BattleService;
use Carbon\Carbon;
use Database\Seeders\WarshipDictionarySeeder;
use Tests\TestCase;

class BattleServiceTest extends TestCase
{

    public function prepareData()
    {
        CityDictionary::create([
            'title'       => 'Island',
            'description' => 'Island of player'
        ]);

        Fleet::create([
            'city_id'        => 1,
            'target_city_id' => 212,
            'fleet_task_id'  => 3,
            'gold'           => 50,
            'population'     => 30,
            'repeating'      => 1,
            'status_id'      => 1,
            'deadline'       => Carbon::now()
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

        $warshipsDictionarySeeder = new WarshipDictionarySeeder();
        $warshipsDictionarySeeder->run();
    }

    public function testPopulateFleetDetailsWithCapacityAndHealth()
    {
        $this->prepareData();

        $fleetDetails = FleetDetail::where('fleet_id', 1)->get();

        $battleService      = new BattleService();
        $warshipsDictionary = WarshipDictionary::get();

        $fleetDetails = $battleService->populateFleetDetailsWithCapacityAndHealth($fleetDetails, $warshipsDictionary);

        $this->assertEquals(100, $fleetDetails[0]->capacity);
        $this->assertEquals(500, $fleetDetails[1]->capacity);
        $this->assertEquals(200, $fleetDetails[2]->capacity);
    }

    public function testGetAvailableCapacity()
    {
        $this->prepareData();

        $fleet = Fleet::where('city_id', 1)->where('target_city_id', 212)->first();

        $warshipsDictionary = WarshipDictionary::get();
        $fleetDetails       = FleetDetail::where('fleet_id', 1)->get();

        $battleService = new BattleService();

        $fleetDetails = $battleService->populateFleetDetailsWithCapacityAndHealth($fleetDetails, $warshipsDictionary);

        $availableCapacity = $battleService->getAvailableCapacity($fleet, $fleetDetails);

        $this->assertEquals(3220, $availableCapacity);
    }

    public function testMoveResourcesToAttackerFleet()
    {
        $this->prepareData();

        City::factory(1)->create([
            'id'         => 212,
            'user_id'    => 1,
            'title'      => '',
            'coord_x'    => 1,
            'coord_y'    => 1,
            'gold'       => 3000,
            'population' => 2000
        ]);

        $city  = City::where('id', 212)->first();
        $fleet = Fleet::where('city_id', 1)->where('target_city_id', 212)->first();

        $warshipsDictionary = WarshipDictionary::get();
        $fleetDetails       = FleetDetail::where('fleet_id', 1)->get();

        $battleService = new BattleService();

        $fleetDetails = $battleService->populateFleetDetailsWithCapacityAndHealth($fleetDetails, $warshipsDictionary);

        $availableCapacity = $battleService->getAvailableCapacity($fleet, $fleetDetails);

        $this->assertEquals(3220, $availableCapacity);

        [$takeGold, $takePopulation] = $battleService->moveResourcesToAttackerFleet($fleet, $fleetDetails, $city);

        $this->assertEquals(1500, $takeGold);
        $this->assertEquals(1000, $takePopulation);
        $this->assertEquals(1500 + 50, $fleet->gold);
        $this->assertEquals(1000 + 30, $fleet->population);


        $city->update([
            'gold'       => 1000,
            'population' => 1000
        ]);

        $fleet->update([
            'gold' => 50,
            'population' => 30
        ]);

        $availableCapacity = $battleService->getAvailableCapacity($fleet, $fleetDetails);

        $this->assertEquals(3220, $availableCapacity);

        [$takeGold, $takePopulation] = $battleService->moveResourcesToAttackerFleet($fleet, $fleetDetails, $city);

        $this->assertEquals(500, $takeGold);
        $this->assertEquals(500, $takePopulation);
        $this->assertEquals(500 + 50, $fleet->gold);
        $this->assertEquals(500 + 30, $fleet->population);


        $city->update([
            'gold'       => 0,
            'population' => 0
        ]);

        $fleet->update([
            'gold' => 50,
            'population' => 30
        ]);

        $availableCapacity = $battleService->getAvailableCapacity($fleet, $fleetDetails);

        $this->assertEquals(3220, $availableCapacity);

        [$takeGold, $takePopulation] = $battleService->moveResourcesToAttackerFleet($fleet, $fleetDetails, $city);

        $this->assertEquals(0, $takeGold);
        $this->assertEquals(0, $takePopulation);
        $this->assertEquals(50, $fleet->gold);
        $this->assertEquals(30, $fleet->population);


        $city->update([
            'gold'       => 500,
            'population' => 50000
        ]);

        $fleet->update([
            'gold' => 50,
            'population' => 30
        ]);

        $availableCapacity = $battleService->getAvailableCapacity($fleet, $fleetDetails);

        $this->assertEquals(3220, $availableCapacity);

        [$takeGold, $takePopulation] = $battleService->moveResourcesToAttackerFleet($fleet, $fleetDetails, $city);

        $this->assertEquals(250, $takeGold);
        $this->assertEquals(3220 - 250, $takePopulation);
        $this->assertEquals(250 + 50, $fleet->gold);
        $this->assertEquals(3220 - 250 + 30, $fleet->population);
    }
}
