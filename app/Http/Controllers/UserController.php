<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildingDictionaryResource;
use App\Http\Resources\BuildingProductionsResource;
use App\Http\Resources\BuildingResourceResource;
use App\Http\Resources\ResearchDictionaryResource;
use App\Http\Resources\ResearchResourceResource;
use App\Http\Resources\UserResearchResource;
use App\Http\Resources\UserResource;
use App\Models\BuildingDictionary;
use App\Models\BuildingProduction;
use App\Models\BuildingResource;
use App\Models\Research;
use App\Models\ResearchDictionary;
use App\Models\ResearchResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function get() {
        $user = Auth::user();

        return new UserResource($user);
    }

    public function getDictionaries() {
        $user = Auth::user();

        $buildings = BuildingDictionary::get();
        $buildingResources = BuildingResource::get();
        $buildingProductions = BuildingProduction::get();

        $researches = ResearchDictionary::get();
        $researchResources = ResearchResource::get();
        $userResearches = $user->researches;

        return [
            'buildings' => BuildingDictionaryResource::collection($buildings),
            'buildingResources' => BuildingResourceResource::collection($buildingResources),
            'researches' => ResearchDictionaryResource::collection($researches),
            'researchResources' => ResearchResourceResource::collection($researchResources),
            'userResearches' => UserResearchResource::collection($userResearches),
            'warships' => [],
            'buildingsProduction' => BuildingProductionsResource::collection($buildingProductions)
        ];
    }
}
