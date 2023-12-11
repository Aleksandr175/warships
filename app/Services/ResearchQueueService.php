<?php

namespace App\Services;

use App\Http\Requests\Api\ResearchRequest;
use App\Models\City;
use App\Models\Research;
use App\Models\ResearchDependency;
use App\Models\ResearchQueue;
use App\Models\ResearchResource;
use Carbon\Carbon;

class ResearchQueueService
{
    protected $userId;
    protected $researchId;
    protected $city;
    protected $nextLvl = 1;

    public function canResearch(): bool
    {
        $research = Research::where('user_id', $this->userId)->where('research_id', $this->researchId)->first();

        if ($research && $research->id) {
            $this->nextLvl = $research->lvl + 1;
        } else {
            $this->nextLvl = 1;
        }

        $hasAllRequirements = $this->hasAllRequirements($this->city, $this->researchId, $this->nextLvl);
        $hasEnoughResources = false;

        // found out what resources we need for research
        $resources = ResearchResource::where('research_id', $this->researchId)->where('lvl', $this->nextLvl)->first();

        if ($resources && $resources->id) {
            if ($this->city->gold >= $resources->gold && $this->city->population >= $resources->population) {
                $hasEnoughResources = true;
            }
        }

        return $hasEnoughResources && $hasAllRequirements;
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

    public function store($userId, ResearchRequest $request, $city): ResearchQueue
    {
        $data       = $request->only('researchId');
        $researchId = $data['researchId'];

        $this->researchId = $researchId;
        $this->city       = $city;
        $this->userId     = $userId;

        $this->city = City::where('id', $this->city->id)->where('user_id', $this->userId)->first();

        if ($this->city && $this->city->id && $this->canResearch()) {
            $queue = $this->updateQueue();

            // TODO: add job for researches like BuildJob::dispatch([])

            return $queue;
        }

        return abort(403);
    }

    public function updateQueue(): ResearchQueue
    {
        // found out what resources we need for research
        $resources = ResearchResource::where('research_id', $this->researchId)->where('lvl', $this->nextLvl)->first();

        $time = $resources->time;

        // take resources from city
        $this->city->update([
            'gold'       => $this->city->gold - $resources->gold,
            'population' => $this->city->population - $resources->population
        ]);

        return ResearchQueue::create([
            'research_id' => $this->researchId,
            'city_id'     => $this->city->id,
            'user_id'     => $this->userId,
            'gold'        => $resources->gold,
            'population'  => $resources->population,
            'lvl'         => $this->nextLvl,
            'time'        => $time,
            'deadline'    => Carbon::now()->addSeconds($time)
        ]);
    }

    public function cancel($userId): City
    {
        $researchQueue = ResearchQueue::where('user_id', $userId)->first();
        $city          = null;

        if ($researchQueue && $researchQueue->id) {
            // update resource
            $city = City::find($researchQueue->city_id);

            $city->update([
                'gold'       => $city->gold + $researchQueue->gold,
                'population' => $city->population + $researchQueue->population,
            ]);

            $researchQueue->delete();
        }

        return $city;
    }
}
