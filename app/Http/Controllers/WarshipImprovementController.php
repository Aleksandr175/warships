<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\WarshipImprovementRequest;
use App\Http\Resources\UserResourceResource;
use App\Http\Resources\WarshipImprovementRecipeResource;
use App\Http\Resources\WarshipImprovementResource;
use App\Models\UserResource;
use App\Models\WarshipImprovement;
use App\Models\WarshipImprovementRecipe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarshipImprovementController extends Controller
{
    public function get()
    {
        $user = Auth::user();

        $warshipImprovements = $user->warshipImprovements;
        $improvementRecipes  = $this->getWarshipImprovementRecipes();

        return [
            'warshipImprovementRecipes' => WarshipImprovementRecipeResource::collection($improvementRecipes),
            'warshipImprovements'       => WarshipImprovementResource::collection($warshipImprovements),
        ];
    }

    public function improve(WarshipImprovementRequest $request)
    {
        $user     = Auth::user();
        $recipeId = $request->only('recipeId');

        $improvementRecipe = WarshipImprovementRecipe::find($recipeId);

        if (!$improvementRecipe) {
            return abort(403);
        }

        $improvementRecipe = $improvementRecipe[0];

        if ($improvementRecipe->level > 1) {
            // check if we have previous improvement
            $previousImprovementRecipe = WarshipImprovement::where('user_id', $user->id)
                ->where('warship_id', $improvementRecipe->warship_id)
                ->where('improvement_type', $improvementRecipe->improvement_type)
                ->where('level', $improvementRecipe->level - 1)
                ->first();

            if ($previousImprovementRecipe) {
                // ok
            } else {
                return abort(403);
            }
        }

        // check resources
        $userResources = $user->resources->toArray();
        $canImprove    = false;

        foreach ($userResources as $userResource) {
            if ($userResource['resource_id'] === $improvementRecipe->resource_id) {
                if ($userResource['qty'] >= $improvementRecipe->qty) {
                    $canImprove = true;
                } else {
                    break;
                }
            }
        }

        if (!$canImprove) {
            return abort(403); // not enough resources
        }

        $this->makeImprovement($improvementRecipe, $user);
        $this->subtractUserResource($user, $improvementRecipe->resource_id, $improvementRecipe->qty);

        $warshipImprovements = $user->warshipImprovements;
        $improvementRecipes  = $this->getWarshipImprovementRecipes();

        return [
            'userResources'             => UserResourceResource::collection($user->load('resources')->resources),
            'warshipImprovementRecipes' => WarshipImprovementRecipeResource::collection($improvementRecipes),
            'warshipImprovements'       => WarshipImprovementResource::collection($warshipImprovements),
        ];
    }

    public function makeImprovement($improvementRecipe, $user): void
    {
        $improvement = WarshipImprovement::where('user_id', $user->id)
            ->where('warship_id', $improvementRecipe->warship_id)
            ->where('improvement_type', $improvementRecipe->improvement_type)
            ->first();

        if ($improvement) {
            $improvement->update([
                'level'               => $improvementRecipe->level,
                'percent_improvement' => $improvementRecipe->percent_improvement
            ]);
        } else {
            WarshipImprovement::create([
                'user_id'             => $user->id,
                'warship_id'          => $improvementRecipe->warship_id,
                'improvement_type'    => $improvementRecipe->improvement_type,
                'level'               => $improvementRecipe->level,
                'percent_improvement' => $improvementRecipe->percent_improvement
            ]);
        }
    }

    public function subtractUserResource($user, $resourceId, $qty): void
    {
        UserResource::where('user_id', $user->id)->where('resource_id', $resourceId)->decrement('qty', $qty);
    }

    public function getWarshipImprovementRecipes()
    {
        return WarshipImprovementRecipe::leftJoin('warship_improvements', function ($join) {
            $join->on('warship_improvement_recipes.warship_id', '=', 'warship_improvements.warship_id')
                ->on('warship_improvement_recipes.improvement_type', '=', 'warship_improvements.improvement_type');
        })
            ->select('warship_improvement_recipes.*')
            ->where(function ($query) {
                // Get recipes with current_lvl greater than the current level in warship_improvements table
                $query->whereRaw('warship_improvement_recipes.level = IFNULL(warship_improvements.level, 0) + 1');
            })
            ->orWhere(function ($query) {
                // Get first level recipe if no row exists in warship_improvements table
                $query->whereNotExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                        ->from('warship_improvements')
                        ->whereRaw('warship_improvements.warship_id = warship_improvement_recipes.warship_id')
                        ->whereRaw('warship_improvements.improvement_type = warship_improvement_recipes.improvement_type');
                });
                // Ensure the current level is 1
                $query->where('warship_improvement_recipes.level', 1);
            })
            ->groupBy('warship_improvement_recipes.id') // Group by the primary key of warship_improvement_recipes
            ->orderBy('warship_improvement_recipes.warship_id')
            ->orderBy('warship_improvement_recipes.improvement_type')
            ->get();
    }
}
