<?php

namespace App\Services;

use App\Http\Requests\Api\WarshipCreateRequest;
use App\Models\City;
use App\Models\Research;
use App\Models\WarshipDependency;
use App\Models\WarshipDictionary;
use App\Models\WarshipQueue;
use Carbon\Carbon;

class WarshipQueueService
{
    protected $userId;
    protected $warshipId;
    protected $qty;
    protected $city;

    public function store($userId, WarshipCreateRequest $request)
    {
        $data      = $request->only('cityId', 'warshipId', 'qty');
        $cityId    = $data['cityId'];
        $warshipId = $data['warshipId'];
        $qty       = $data['qty'];

        $this->userId    = $userId;
        $this->warshipId = $warshipId;
        $this->qty       = $qty;

        $this->city = City::where('id', $cityId)->where('user_id', $this->userId)->first();

        if ($this->city && $this->city->id && $this->canBuild()) {
            $queue = $this->updateWarshipQueue();

            return $queue;
        }

        return abort(403);
    }

    public function orderWarship($userId, $data)
    {
        $queue     = null;
        $cityId    = $data['cityId'];
        $warshipId = $data['warshipId'];
        $qty       = $data['qty'];

        $this->userId    = $userId;
        $this->warshipId = $warshipId;
        $this->qty       = $qty;

        $this->city = City::where('id', $cityId)->where('user_id', $this->userId)->first();

        // TODO: should we check canBuild here? (especially for pirates)
        if ($this->city && $this->city->id) {
            $queue = $this->updateWarshipQueue();
        }

        return $queue;
    }

    public function canBuild()
    {
        $hasAllRequirements = $this->hasAllRequirements($this->city, $this->warshipId);
        /*$hasEnoughResources = false;*/

        // found out what resources we need for warship
        // TODO: do we need it? We check it in $this->updateWarshipQueue();
        /*$resources = ResearchResource::where('research_id', $this->researchId)->where('lvl', $this->nextLvl)->first();

        if ($resources && $resources->id) {
            if ($this->city->gold >= $resources->gold && $this->city->population >= $resources->population) {
                $hasEnoughResources = true;
            }
        }*/

        return /*$hasEnoughResources &&*/ $hasAllRequirements;
    }

    public function hasAllRequirements($city, $warshipId): bool
    {
        $requirements       = WarshipDependency::where('warship_id', $warshipId)
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

    public function updateWarshipQueue()
    {
        $warshipDict = WarshipDictionary::find($this->warshipId)->load('requiredResources');
        $cityResources = $this->city->resources;

        // Determine the maximum number of warships that can be built with the available resources
        $maxBuildableQty = 1000;

        foreach ($warshipDict->requiredResources as $requiredResource) {
            $maxQtyForResource = 0;

            // Find the corresponding resource in the city resources
            foreach ($cityResources as $cityResource) {
                if ($cityResource->id === $requiredResource->id) {
                    // Calculate the maximum buildable quantity based on this resource
                    $maxQtyForResource = floor($cityResource->qty / $requiredResource->qty);
                }
            }

            // Update the maximum buildable quantity if needed
            $maxBuildableQty = min($maxBuildableQty, $maxQtyForResource);
        }

        // Determine the actual quantity to build (minimum of requestedQty and maxBuildableQty)
        $actualWarshipsQtyToBuild = min($this->qty, $maxBuildableQty);

        $time                   = $this->qty * $warshipDict->time;

        $queue = WarshipQueue::where('user_id', $this->userId)->where('city_id', $this->city->id)->orderBy('deadline')->get();

        if ($this->qty > 0) {
            if (!count($queue)) {
                // just add time for first queue
                $deadline = Carbon::now()->addSeconds($time);
            } else {
                // calculate deadline for next item in queue
                $deadline = Carbon::create($queue[count($queue) - 1]->deadline)->addSeconds($time);
            }

            $queue->push(WarshipQueue::create([
                'user_id'    => $this->userId,
                'city_id'    => $this->city->id,
                'warship_id' => $this->warshipId,
                'qty'        => $actualWarshipsQtyToBuild,
                'time'       => $time,
                'deadline'   => $deadline
            ]));

            // Subtract the required amount of each resource from the city
            foreach ($warshipDict->requiredResources as $requiredResource) {
                $requiredQty = $requiredResource->qty * $actualWarshipsQtyToBuild;

                // Find the corresponding resource in the city resources
                $cityResource = $cityResources->where('resource_id', $requiredResource->resource_id)->first();

                // Subtract the required quantity from the city's resource
                $cityResource->qty -= $requiredQty;

                // Save the changes to the database
                $cityResource->save();
            }
        }

        return $queue;
    }
}
