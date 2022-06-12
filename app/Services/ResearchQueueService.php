<?php

namespace App\Services;

use App\Http\Requests\Api\ResearchRequest;
use App\Models\City;
use App\Models\Research;
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
        }

        // found out what resources we need for research
        $resources = ResearchResource::where('research_id', $this->researchId)->where('lvl', $this->nextLvl)->first();

        if ($resources && $resources->id) {
            if ($this->city->gold >= $resources->gold && $this->city->population >= $resources->population) {
                return true;
            }
        }

        return false;
    }

    public function store($userId, ResearchRequest $request, $city): ResearchQueue
    {
        $queue      = null;
        $data       = $request->only('researchId');
        $researchId = $data['researchId'];

        $this->researchId = $researchId;
        $this->city       = $city;
        $this->userId     = $userId;

        $this->city = City::where('id', $this->city->id)->where('user_id', $this->userId)->first();

        if ($this->city && $this->city->id) {
            if ($this->canResearch()) {
                $queue = $this->updateResearchQueue();
            }
        } else {
            return abort(403);
        }

        return $queue;
    }

    public function updateResearchQueue(): ResearchQueue
    {
        // found out what resources we need for research
        $resources = ResearchResource::where('research_id', $this->researchId)->where('lvl', $this->nextLvl)->first();

        $time = ($resources->gold + $resources->population) / 10;

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
}
