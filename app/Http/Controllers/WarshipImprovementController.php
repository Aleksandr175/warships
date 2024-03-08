<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarshipImprovementRecipeResource;
use App\Http\Resources\WarshipImprovementResource;
use App\Models\WarshipImprovementRecipe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarshipImprovementController extends Controller
{
    public function get()
    {
        $user = Auth::user();

        $warshipImprovements = $user->warshipImprovements;

        $improvementRecipes = WarshipImprovementRecipe::leftJoin('warship_improvements', function($join) {
            $join->on('warship_improvement_recipes.warship_id', '=', 'warship_improvements.warship_id')
                ->on('warship_improvement_recipes.improvement_type', '=', 'warship_improvements.improvement_type');
        })
            ->select('warship_improvement_recipes.*')
            ->where(function($query) {
                // Get recipes with current_lvl greater than the current level in warship_improvements table
                $query->whereRaw('warship_improvement_recipes.level = IFNULL(warship_improvements.level, 0) + 1');
            })
            ->orWhere(function($query) {
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

        return [
            'warshipImprovementRecipes' => WarshipImprovementRecipeResource::collection($improvementRecipes),
            'warshipImprovements'       => WarshipImprovementResource::collection($warshipImprovements),
        ];
    }
}
