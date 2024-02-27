<?php

namespace App\Services;

use App\Http\Requests\Api\RefiningRequest;
use App\Models\City;
use App\Models\RefiningQueue;
use App\Models\RefiningRecipe;
use Carbon\Carbon;

class RefiningQueueService
{
    protected $userId;
    protected $city;

    public function handle(RefiningQueue $refiningQueue): void
    {
        $cityId = $refiningQueue->city_id;

        $cityService = new CityService();
        $cityService->addResourceToCity($cityId, $refiningQueue->output_resource_id, $refiningQueue->output_qty);

        $refiningQueue->delete();
    }

    public function canStore(City $city, $recipeId, $qty): bool
    {
        $recipe = RefiningRecipe::where('id', $recipeId)->first();

        if (!$recipe) {
            return false;
        }

        // TODO: check refining lvl required
        // TODO: check available slots
        $hasAllRequirements = $this->hasAllRequirements($city, $recipe, $qty);

        if (!$hasAllRequirements) {
            return false;
        }

        // found out what resources we need for refining
        $requiredResourceId  = $recipe->input_resource_id;
        $requiredResourceQty = $recipe->input_qty * $qty;
        $cityResources       = $city->resources;

        $hasEnoughRequiredResourceQty = false;
        // Find the corresponding resource in the city resources
        foreach ($cityResources as $cityResource) {
            if ($cityResource->resource_id === $requiredResourceId
                && $cityResource->qty >= $requiredResourceQty) {
                $hasEnoughRequiredResourceQty = true;
                break;
            }
        }

        return $hasEnoughRequiredResourceQty;
    }

    public function hasAllRequirements(City $city, RefiningRecipe $recipe, $qty): bool
    {
        // TODO: check refining lvl required
        $requiredRefiningBuildingLvl = $recipe->refining_level_required;

        $hasAllRequirements = true;

        $refiningQueue = $city->refiningQueue;

        // TODO: calculate max available slots
        if (count($refiningQueue) > 4) {
            $hasAllRequirements = false;
        }

        return $hasAllRequirements;
    }

    public function store($userId, RefiningRequest $request)
    {
        $data     = $request->only('recipeId', 'cityId', 'qty');
        $cityId   = $data['cityId'];
        $recipeId = $data['recipeId'];
        $qty      = $data['qty'];

        $city = City::where('id', $cityId)->where('user_id', $userId)->first();

        if ($city && $city->id && $this->canStore($city, $recipeId, $qty)) {
            return $this->updateQueue($city, $recipeId, $qty);
        }

        return abort(403);
    }

    public function updateQueue($city, $recipeId, $qty)
    {
        $cityResources = $city->resources;

        $recipe = RefiningRecipe::find($recipeId);

        // found out what resources we need for refining
        $requiredResourceId  = $recipe->input_resource_id;
        $requiredResourceQty = $recipe->input_qty * $qty;

        $timeRequired = $recipe->time * $qty;

        // Subtract the required amount of resource from the city
        $cityResource = $cityResources->where('resource_id', $requiredResourceId)->first();

        // Subtract the required quantity from the city's resource
        $cityResource->qty -= $requiredResourceQty;

        $cityResource->save();

        return RefiningQueue::create([
            'city_id'            => $city->id,
            'refining_recipe_id' => $recipe->id,
            'input_resource_id'  => $requiredResourceId,
            'input_qty'          => $requiredResourceQty,
            'output_resource_id' => $recipe->output_resource_id,
            'output_qty'         => $recipe->output_qty * $qty,
            'time'               => $timeRequired,
            'deadline'           => Carbon::now()->addSeconds($timeRequired)
        ]);
    }
}
