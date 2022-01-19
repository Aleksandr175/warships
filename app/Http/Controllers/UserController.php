<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildingDictionaryResource;
use App\Http\Resources\UserResource;
use App\Models\BuildingDictionary;
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

        return [
            'buildings' => BuildingDictionaryResource::collection($buildings),
            'researches' => [],
            'warships' => []
        ];
    }
}
