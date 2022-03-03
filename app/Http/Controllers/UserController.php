<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildingDictionaryResource;
use App\Http\Resources\BuildingProductionsResource;
use App\Http\Resources\BuildingResourceResource;
use App\Http\Resources\UserResource;
use App\Models\BuildingDictionary;
use App\Models\BuildingProduction;
use App\Models\BuildingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function get() {
        $user = Auth::user();

        return new UserResource($user);
    }

    public function getDictionaries() {
        $buildings = BuildingDictionary::get();
        $buildingResources = BuildingResource::get();
        $buildingProductions = BuildingProduction::get();

        return [
            'buildings' => BuildingDictionaryResource::collection($buildings),
            'buildingResources' => BuildingResourceResource::collection($buildingResources),
            'researches' => [],
            'warships' => [],
            'buildingsProduction' => BuildingProductionsResource::collection($buildingProductions)
        ];
    }
}
