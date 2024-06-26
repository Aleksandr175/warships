<?php

namespace App\Services;

use App\Events\CityDataUpdatedEvent;
use App\Events\FleetUpdatedEvent;
use App\Http\Resources\WarshipResource;
use App\Jobs\BattleJob;
use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetResource;
use App\Models\FleetTaskDictionary;
use App\Models\Message;
use App\Models\Research;
use App\Models\Resource;
use App\Models\User;
use App\Models\Warship;
use App\Models\WarshipDictionary;
use Carbon\Carbon;

class FleetService
{
    private $cityId              = null;
    private $coordX              = null;
    private $coordY              = null;
    private $fleetDetails        = [];
    private $updatedFleetDetails = [];
    private $repeating           = false;
    private $taskType            = null;
    private $taskTypeSlug        = null;
    private $targetCity          = null;
    private $taskTypeId          = null;
    private $type                = null;
    private $resources           = [];

    // send fleet to target
    public function send($params, $user)
    {
        $this->cityId              = $params->cityId;
        $this->coordX              = $params->coordX;
        $this->coordY              = $params->coordY;
        $this->fleetDetails        = $params->fleetDetails;
        $this->repeating           = $params->repeating ? 1 : 0;
        $this->taskTypeSlug        = $params->taskType;
        $this->updatedFleetDetails = [];
        $this->type                = $params->type; // map | adventure
        $this->resources           = $params['resources'] ?: [];

        if ($this->taskTypeSlug !== 'expedition' && $this->taskTypeSlug !== 'trade' && (!$this->coordX || !$this->coordY)) {
            return 'no coordinates';
        }

        // get archipelago id depending on type
        if ($this->type === 'adventure') {
            // send fleet to external archipelago / adventure
            $adventure = $user->adventure;

            $archipelagoId = $adventure->archipelago_id;

            $this->repeating = false;
        } else {
            // send fleet inside our archipelago
            $archipelagoId = $user->archipelagoId();
        }

        // get player's city
        $userCity = $user->city($this->cityId);

        if (!($userCity && $userCity->id)) {
            return 'it is not city of current user';
        }

        if ($this->taskTypeSlug !== 'expedition' && $this->taskTypeSlug !== 'trade') {
            // get target city by coordinates and archipelago id
            $this->targetCity = $this->getCityByCoords($archipelagoId, (int)$this->coordX, (int)$this->coordY);

            // we need target city, except for expedition
            if (!$this->isCity($this->targetCity)) {
                return 'there is no island';
            }

            if ($this->targetCity->coord_y === $userCity->coord_y &&
                $this->targetCity->coord_x === $userCity->coord_x &&
                $this->targetCity->archipelago_id === $userCity->archipelago_id) {
                return 'Your fleet is already in this island';
            }

            if ($this->type === 'adventure') {
                if ($this->targetCity->raided) {
                    return 'Adventure island is already raided';
                }

                // we can't send more than 1 fleet to the adventure island at one time
                $isSomeFleetGoingToIsland = Fleet::where('target_city_id', $this->targetCity->id)->first();

                if ($isSomeFleetGoingToIsland) {
                    return 'Some fleet is already going to the adventure island';
                }
            }
        }

        $this->taskType = $this->getTaskType($this->taskTypeSlug);

        $taskTypeId = $this->taskType && $this->taskType->id;

        if (!$taskTypeId) {
            return 'no such task type';
        }

        if (
            $this->type !== 'adventure' &&
            $this->taskTypeSlug === 'attack' && $this->targetCity->city_dictionary_id !== config('constants.CITY_TYPE_ID.PIRATE_BAY')) {
            return 'We can attack pirate bay only';
        }

        $this->taskTypeId = $this->taskType->id;

        if (!$this->fleetDetails || !count($this->fleetDetails)) {
            return 'No warships in fleet';
        }

        // get warships in city
        $warshipGroupsInCity = $userCity->warships()->where('user_id', $user->id)->get();

        // check details
        $this->updatedFleetDetails = $this->checkFleetDetails($warshipGroupsInCity, $this->fleetDetails);

        if (!count($this->updatedFleetDetails)) {
            return 'no warships selected';
        }

        $cityResources          = $userCity->resources;
        $resourcesDict          = Resource::get();
        $wholeAmountOfResources = 0;

        foreach ($this->resources as $resourceSlug => $resourceAmount) {
            $resourceExistInDict = false;
            foreach ($resourcesDict as $resDict) {
                if ($resDict->slug === $resourceSlug) {
                    $resourceExistInDict = true;
                }
            }

            if (!$resourceExistInDict) {
                return 'Error: Some resources do not exist';
            }

            $wholeAmountOfResources += (int)$resourceAmount;
        }

        $warshipsDictionary = WarshipDictionary::get();

        $this->updatedFleetDetails = (new BattleService)->populateFleetDetailsWithCapacityAndHealth($user->id, $this->updatedFleetDetails, $warshipsDictionary);

        $availableCapacity = (new BattleService)->getAvailableCapacity(null, $this->updatedFleetDetails);

        if ($availableCapacity < $wholeAmountOfResources) {
            return 'Cant carry so many resources';
        }

        $defaultFleetStatusId = $this->getDefaultFleetTaskStatus($this->taskTypeId);
        $timeToTarget         = $this->getTimeToTarget($defaultFleetStatusId);

        // some logic for sending trade fleet
        if ($this->taskTypeId === config('constants.FLEET_TASKS.TRADE')) {
            // get trade system for checking amount of trade fleets
            $tradeSystemResearch = Research::where('user_id', $user->id)->where('research_id', config('constants.RESEARCHES.TRADE_SYSTEM'))->first();
            $tradeSystemLvl      = 0;
            if ($tradeSystemResearch) {
                $tradeSystemLvl = $tradeSystemResearch->lvl;
            }

            $userCityIds = $user->cities->pluck('id')->toArray();
            $tradeFleets = Fleet::whereIn('city_id', $userCityIds)->where('fleet_task_id', config('constants.FLEET_TASKS.TRADE'))->get();

            if (count($tradeFleets) >= $tradeSystemLvl) {
                return 'You cant send more trade fleets';
            }

            // select city for trading
            $randomCityForTrading = City::where('city_dictionary_id', config('constants.CITY_TYPE_ID.ISLAND'))
                ->where('user_id', '!=', $user->id)
                ->inRandomOrder()
                ->first();

            if (!$randomCityForTrading) {
                return 'Cant find city for trading';
            }

            $this->targetCity = $randomCityForTrading;
        }

        // some logic for sending expedition fleet
        if ($this->taskTypeId === config('constants.FLEET_TASKS.EXPEDITION')) {
            // get expedition system for checking amount of expedition fleets
            $expeditionSystemResearch = Research::where('user_id', $user->id)->where('research_id', config('constants.RESEARCHES.EXPEDITION_SYSTEM'))->first();
            $expeditionSystemLvl      = 0;
            if ($expeditionSystemResearch) {
                $expeditionSystemLvl = $expeditionSystemResearch->lvl;
            }

            $userCityIds      = $user->cities->pluck('id')->toArray();
            $expeditionFleets = Fleet::whereIn('city_id', $userCityIds)->where('fleet_task_id', config('constants.FLEET_TASKS.EXPEDITION'))->get();

            if (count($expeditionFleets) >= $expeditionSystemLvl) {
                return 'You cant send more expedition fleets';
            }
        }

        // create fleet and details
        $fleetId = Fleet::create([
            'city_id'        => $userCity->id,
            'target_city_id' => $this->targetCity?->id,
            'fleet_task_id'  => $this->taskTypeId,
            'speed'          => 100,
            'time'           => $timeToTarget,
            'repeating'      => $this->repeating,
            'status_id'      => $defaultFleetStatusId,
            'deadline'       => Carbon::now()->addSeconds($timeToTarget)
        ])->id;

        $this->transferResourcesFromCityToFleet($fleetId, $cityResources, $this->resources, $resourcesDict);

        $this->moveWarshipsFromCityToFleet($warshipGroupsInCity, $fleetId, $this->updatedFleetDetails);

        $this->sendFleetUpdatedEvent($user, $userCity);
        $this->sendCityDataUpdatedEvent($user);

        return [
            'success'  => true,
            'warships' => WarshipResource::collection($warshipGroupsInCity)
        ];
    }

    // check and correct fleet details, convert fleet details to backend format
    // we cant send more warships that we have in the city
    public function checkFleetDetails($warshipGroupInCity, $fleetDetails): array
    {
        $updatedFleetDetails = [];

        foreach ($warshipGroupInCity as $warshipGroup) {
            foreach ($fleetDetails as $fleetDetail) {
                if ($fleetDetail['warshipId'] === $warshipGroup->warship_id && $fleetDetail['qty'] > 0 && $warshipGroup->qty > 0) {
                    $detail = [
                        'qty'        => min($warshipGroup->qty, $fleetDetail['qty']),
                        'warship_id' => $warshipGroup->warship_id
                    ];

                    $updatedFleetDetails[] = $detail;
                }
            }
        }

        return $updatedFleetDetails;
    }

    public function moveWarshipsFromCityToFleet($warshipGroupsInCity, $fleetId, $fleetDetails): void
    {
        $newFleetDetailsData = [];
        foreach ($fleetDetails as $fleetDetail) {
            $newFleetDetailsData[] = [
                'fleet_id'   => $fleetId,
                'warship_id' => $fleetDetail['warship_id'],
                'qty'        => $fleetDetail['qty']
            ];

            // remove warships from city
            $warshipGroupsInCity->where('warship_id', $fleetDetail['warship_id'])->first()->increment('qty', -$fleetDetail['qty']);
        }

        if (count($newFleetDetailsData)) {
            (new FleetDetail)->insert($newFleetDetailsData);
        }
    }

    // send event via websockets
    public function sendFleetUpdatedEvent($user, $city)
    {
        $fleets        = $city->fleets;
        $fleetsDetails = FleetDetail::getFleetDetails($fleets->pluck('id'));

        $cityIds       = $fleets->pluck('city_id')->toArray();
        $targetCityIds = $fleets->pluck('target_city_id')->toArray();

        $cities = City::whereIn('id', array_merge($cityIds, $targetCityIds))->get();

        FleetUpdatedEvent::dispatch($user, $fleets, $fleetsDetails, $cities);
    }

    public function sendCityDataUpdatedEvent($user)
    {
        $cities = $user->cities;

        CityDataUpdatedEvent::dispatch($user, $cities);
    }

    public function isCity($city): bool
    {
        return $city && isset($city->id);
    }

    public function getTaskType($taskTypeSlug)
    {
        $t = FleetTaskDictionary::where('slug', $taskTypeSlug)->first();

        return $t ?: null;
    }

    public function getCityByCoords(int $archipelagoId, int $coordX, int $coordY): City
    {
        return City::where('archipelago_id', $archipelagoId)->where('coord_x', $coordX)->where('coord_y', $coordY)->first();
    }

    /**
     * @param Fleet $fleet
     *
     * @return void
     */
    public function handleFleet(Fleet $fleet)
    {
        // only if deadline is expired
        if ($fleet->deadline < Carbon::now()) {
            $statusId          = null;
            $deadline          = null;
            $repeating         = null;
            $shouldDeleteFleet = false;

            $city = City::find($fleet->city_id);

            $resourcesDictionary = Resource::get()->toArray();

            // task: trade
            if ($fleet->isTradeTask()) {
                if ($fleet->isTradeGoingToTarget()) {
                    dump('trade: fleet starts to trade');
                    $statusId = config('constants.FLEET_STATUSES.TRADING');

                    $duration = config('constants.DURATION.TRADING');

                    $deadline = Carbon::create($fleet->deadline)->addSeconds($duration);

                    // check user of island (we can trade only with foreign islands)
                    $city       = City::find($fleet->city_id);
                    $targetCity = City::find($fleet->target_city_id);

                    Message::create([
                        'user_id'        => $city->user_id,
                        'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_TRADE_START_TRADING'),
                        'event_type'     => 'Fleet',
                        'city_id'        => $city->id,
                        'target_city_id' => $targetCity->id,
                    ]);

                    if ($city->user_id === $targetCity->user_id) {
                        // send fleet back because we cant trade with ourselves
                        $statusId = config('constants.FLEET_STATUSES.TRADE_GOING_BACK');

                        $duration = config('constants.DURATION.TRADING_GOING_BACK');

                        $deadline  = Carbon::create($fleet->deadline)->addSeconds($duration);
                        $repeating = 0;
                    }
                }

                if ($fleet->isTrading()) {
                    $statusId = config('constants.FLEET_STATUSES.TRADE_GOING_BACK');

                    $duration = config('constants.DURATION.TRADING_GOING_BACK');

                    $deadline = Carbon::create($fleet->deadline)->addSeconds($duration);

                    $warshipsDictionary = WarshipDictionary::get();

                    $fleetDetails      = FleetDetail::getFleetDetails([$fleet->id])->toArray();
                    $fleetDetails      = (new BattleService)->populateFleetDetailsWithCapacityAndHealth($city->user_id, $fleetDetails, $warshipsDictionary);
                    $availableCapacity = (new BattleService)->getAvailableCapacity($fleet, $fleetDetails);

                    $gold          = floor($availableCapacity * 0.1);
                    $goldForIsland = floor($gold / 2);
                    $this->addResourceToFleet($fleet->id, config('constants.RESOURCE_IDS.GOLD'), $gold);

                    $targetCity = City::find($fleet->target_city_id);

                    // TODO: change logic for trading
                    $cityService = new CityService();
                    $cityService->addResourceToCity($targetCity->id, config('constants.RESOURCE_IDS.GOLD'), $goldForIsland);

                    dump('trade: fleet completed trading, got ' . $gold . ' gold, island got: ' . $goldForIsland);
                }

                if ($fleet->isTradeGoingBack()) {
                    dump('trade: fleet has returned to home');

                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    $messageId = Message::create([
                        'user_id'        => $city->user_id,
                        'content'        => 'Merchant fleet is back.',
                        'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_TRADE_IS_BACK'),
                        'event_type'     => 'Fleet',
                        'city_id'        => $fleet->target_city_id,
                        'target_city_id' => $city->id,
                    ])->id;
                    (new MessageService())->addMessageAboutResources($fleet, $messageId);
                    (new MessageService())->addMessageAboutFleetDetails($fleetDetails, $messageId);


                    // move all resources from Fleet to City
                    $this->moveResourcesFromFleetToCityOrUser($fleet, $city, $resourcesDictionary);


                    if ($fleet->repeating) {
                        dump('trade: fleet repeats trade task, going to target');
                        // just repeat task
                        $statusId = config('constants.FLEET_STATUSES.TRADE_GOING_TO_TARGET');

                        $duration = config('constants.DURATION.TRADING_GOING_TO');

                        $deadline = Carbon::create($fleet->deadline)->addSeconds($duration);
                    } else {
                        // transfer fleet to warships in the island
                        $this->convertFleetDetailsToWarships($fleetDetails, $city);

                        $shouldDeleteFleet = true;
                    }
                }
            }

            // task: move fleet to another island
            if ($fleet->isMovingTask()) {
                if ($fleet->isMovingFleetGoingToTarget()) {
                    // check user of island (we can move fleet between not ours islands)
                    $city         = City::find($fleet->city_id);
                    $targetCity   = City::find($fleet->target_city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    if ($city->user_id === $targetCity->user_id) {
                        dump('move: fleet moved to another island');

                        $messageId = Message::create([
                            'user_id'        => $city->user_id,
                            'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_MOVE_DONE'),
                            'event_type'     => 'Fleet',
                            'city_id'        => $fleet->city_id,
                            'target_city_id' => $fleet->target_city_id,
                        ])->id;
                        (new MessageService())->addMessageAboutResources($fleet, $messageId);
                        (new MessageService())->addMessageAboutFleetDetails($fleetDetails, $messageId);

                        // transfer fleet to warships in the island
                        $this->convertFleetDetailsToWarships($fleetDetails, $targetCity);

                        $this->moveResourcesFromFleetToCityOrUser($fleet, $targetCity, $resourcesDictionary);

                        $shouldDeleteFleet = true;
                    } else {
                        dump('move: fleet is returning to original island');
                        // return fleet back
                        $statusId = config('constants.FLEET_STATUSES.MOVING_GOING_BACK');

                        $duration = config('constants.DURATION.MOVE_GOING_BACK');

                        $deadline  = Carbon::create($fleet->deadline)->addSeconds($duration);
                        $repeating = 0;

                        Message::create([
                            'user_id'        => $city->user_id,
                            'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_MOVE_CANT'),
                            'event_type'     => 'Fleet',
                            'city_id'        => $fleet->city_id,
                            'target_city_id' => $fleet->target_city_id,
                        ]);
                    }

                }

                if ($fleet->isMovingFleetGoingBack()) {
                    dump('move: fleet has returned');
                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    $messageId = Message::create([
                        'user_id'        => $city->user_id,
                        'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_MOVE_WENT_BACK'),
                        'event_type'     => 'Fleet',
                        'city_id'        => $fleet->target_city_id,
                        'target_city_id' => $fleet->city_id,
                    ])->id;
                    (new MessageService())->addMessageAboutResources($fleet, $messageId);
                    (new MessageService())->addMessageAboutFleetDetails($fleetDetails, $messageId);

                    // transfer fleet to warships in the island
                    $this->convertFleetDetailsToWarships($fleetDetails, $city);

                    $this->moveResourcesFromFleetToCityOrUser($fleet, $city, $resourcesDictionary);

                    $shouldDeleteFleet = true;
                }
            }

            // task: transport resources to another island and go back
            if ($fleet->isTrasnsportTask()) {
                if ($fleet->isTransportFleetGoingToTarget()) {
                    dump('transport: fleet delivered resource, fleet is going back');
                    $statusId = config('constants.FLEET_STATUSES.TRANSPORT_GOING_BACK');

                    $duration = config('constants.DURATION.TRADING_GOING_BACK');

                    $deadline = Carbon::create($fleet->deadline)->addSeconds($duration);

                    $targetCity = City::find($fleet->target_city_id);

                    $this->moveResourcesFromFleetToCityOrUser($fleet, $targetCity, $resourcesDictionary);
                }

                if ($fleet->isTransportFleetGoingBack()) {
                    dump('transport: fleet has returned to original island');

                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    $this->convertFleetDetailsToWarships($fleetDetails, $city);

                    $this->moveResourcesFromFleetToCityOrUser($fleet, $city, $resourcesDictionary);

                    $shouldDeleteFleet = true;
                }
            }

            // task: expedition to unknown
            if ($fleet->isExpeditionTask()) {
                if ($fleet->isExpeditionFleetGoingToTarget()) {
                    dump('expedition: fleet reached unknown islands, start researching...');
                    $statusId = config('constants.FLEET_STATUSES.EXPEDITION_IN_PROGRESS');

                    $duration = config('constants.DURATION.EXPEDITION');

                    $deadline = Carbon::create($fleet->deadline)->addSeconds($duration);
                }

                if ($fleet->isExpeditionInProgress()) {
                    dump('expedition: calculation expedition result...');
                    $expeditionService = new ExpeditionService();

                    $expeditionService->handle($fleet);
                }

                if ($fleet->isExpeditionDone()) {
                    dump('expedition: fleet completed expedition, we got something, going back...');
                    $statusId = config('constants.FLEET_STATUSES.EXPEDITION_GOING_BACK');

                    $duration = config('constants.DURATION.EXPEDITION_GOING_BACK');

                    $deadline = Carbon::create($fleet->deadline)->addSeconds($duration);
                }

                if ($fleet->isExpeditionGoingBack()) {
                    dump('expedition: fleet returned');

                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    $messageId = Message::create([
                        'user_id'        => $city->user_id,
                        'template_id'    => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_IS_BACK'),
                        'event_type'     => 'Expedition',
                        'archipelago_id' => $city->archipelago_id,
                        'coord_x'        => $city->coord_x,
                        'coord_y'        => $city->coord_y,
                    ])->id;
                    (new MessageService())->addMessageAboutResources($fleet, $messageId);
                    (new MessageService())->addMessageAboutFleetDetails($fleetDetails, $messageId);

                    $this->moveResourcesFromFleetToCityOrUser($fleet, $city, $resourcesDictionary);

                    if ($fleet->repeating) {
                        dump('expedition: fleet repeats expedition task, going to target');
                        // just repeat task
                        $statusId = config('constants.FLEET_STATUSES.EXPEDITION_GOING_TO_TARGET');

                        $duration = config('constants.DURATION.EXPEDITION_GOING_TO');

                        $deadline = Carbon::create($fleet->deadline)->addSeconds($duration);
                    } else {
                        // transfer fleet to warships in the island
                        $this->convertFleetDetailsToWarships($fleetDetails, $city);

                        $shouldDeleteFleet = true;
                    }
                }
            }

            // task: attack island and go back
            if ($fleet->isAttackTask()) {
                if ($fleet->isAttackFleetGoingToTarget()) {
                    dump('attack fleet: fleet achieved target island');
                    $statusId = config('constants.FLEET_STATUSES.ATTACK_IN_PROGRESS');

                    // TODO: how long? // distance?
                    $deadline = Carbon::create($fleet->deadline);

                    BattleJob::dispatch()->onQueue('battle');
                }

                if ($fleet->isAttackFleetAttackCompleted()) {
                    dump('fleets\'s attack is completed: fleet is going back');
                    $statusId = config('constants.FLEET_STATUSES.ATTACK_GOING_BACK');

                    $duration = config('constants.DURATION.ATTACK_GOING_BACK');

                    // TODO: how long? // distance?
                    $deadline = Carbon::create($fleet->deadline)->addSeconds($duration);
                }

                if ($fleet->isAttackFleetGoingBack()) {
                    dump('attack: fleet has returned to original island');

                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    $this->convertFleetDetailsToWarships($fleetDetails, $city);

                    $shouldDeleteFleet = true;
                }
            }

            if ($deadline && $statusId) {
                // update fleet
                $fleet->update([
                    'status_id' => $statusId,
                    'deadline'  => $deadline,
                    'repeating' => $repeating !== null ? $repeating : $fleet->repeating
                ]);
            }

            if ($shouldDeleteFleet) {
                $fleet->delete();
            }

            if ($statusId || $deadline || $shouldDeleteFleet) {
                dump('Dispatch new fleet event');

                $user = User::find($city->user_id);
                $this->sendFleetUpdatedEvent($user, $city);
            }
        }

    }

    public function convertFleetDetailsToWarships($fleetDetails, $city): void
    {
        foreach ($fleetDetails as $fleetDetail) {
            $warship = $city->warship($fleetDetail->warship_id);

            if (!$warship) {
                Warship::create([
                    'warship_id' => $fleetDetail->warship_id,
                    'qty'        => $fleetDetail->qty,
                    'city_id'    => $city->id,
                    'user_id'    => $city->user_id
                ]);
            } else {
                $city->warship($fleetDetail->warship_id)->increment('qty', $fleetDetail->qty);
            }

            $fleetDetail->delete();
        }
    }

    public function transferResourcesFromCityToFleet(int $fleetId, $cityResources, $resources, $resourcesDict): void
    {
        // transfer resources from island to fleet
        foreach ($resources as $resourceSlug => $resourceAmount) {
            $resourceId = null;

            foreach ($resourcesDict as $resDict) {
                if ($resDict->slug === $resourceSlug) {
                    $resourceId = $resDict->id;
                }
            }

            if ($resourceId) {
                foreach ($cityResources as $cityResource) {
                    if ($resourceId === $cityResource->resource_id) {
                        // check max available qty we can transfer to fleet
                        $maxQty    = floor((int)$resourceAmount);
                        $qtyInCity = floor($cityResource->qty);

                        if ($maxQty > $qtyInCity) {
                            $maxQty = $qtyInCity;
                        }

                        $this->addResourceToFleet($fleetId, $resourceId, $maxQty);
                        $cityResource->decrement('qty', $maxQty);

                        break;
                    }
                }
            }
        }
    }

    /*public function subtractResourcesFromCity($cityResources, $subtractResources, $resourcesDict): void
    {
        // $subtractResources: ['slug' => qty]
        foreach ($subtractResources as $subtractResourceSlug => $subtractResourceQty) {
            $resourceId = null;

            foreach ($resourcesDict as $resDict) {
                if ($resDict->slug === $subtractResourceSlug) {
                    $resourceId = $resDict->id;
                }
            }

            if ($resourceId) {
                foreach ($cityResources as $cityResource) {
                    if ($resourceId === $cityResource->resource_id) {
                        // check max available qty we can take from island (to fleet)
                        $maxQty = $subtractResourceQty;

                        if ($maxQty > $cityResource->qty) {
                            $maxQty = $cityResource->qty;
                        }

                        $cityResource->decrement('qty', $maxQty);
                    }
                }
            }
        }
    }*/

    public function addResourceToFleet(int $fleetId, int $resourceId, int $qty): void
    {
        $resource = FleetResource::where('fleet_id', $fleetId)->where('resource_id', $resourceId)->first();

        if ($resource) {
            $resource->increment('qty', $qty);
        } else {
            FleetResource::create([
                'fleet_id'    => $fleetId,
                'resource_id' => $resourceId,
                'qty'         => $qty
            ]);
        }
    }

    public function moveResourcesFromFleetToCityOrUser(Fleet $fleet, City $city, $resourcesDictionary): void
    {
        $resources = FleetResource::where('fleet_id', $fleet->id)->get();

        $cityService = new CityService();
        $userService = new UserService();

        foreach ($resources as $resource) {
            $resourceType = $resourcesDictionary[0]['type'];
            foreach ($resourcesDictionary as $resourceDict) {
                if ($resourceDict['id'] === $resource['resource_id']) {
                    $resourceType = $resourceDict['type'];
                    break;
                }
            }

            if ($resourceType === config('constants.RESOURCE_TYPE_IDS.COMMON')) {
                $cityService->addResourceToCity($city->id, $resource->resource_id, $resource->qty);
            }

            // some unique resources we move to user, not city
            if ($resourceType === config('constants.RESOURCE_TYPE_IDS.CARD')
                || $resourceType === config('constants.RESOURCE_TYPE_IDS.RESEARCH')) {
                $userService->addResourceToUser($city->user_id, $resource->resource_id, $resource->qty);
            }
        }

        FleetResource::where('fleet_id', $fleet->id)->delete();
    }

    public function getDefaultFleetTaskStatus(int $taskTypeId)
    {
        $defaultFleetStatusId = 0;

        if ($taskTypeId === config('constants.FLEET_TASKS.TRADE')) {
            $defaultFleetStatusId = config('constants.FLEET_STATUSES.TRADE_GOING_TO_TARGET');
        }
        if ($taskTypeId === config('constants.FLEET_TASKS.MOVE')) {
            $defaultFleetStatusId = config('constants.FLEET_STATUSES.MOVING_GOING_TO_TARGET');
        }
        if ($taskTypeId === config('constants.FLEET_TASKS.ATTACK')) {
            $defaultFleetStatusId = config('constants.FLEET_STATUSES.ATTACK_GOING_TO_TARGET');
        }
        if ($taskTypeId === config('constants.FLEET_TASKS.TRANSPORT')) {
            $defaultFleetStatusId = config('constants.FLEET_STATUSES.TRANSPORT_GOING_TO_TARGET');
        }
        if ($taskTypeId === config('constants.FLEET_TASKS.EXPEDITION')) {
            $defaultFleetStatusId = config('constants.FLEET_STATUSES.EXPEDITION_GOING_TO_TARGET');
        }

        return $defaultFleetStatusId;
    }

    public function getTimeToTarget(int $fleetStatusId): int
    {
        // calculate time to target
        // $distance = abs($userCity->coord_x - $this->coordX) + abs($userCity->coord_y - $this->coordY);

        // TODO: add speed param for time ???
        $timeToTarget = 10; // in seconds

        if ($fleetStatusId === config('constants.FLEET_STATUSES.TRADE_GOING_TO_TARGET')) {
            $timeToTarget = config('constants.DURATION.TRADING_GOING_TO');
        }
        if ($fleetStatusId === config('constants.FLEET_STATUSES.MOVING_GOING_TO_TARGET')) {
            $timeToTarget = config('constants.DURATION.MOVE_GOING_TO');
        }
        if ($fleetStatusId === config('constants.FLEET_STATUSES.ATTACK_GOING_TO_TARGET')) {
            $timeToTarget = config('constants.DURATION.ATTACK_GOING_TO');
        }
        if ($fleetStatusId === config('constants.FLEET_STATUSES.TRANSPORT_GOING_TO_TARGET')) {
            $timeToTarget = config('constants.DURATION.TRANSPORT_GOING_TO');
        }
        if ($fleetStatusId === config('constants.FLEET_STATUSES.EXPEDITION_GOING_TO_TARGET')) {
            $timeToTarget = config('constants.DURATION.EXPEDITION_GOING_TO');
        }

        return $timeToTarget;
    }
}
