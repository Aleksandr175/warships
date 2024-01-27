<?php

namespace App\Services;

use App\Http\Requests\Api\ResearchRequest;
use App\Models\City;
use App\Models\Research;
use App\Models\ResearchDependency;
use App\Models\ResearchQueue;
use App\Models\ResearchQueueResource;
use App\Models\ResearchResource;
use Carbon\Carbon;

class ResearchQueueService
{
    protected $userId;
    protected $researchId;
    protected $city;
    protected $nextLvl = 1;

    public function canResearch($city, $researchId): bool
    {
        $research = Research::where('user_id', $this->userId)->where('research_id', $researchId)->first();

        if ($research && $research->id) {
            $this->nextLvl = $research->lvl + 1;
        } else {
            $this->nextLvl = 1;
        }

        $hasAllRequirements = $this->hasAllRequirements($this->city, $this->researchId, $this->nextLvl);

        if (!$hasAllRequirements) {
            return false;
        }

        // found out what resources we need for research
        $requiredResources = ResearchResource::where('research_id', $this->researchId)->where('lvl', $this->nextLvl)->get();
        $cityResources     = $city->resources;

        $canOrder = true;
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
                $canOrder = false;

                break;
            }
        }

        return $canOrder;
    }

    public function hasAllRequirements($city, $researchId, $nextLvl): bool
    {
        $requirements       = ResearchDependency::where('research_id', $researchId)
            ->where('research_lvl', $nextLvl)
            ->where('required_entity', 'research')
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

    public function store($userId, ResearchRequest $request): ResearchQueue
    {
        $data       = $request->only('researchId', 'cityId');
        $cityId     = $data['cityId'];
        $researchId = $data['researchId'];

        $this->researchId = $researchId;
        $this->userId     = $userId;

        $this->city = City::where('id', $cityId)->where('user_id', $this->userId)->first();

        if ($this->city && $this->city->id && $this->canResearch($this->city, $researchId)) {
            return $this->updateQueue($this->city);
        }

        return abort(403);
    }

    public function updateQueue($city): ResearchQueue
    {
        $cityResources = $city->resources;

        // found out what resources we need for research
        $requiredResources = ResearchResource::where('research_id', $this->researchId)->where('lvl', $this->nextLvl)->get();

        // each resource row has time_required for upgrading research
        $timeRequired = $requiredResources[0]->time_required;

        // Subtract the required amount of each resource from the city
        foreach ($requiredResources as $requiredResource) {
            // Find the corresponding resource in the city resources
            $cityResource = $cityResources->where('resource_id', $requiredResource->resource_id)->first();

            // Subtract the required quantity from the city's resource
            $cityResource->qty -= $requiredResource->qty;

            $cityResource->save();
        }

        $researchQueue = ResearchQueue::create([
            'research_id'   => $this->researchId,
            'city_id'       => $city->id,
            'user_id'       => $this->userId,
            'lvl'           => $this->nextLvl,
            'time_required' => $timeRequired,
            'deadline'      => Carbon::now()->addSeconds($timeRequired)
        ]);

        // Add required amount of each resource to table in case we want to cancel building queue
        foreach ($requiredResources as $requiredResource) {
            ResearchQueueResource::create([
                'research_queue_id' => $researchQueue->id,
                'resource_id'       => $requiredResource->resource_id,
                'qty'               => $requiredResource->qty
            ]);
        }

        return $researchQueue;
    }

    public function cancel($userId): City
    {
        $researchQueue = ResearchQueue::where('user_id', $userId)->first();
        $city          = null;

        if ($researchQueue && $researchQueue->id) {
            // find city
            $city = City::find($researchQueue->city_id);

            $resources = ResearchQueueResource::where('research_queue_id', $researchQueue->id)->get();

            foreach ($resources as $resource) {
                $cityResource = $city->resources->where('resource_id', $resource->resource_id)->first();
                $cityResource->increment('qty', $resource->qty);

                $resource->delete();
            }

            $researchQueue->delete();
        }

        return $city;
    }
}
