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
use App\Http\Resources\UserResearchResource;
use App\Http\Resources\UserResource;
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

        $warships            = WarshipDictionary::get();
        $warshipDependencies = WarshipDependency::get();

        $fleetTasksDictionary    = FleetTaskDictionary::get();
        $fleetStatusesDictionary = FleetStatusDictionary::get();

        return [
            'buildings'               => BuildingDictionaryResource::collection($buildings),
            'buildingResources'       => BuildingResourceResource::collection($buildingResources),
            'researches'              => ResearchDictionaryResource::collection($researches),
            'researchResources'       => ResearchResourceResource::collection($researchResources),
            'userResearches'          => UserResearchResource::collection($userResearches),
            'warships'                => WarshipDictionaryResource::collection($warships),
            'buildingsProduction'     => BuildingProductionsResource::collection($buildingProductions),
            'fleetTasksDictionary'    => FleetTaskDictionaryResource::collection($fleetTasksDictionary),
            'fleetStatusesDictionary' => FleetStatusDictionaryResource::collection($fleetStatusesDictionary),
            'buildingDependencies'    => BuildingDependencyResource::collection($buildingDependencies),
            'researchDependencies'    => ResearchDependencyResource::collection($researchDependencies),
            'warshipDependencies'     => WarshipDependencyResource::collection($warshipDependencies),
        ];
    }
}
