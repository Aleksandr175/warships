<?php

namespace App\Http\Controllers;

use App\Http\Resources\MapCityResource;
use App\Models\Adventure;
use App\Models\City;
use App\Services\CityService;
use Illuminate\Support\Facades\Auth;

class AdventureController extends Controller
{
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function getMap()
    {
        $userId = Auth::user()->id;

        $adventure = Adventure::where('user_id', $userId)->first();

        if (!$adventure) {
            $adventure = $this->generateAdventure($userId, 1);
        }

        $cities = City::where('adventure_id', $adventure->id)->get();

        return [
            'cities' => MapCityResource::collection($cities)
        ];
    }

    public function generateAdventure(int $userId, int $lvl)
    {
        $archipelagoController = new ArchipelagoController();
        $newArchipelago      = $archipelagoController->createArchipelagoForAdventure();

        $adventure = Adventure::create([
            'user_id'         => $userId,
            'adventure_level' => $lvl,
            'archipelago_id'  => $newArchipelago->id,
            'status'          => 1
        ]);

        // generate new cities for adventure
        $this->cityService->generateCitiesForAdventure($adventure, $newArchipelago);

        return $adventure;
    }

    public function isAdventureCompleted()
    {
        // generate new adventure
    }
}
