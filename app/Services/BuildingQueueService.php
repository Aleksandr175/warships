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

        if (!$hasAllRequirements) {
            return false;
        }

        // found out what resources we need for building
        $requiredResources = BuildingResource::where('building_id', $buildingId)->where('lvl', $this->nextLvl)->get();
        $cityResources     = $city->resources;

        $canBuild = true;
        foreach ($requiredResources as $requiredResource) {
            $hasEnoughRequiredResourceQty = false;
            // Find the corresponding resource in the city resources
            foreach ($cityResources as $cityResource) {
                if ($cityResource->resource_id === $requiredResource->resource_id
                    && $cityResource->qty >= $requiredResource->qty) {
                    $hasEnoughRequiredResourceQty = true;
                    break;
                }
            }

            if (!$hasEnoughRequiredResourceQty) {
                $canBuild = false;

                break;
            }
        }

        return $canBuild;
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

    public function store($userId, BuildRequest $request): CityBuildingQueue
    {
        $data       = $request->only('buildingId', 'cityId');
        $cityId     = $data['cityId'];
        $buildingId = $data['buildingId'];

        $this->userId     = $userId;
        $this->buildingId = $buildingId;

        $this->city = City::where('id', $cityId)->where('user_id', $this->userId)->first();

        if ($this->city && $this->city->id && $this->canBuild($this->city, $buildingId)) {
            return $this->updateQueue($this->city);
        }

        return abort(403);
    }

    public function updateQueue($city): CityBuildingQueue
    {
        $cityResources = $city->resources;

        // found out what resources we need for building
        $requiredResources = BuildingResource::where('building_id', $this->buildingId)->where('lvl', $this->nextLvl)->get();

        // each resource row has time_required for upgrading building
        $timeRequired = $requiredResources[0]->time_required;

        // Subtract the required amount of each resource from the city
        foreach ($requiredResources as $requiredResource) {
            // Find the corresponding resource in the city resources
            $cityResource = $cityResources->where('resource_id', $requiredResource->resource_id)->first();

            // Subtract the required quantity from the city's resource
            $cityResource->qty -= $requiredResource->qty;

            $cityResource->save();
        }

        // TODO: fix this, add resources rows for cancel
        return CityBuildingQueue::create([
            'building_id'   => $this->buildingId,
            'city_id'       => $city->id,
            //'gold'        => $buildingResources->gold,
            //'population'  => $buildingResources->population,
            'lvl'           => $this->nextLvl,
            'time_required' => $timeRequired,
            'deadline'      => Carbon::now()->addSeconds($timeRequired)
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
