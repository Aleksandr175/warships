<?php

namespace App\Http\Controllers;

use App\Http\Resources\RefiningQueueResource;
use App\Http\Resources\RefiningRecipeResource;
use App\Models\City;
use App\Models\RefiningRecipe;
use App\Services\RefiningQueueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefiningController extends Controller
{
    public function get(Request $request)
    {
        $userId = Auth::user()->id;
        $cityId = $request->get('cityId');

        $city = City::where('id', $cityId)->where('user_id', $userId)->first();


        $refiningService = new RefiningQueueService();
        $refiningSlots = $refiningService->getMaxAvailableSlots($city);

        if ($city && $city->id) {
            return [
                'refiningQueue' => $city->refiningQueue ? RefiningQueueResource::collection($city->refiningQueue) : [],
                'refiningSlots' => $refiningSlots
            ];
        }

        return abort(403);
    }

    public function getRecipes()
    {
        // TODO: add checking lvl of refining building
        $recipes = RefiningRecipe::get();

        return [
            'refiningRecipes' => RefiningRecipeResource::collection($recipes),
        ];
    }
}
