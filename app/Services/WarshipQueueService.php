<?php

namespace App\Services;

use App\Http\Requests\Api\WarshipCreateRequest;
use App\Models\BuildingQueueSlot;
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

        if ($this->city && $this->city->id && $this->canBuild($this->city)) {
            return $this->updateWarshipQueue();
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

    public function canBuild($city)
    {
        $hasAllRequirements = $this->hasAllRequirements($city, $this->warshipId);
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

        $shipyardBuilding = $city->building(config('constants.BUILDINGS.SHIPYARD'));

        $shipyardBuildingLvl = 0;

        if ($shipyardBuilding) {
            $shipyardBuildingLvl = $shipyardBuilding->lvl;
        }

        if (!$shipyardBuildingLvl) {
            return false;
        }

        $warshipQueue = $city->warshipQueue;

        $maxWarshipSlots = 0;

        $slotsData = BuildingQueueSlot::slots($shipyardBuilding->building_id, $shipyardBuildingLvl);

        if ($slotsData) {
            $maxWarshipSlots = $slotsData->slots;
        }

        if (count($warshipQueue) >= $maxWarshipSlots) {
            $hasAllRequirements = false;
        }

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
        $warshipDict   = WarshipDictionary::find($this->warshipId)->load('requiredResources');

        $warshipService = new WarshipService();
        // Determine the maximum number of warships that can be built with the available resources
        // number could not be more than one slot can have
        $availableWarshipQtyToBuild = $warshipService->hasResourceToBuildWarships($this->city, $this->warshipId, $this->qty);

        $time = $availableWarshipQtyToBuild * $warshipDict->time;

        $queue = WarshipQueue::where('user_id', $this->userId)->where('city_id', $this->city->id)->orderBy('deadline')->get();

        if ($availableWarshipQtyToBuild > 0) {
            $deadline = Carbon::now()->addSeconds($time);

            $queue->push(WarshipQueue::create([
                'user_id'    => $this->userId,
                'city_id'    => $this->city->id,
                'warship_id' => $this->warshipId,
                'qty'        => $availableWarshipQtyToBuild,
                'time'       => $time,
                'deadline'   => $deadline
            ]));

            // Subtract the required amount of each resource from the city
            $warshipService->subtractResourcesForWarships($this->city->id, $warshipDict, $availableWarshipQtyToBuild);
        }

        return $queue;
    }
}
