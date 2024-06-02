<?php

namespace App\Services;

use App\Events\ResearchesDataUpdatedEvent;
use App\Http\Requests\Api\ResearchRequest;
use App\Models\City;
use App\Models\Research;
use App\Models\ResearchDependency;
use App\Models\ResearchQueue;
use App\Models\ResearchQueueResource;
use App\Models\ResearchResource;
use App\Models\Resource;
use App\Models\User;
use Carbon\Carbon;

class ResearchQueueService
{
    protected $userId;
    protected $researchId;
    protected $city;
    protected $nextLvl = 1;

    public function handle(ResearchQueue $researchQueue)
    {
        $researchId = $researchQueue['research_id'];
        $user       = User::find($researchQueue['user_id']);
        $research   = $user->research($researchId);
        $queue      = $user->researchesQueue()->first();

        if ($queue) {
            // add lvl
            if ($research) {
                $research->increment('lvl');
            } else {
                // create new research
                $user->researches()->create([
                    'research_id' => $researchId,
                    'user_id'     => $user->id,
                    'lvl'         => 1,
                ]);
            }

            $queue->resources()->delete();
            $queue->delete();
            $user->researchesQueue()->delete();

            $this->sendResearchesDataUpdatedEvent($user);
        }
    }

    public function sendResearchesDataUpdatedEvent(User $user): void
    {
        $researches = $user->researches;

        ResearchesDataUpdatedEvent::dispatch($user->id, $researches);
    }

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
        $userResources     = User::find($this->userId)->resources;

        $canOrder = true;
        foreach ($requiredResources as $requiredResource) {
            $hasEnoughRequiredResourceQty = $this->hasEnoughResource($cityResources, $requiredResource) || $this->hasEnoughResource($userResources, $requiredResource);

            if (!$hasEnoughRequiredResourceQty) {
                $canOrder = false;

                break;
            }
        }

        return $canOrder;
    }

    public function hasEnoughResource($resources, $requiredResource): bool
    {
        $hasEnoughRequiredResourceQty = false;
        // Find the corresponding resource in the city/user resources
        foreach ($resources as $resource) {
            if ($resource->resource_id === $requiredResource->resource_id
                && $resource->qty >= $requiredResource->qty) {
                $hasEnoughRequiredResourceQty = true;
                break;
            }
        }

        return $hasEnoughRequiredResourceQty;
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

    public function updateQueue(City $city): ResearchQueue
    {
        $userId = $city->user_id;

        // found out what resources we need for research
        $requiredResources = ResearchResource::where('research_id', $this->researchId)->where('lvl', $this->nextLvl)->get();

        // each resource row has time_required for upgrading research
        $timeRequired = $requiredResources[0]->time_required;

        // Subtract the required amount of each resource from the city
        foreach ($requiredResources as $requiredResource) {
            $typeOfResource = Resource::find($requiredResource->resource_id)->type;

            if ($typeOfResource === config('constants.RESOURCE_TYPE_IDS.COMMON')) {
                (new CityService())->subtractResourceFromCity($city->id, $requiredResource->resource_id, $requiredResource->qty);
            }

            if ($typeOfResource === config('constants.RESOURCE_TYPE_IDS.RESEARCH')) {
                (new UserService())->subtractResourceFromUser($userId, $requiredResource->resource_id, $requiredResource->qty);
            }
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

    public function cancel(int $userId): City
    {
        $researchQueue = ResearchQueue::where('user_id', $userId)->first();
        $city          = null;

        if ($researchQueue && $researchQueue->id) {
            // find city
            $city = City::find($researchQueue->city_id);

            $resources = ResearchQueueResource::where('research_queue_id', $researchQueue->id)->get();

            foreach ($resources as $resource) {
                $typeOfResource = Resource::find($resource->resource_id)->type;

                if ($typeOfResource === config('constants.RESOURCE_TYPE_IDS.COMMON')) {
                    (new CityService())->addResourceToCity($city->id, $resource->resource_id, $resource->qty);
                }

                if ($typeOfResource === config('constants.RESOURCE_TYPE_IDS.RESEARCH')) {
                    (new UserService())->addResourceToUser($userId, $resource->resource_id, $resource->qty);
                }

                $resource->delete();
            }

            $researchQueue->delete();
        }

        return $city;
    }
}
