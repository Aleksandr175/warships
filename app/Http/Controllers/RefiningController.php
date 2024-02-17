<?php

namespace App\Http\Controllers;

use App\Http\Resources\RefiningQueueResource;
use App\Http\Resources\RefiningRecipeResource;
use App\Models\City;
use App\Models\RefiningRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefiningController extends Controller
{
    public function get(Request $request)
    {
        $userId = Auth::user()->id;
        $cityId = $request->get('cityId');

        $city = City::where('id', $cityId)->where('user_id', $userId)->first();

        // TODO: add checking lvl of refining building
        $recipes = RefiningRecipe::get();

        if ($city && $city->id) {
            return [
                'refiningRecipes' => RefiningRecipeResource::collection($recipes),
                'refiningQueue'   => $city->refiningQueue ? RefiningQueueResource::collection($city->refiningQueue) : [],
            ];
        }

        return abort(403);

    }
}
