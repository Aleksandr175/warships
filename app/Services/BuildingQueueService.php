<?php

namespace App\Services;

use App\Http\Requests\Api\BuildRequest;
use App\Http\Resources\CityResourcesResource;
use App\Jobs\BuildJob;
use App\Models\BuildingResource;
use App\Models\CityBuildingQueue;
use Carbon\Carbon;

class BuildingQueueService
{
    protected $userId;
    protected $buildingId;
    protected $city;
    protected $nextLvl = 1;

    public function canBuild($city, $buildingId): bool
    {
        $cityBuilding = $city->building($buildingId);

        if ($cityBuilding && $cityBuilding->id) {
            $this->nextLvl = $cityBuilding->lvl + 1;
        }

        // found out what resources we need for building
        $resources = BuildingResource::where('building_id', $buildingId)->where('lvl', $this->nextLvl)->first();

        if ($resources && $resources->id) {
            if ($city->gold >= $resources->gold && $city->population >= $resources->population) {
                return true;
            }
        }

        return false;
    }

    public function store($userId, BuildRequest $request, $city): CityBuildingQueue
    {
        $queue      = null;
        $data       = $request->only('buildingId');
        $buildingId = $data['buildingId'];

        $this->userId     = $userId;
        $this->buildingId = $buildingId;
        $this->city       = $city;

        if ($this->city && $this->city->id && $this->canBuild($this->city, $buildingId)) {
            $queue = $this->updateQueue();

            BuildJob::dispatch([
                'cityId' => $this->city->id,
                'buildingId' => $buildingId,
                'userId' => $this->userId,
                'gold' => $queue->gold,
                'population' => $queue->population,
            ])->delay(now()->addSeconds($queue->time));
        } else {
            return abort(403);
        }

        return $queue;
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
