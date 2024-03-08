<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarshipImprovementRecipeResource;
use App\Http\Resources\WarshipImprovementResource;
use App\Models\WarshipImprovementRecipe;
use Illuminate\Support\Facades\Auth;

class WarshipImprovementController extends Controller
{
    public function get()
    {
        $user = Auth::user();

        $warshipImprovementRecipes = WarshipImprovementRecipe::get();

        $warshipImprovements = $user->warshipImprovements;

        return [
            'warshipImprovementRecipes' => WarshipImprovementRecipeResource::collection($warshipImprovementRecipes),
            'warshipImprovements'       => WarshipImprovementResource::collection($warshipImprovements),
        ];
    }
}
