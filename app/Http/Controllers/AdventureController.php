<?php

namespace App\Http\Controllers;

use App\Http\Resources\MapAdventureCityResource;
use App\Http\Resources\WarshipResource;
use App\Models\Adventure;
use App\Models\City;
use App\Models\Warship;
use App\Services\CityService;
use App\Services\WarshipService;
use Illuminate\Support\Facades\Auth;

class AdventureController extends Controller
{
    public function __construct(CityService $cityService, WarshipService $warshipService)
    {
        $this->cityService = $cityService;
        $this->warshipService = $warshipService;
    }

    public function getMap()
    {
        $userId = Auth::user()->id;

        $adventure = Adventure::where('user_id', $userId)->first();

        if (!$adventure) {
            $adventure = $this->generateAdventure($userId, 1);
        }

        $cities = City::where('adventure_id', $adventure->id)->get();

        $warships = Warship::whereIn('city_id', $cities->pluck('id'))->get();

        return [
            'cities'   => MapAdventureCityResource::collection($cities),
            'warships' => WarshipResource::collection($warships)
        ];
    }

    public function generateAdventure(int $userId, int $lvl)
    {
        $archipelagoController = new ArchipelagoController();
        $newArchipelago        = $archipelagoController->createArchipelagoForAdventure();

        $adventure = Adventure::create([
            'user_id'         => $userId,
            'adventure_level' => $lvl,
            'archipelago_id'  => $newArchipelago->id,
            'status'          => 1
        ]);

        // generate new cities for adventure
        $this->cityService->generateCitiesForAdventure($adventure, $newArchipelago);
        $this->warshipService->generateWarshipsForAdventureCities($adventure, $newArchipelago);

        return $adventure;
    }

    public function isAdventureCompleted()
    {
        // generate new adventure
    }
}
