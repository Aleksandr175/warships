<?php

namespace App\Services;

use App\Http\Requests\Api\BuildRequest;
use App\Models\BuildingDependency;
use App\Models\BuildingProduction;
use App\Models\BuildingResource;
use App\Models\City;
use App\Models\CityBuildingQueue;
use App\Models\Research;
use Carbon\Carbon;

class BuildingQueueService
{
    protected $userId;
    protected $buildingId;
    protected $city;
    protected $nextLvl = 1;

    public function handle(CityBuildingQueue $buildingQueue): void
    {
        $cityId     = $buildingQueue['city_id'];
        $buildingId = $buildingQueue['building_id'];

        $city = City::find($cityId);

        // add lvl
        if ($city->building($buildingId)) {
            $city->building($buildingId)->increment('lvl');
        } else {
            // create new building
            $city->buildings()->create([
                'building_id' => $buildingId,
                'city_id'     => $cityId,
                'lvl'         => 1,
            ]);
        }

        if ($buildingId === config('constants.BUILDINGS.HOUSES')) {
            $additionalPopulation = BuildingProduction::where('lvl', $buildingQueue->lvl)->where('resource', 'population')->first();

            $city->increment('population', $additionalPopulation->qty);
        }

        $city->buildingQueue()->delete();
    }

    public function canBuild($city, $buildingId): bool
    {
        $cityBuilding = $city->building($buildingId);

        if ($cityBuilding && $cityBuilding->id) {
            $this->nextLvl = $cityBuilding->lvl + 1;
        } else {
            $this->nextLvl = 1;
        }

        $hasAllRequirements = $this->hasAllRequirements($city, $buildingId, $this->nextLvl);
        $hasEnoughResources = false;

        // found out what resources we need for building
        $resources = BuildingResource::where('building_id', $buildingId)->where('lvl', $this->nextLvl)->first();

        if ($resources && $resources->id) {
            if ($city->gold >= $resources->gold && $city->population >= $resources->population) {
                $hasEnoughResources = true;
            }
        }

        return $hasEnoughResources && $hasAllRequirements;
    }

    public function hasAllRequirements($city, $buildingId, $nextLvl): bool
    {
        $requirements       = BuildingDependency::where('building_id', $buildingId)
            ->where('building_lvl', $nextLvl)
            ->where('required_entity', 'building')
            ->get();
        $hasAllRequirements = true;

        $researches = Research::where('user_id', $this->userId)->get();

        if ($requirements) {
            $cityBuildings = $city->buildings;

            foreach ($requirements as $requirement) {
                $hasRequirement = false;

                if ($requirement->required_entity === 'building') {
                    foreach ($cityBuildings as $cityBuilding) {
                        if (($requirement->required_entity_id === $cityBuilding->building_id) && $requirement->required_entity_lvl <= $cityBuilding->lvl) {
                            $hasRequirement = true;
                        }
                    }
                }

                if ($requirement->required_entity === 'research') {
                    foreach ($researches as $research) {
                        if (($requirement->required_entity_id === $research->id) && $requirement->required_entity_lvl <= $research->lvl) {
                            $hasRequirement = true;
                        }
                    }
                }

                if (!$hasRequirement) {
                    $hasAllRequirements = false;
                }
            }
        }

        return $hasAllRequirements;
    }

    public function store($userId, BuildRequest $request, $city): CityBuildingQueue
    {
        $data       = $request->only('buildingId');
        $buildingId = $data['buildingId'];

        $this->userId     = $userId;
        $this->buildingId = $buildingId;
        $this->city       = $city;

        if ($this->city && $this->city->id && $this->canBuild($this->city, $buildingId)) {
            return $this->updateQueue();
        }

        return abort(403);
    }

    public function updateQueue(): CityBuildingQueue
    {
        // found out what resources we need for building
        $buildingResources = BuildingResource::where('building_id', $this->buildingId)->where('lvl', $this->nextLvl)->first();

        $time = $buildingResources->time;

        // take resources from city
        $this->city->update([
            'gold'       => $this->city->gold - $buildingResources->gold,
            'population' => $this->city->population - $buildingResources->population
        ]);

        return CityBuildingQueue::create([
            'building_id' => $this->buildingId,
            'city_id'     => $this->city->id,
            'gold'        => $buildingResources->gold,
            'population'  => $buildingResources->population,
            'lvl'         => $this->nextLvl,
            'time'        => $time,
            'deadline'    => Carbon::now()->addSeconds($time)
        ]);
    }

    public function cancel($city): void
    {
        if ($city && $city->id) {
            $buildingQueue = $city->buildingQueue;

            if ($buildingQueue && $buildingQueue->id) {
                // update resource
                $city->update([
                    'gold'       => $city->gold + $buildingQueue->gold,
                    'population' => $city->population + $buildingQueue->population,
                ]);

                $city->buildingQueue()->delete();
            }
        }
    }
}
