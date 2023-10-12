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
        $warshipDict = WarshipDictionary::find($this->warshipId);

        $totalWarshipGold       = $this->qty * $warshipDict->gold;
        $totalWarshipPopulation = $this->qty * $warshipDict->population;
        $time                   = $this->qty * $warshipDict->time;

        $cityGold       = $this->city->gold;
        $cityPopulation = $this->city->population;

        if ($this->qty > $cityGold / $warshipDict->gold) {
            $this->qty = floor($cityGold / $warshipDict->gold);
        }

        if ($this->qty > $cityPopulation / $warshipDict->population) {
            $this->qty = floor($cityPopulation / $warshipDict->population);
        }

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
                'qty'        => $this->qty,
                'time'       => $time,
                'deadline'   => $deadline
            ]));

            // take resources from city
            $this->city->update([
                'gold'       => $cityGold - $totalWarshipGold,
                'population' => $cityPopulation - $totalWarshipPopulation
            ]);
        }

        return $queue;
    }
}
