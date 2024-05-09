<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildingDependencyResource;
use App\Http\Resources\BuildingDictionaryResource;
use App\Http\Resources\BuildingProductionsResource;
use App\Http\Resources\BuildingResourceResource;
use App\Http\Resources\FleetStatusDictionaryResource;
use App\Http\Resources\FleetTaskDictionaryResource;
use App\Http\Resources\ResearchDependencyResource;
use App\Http\Resources\ResearchDictionaryResource;
use App\Http\Resources\ResearchResourceResource;
use App\Http\Resources\ResourceDictionaryResource;
use App\Http\Resources\UserResearchResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceResource;
use App\Http\Resources\WarshipDependencyResource;
use App\Http\Resources\WarshipDictionaryResource;
use App\Models\BuildingDependency;
use App\Models\BuildingDictionary;
use App\Models\BuildingProduction;
use App\Models\BuildingResource;
use App\Models\FleetStatusDictionary;
use App\Models\FleetTaskDictionary;
use App\Models\ResearchDependency;
use App\Models\ResearchDictionary;
use App\Models\ResearchResource;
use App\Models\Resource;
use App\Models\WarshipDependency;
use App\Models\WarshipDictionary;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function get()
    {
        $user = Auth::user();

        return new UserResource($user);
    }

    public function getDictionaries()
    {
        $user = Auth::user();

        $buildings            = BuildingDictionary::get();
        $buildingResources    = BuildingResource::get();
        $buildingProductions  = BuildingProduction::get();
        $buildingDependencies = BuildingDependency::get();

        $researches           = ResearchDictionary::get();
        $researchResources    = ResearchResource::get();
        $researchDependencies = ResearchDependency::get();
        $userResearches       = $user->researches;

        $warshipsDictionary  = WarshipDictionary::get();
        $warshipDependencies = WarshipDependency::get();

        $fleetTasksDictionary    = FleetTaskDictionary::get();
        $fleetStatusesDictionary = FleetStatusDictionary::get();

        $resourcesDictionary = Resource::get();

        $unreadMessagesNumber = $user->unreadMessagesNumber();

        $tradeSystem      = $userResearches->where('research_id', config('constants.RESEARCHES.TRADE_SYSTEM'))->first();
        $tradeFleetNumber = 0;
        if ($tradeSystem) {
            $tradeFleetNumber = $tradeSystem->lvl;
        }

        $expeditionSystem      = $userResearches->where('research_id', config('constants.RESEARCHES.EXPEDITION_SYSTEM'))->first();
        $expeditionFleetNumber = 0;
        if ($expeditionSystem) {
            $expeditionFleetNumber = $expeditionSystem->lvl;
        }

        return [
            'buildings'               => BuildingDictionaryResource::collection($buildings),
            'buildingResources'       => BuildingResourceResource::collection($buildingResources),
            'researches'              => ResearchDictionaryResource::collection($researches),
            'researchResources'       => ResearchResourceResource::collection($researchResources),
            'userResearches'          => UserResearchResource::collection($userResearches),
            'warshipsDictionary'      => WarshipDictionaryResource::collection($warshipsDictionary->load('requiredResources')),
            'buildingsProduction'     => BuildingProductionsResource::collection($buildingProductions),
            'fleetTasksDictionary'    => FleetTaskDictionaryResource::collection($fleetTasksDictionary),
            'fleetStatusesDictionary' => FleetStatusDictionaryResource::collection($fleetStatusesDictionary),
            'buildingDependencies'    => BuildingDependencyResource::collection($buildingDependencies),
            'researchDependencies'    => ResearchDependencyResource::collection($researchDependencies),
            'warshipDependencies'     => WarshipDependencyResource::collection($warshipDependencies),
            'unreadMessagesNumber'    => $unreadMessagesNumber,
            'messageTemplates'        => [
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_TRADE_START_TRADING'),
                    'title'      => 'Trade Fleet starts trading',
                    'content'    => 'It will take some time'
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_TRADE_IS_BACK'),
                    'title'      => 'Trade Fleet is back',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_MOVE_DONE'),
                    'title'      => 'Fleet Moved successfully',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_MOVE_CANT'),
                    'title'      => 'Fleet can not be moved',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_MOVE_WENT_BACK'),
                    'title'      => 'Fleet is back',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_RESOURCES'),
                    'title'      => 'Expedition Fleet found resources',
                    'content'    => 'Expedition has success. Your fleet found some resources.'
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_STORM'),
                    'title'      => 'Expedition Fleet was in storm',
                    'content'    => 'Expedition Fleet was caught in a storm. Some warships have been destroyed',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_LOST'),
                    'title'      => 'Expedition Fleet has been lost',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_NOTHING'),
                    'title'      => 'Expedition Fleet found nothing',
                    'content'    => 'Expedition Fleet is returning with nothing'
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_EXPEDITION_IS_BACK'),
                    'title'      => 'Expedition Fleet is back',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.BATTLE_ATTACK_HAPPENED'),
                    'title'      => 'You attack island',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.BATTLE_DEFEND_HAPPENED'),
                    'title'      => 'Your island has been attacked',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_TAKE_OVER_DONE'),
                    'title'      => 'You took island over successfully',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_TAKE_OVER_CANT_DONE'),
                    'title'      => 'Fleet could not take the city over',
                ],
                [
                    'templateId' => config('constants.MESSAGE_TEMPLATE_IDS.FLEET_TAKE_OVER_DONE_IS_BACK'),
                    'title'      => 'Take Over Fleet is back',
                ],
            ],
            'resourcesDictionary'     => ResourceDictionaryResource::collection($resourcesDictionary),
            'resourcesDictionaryTypes'     => [
                'common'   => config('constants.RESOURCE_TYPE_IDS.COMMON'),
                'card'     => config('constants.RESOURCE_TYPE_IDS.CARD'),
                'research' => config('constants.RESOURCE_TYPE_IDS.RESEARCH'),
            ],
            'maxFleetNumbers'         => [
                'trade'      => $tradeFleetNumber,
                'expedition' => $expeditionFleetNumber
            ]
        ];
    }

    public function resources()
    {
        $user = Auth::user();

        $userResources = $user->resources;

        return [
            'resources' => UserResourceResource::collection($userResources)
        ];
    }
}
