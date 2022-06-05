<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildingDictionaryResource;
use App\Http\Resources\BuildingProductionsResource;
use App\Http\Resources\BuildingResourceResource;
use App\Http\Resources\ResearchDictionaryResource;
use App\Http\Resources\ResearchResourceResource;
use App\Http\Resources\UserResearchResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WarshipDictionaryResource;
use App\Http\Resources\WarshipResourceResource;
use App\Models\BuildingDictionary;
use App\Models\BuildingProduction;
use App\Models\BuildingResource;
use App\Models\Research;
use App\Models\ResearchDictionary;
use App\Models\ResearchResource;
use App\Models\ShipDictionary;
use App\Models\ShipResource;
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

        $warships = ShipDictionary::get();
        $warshipsResources = ShipResource::get();

        return [
            'buildings' => BuildingDictionaryResource::collection($buildings),
            'buildingResources' => BuildingResourceResource::collection($buildingResources),
            'researches' => ResearchDictionaryResource::collection($researches),
            'researchResources' => ResearchResourceResource::collection($researchResources),
            'userResearches' => UserResearchResource::collection($userResearches),
            'warships' => WarshipDictionaryResource::collection($warships),
            'warshipsResources' => WarshipResourceResource::collection($warshipsResources),
            'buildingsProduction' => BuildingProductionsResource::collection($buildingProductions)
        ];
    }
}
