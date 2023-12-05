<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityShortInfoResource;
use App\Http\Resources\FleetDetailResource;
use App\Http\Resources\FleetIncomingResource;
use App\Http\Resources\FleetResource;
use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Services\FleetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FleetController extends Controller
{
    public function get(Request $request)
    {
        $userId = Auth::user()->id;

        $cities = City::where('user_id', $userId)->get();
        $userCityIds     = $cities->pluck('id');

        $fleets         = Fleet::whereIn('city_id', $userCityIds)->get();
        $incomingFleets = Fleet::whereIn('target_city_id', $userCityIds)->whereNotIn('city_id', $userCityIds)->get();

        $fleetIds     = $fleets->pluck('id');
        $fleetDetails = FleetDetail::getFleetDetails($fleetIds);

        $cityIds       = $fleets->pluck('city_id')->toArray();
        $targetCityIds = $fleets->pluck('target_city_id')->toArray();

        $incomingCityIds       = $incomingFleets->pluck('city_id')->toArray();
        $incomingTargetCityIds = $incomingFleets->pluck('target_city_id')->toArray();

        $cities = City::whereIn('id', array_merge($cityIds, $targetCityIds, $incomingCityIds, $incomingTargetCityIds))->get();

        return [
            'fleets'         => FleetResource::collection($fleets),
            'fleetDetails'   => FleetDetailResource::collection($fleetDetails),
            'cities'         => CityShortInfoResource::collection($cities),
            'fleetsIncoming' => FleetIncomingResource::collection($incomingFleets),
        ];
    }

    /*public function getFleetByCity(Request $request)
    {
        $userId = Auth::user()->id;
        $cityId = $request->get('cityId');

        $city = City::where('id', $cityId)->where('user_id', $userId)->first();

        $fleets         = $city->fleets;
        $incomingFleets = $city->incomingFleets;

        dd($fleets, $incomingFleets);

        $fleetIds     = $fleets->pluck('id');
        $fleetDetails = FleetDetail::getFleetDetails($fleetIds);

        $cityIds       = $fleets->pluck('city_id')->toArray();
        $targetCityIds = $fleets->pluck('target_city_id')->toArray();

        $incomingCityIds       = $incomingFleets->pluck('city_id')->toArray();
        $incomingTargetCityIds = $incomingFleets->pluck('target_city_id')->toArray();

        $cities = City::whereIn('id', array_merge($cityIds, $targetCityIds, $incomingCityIds, $incomingTargetCityIds))->get();

        if ($city && $city->id) {
            return [
                'fleets'         => FleetResource::collection($fleets),
                'fleetDetails'   => FleetDetailResource::collection($fleetDetails),
                'cities'         => CityShortInfoResource::collection($cities),
                'fleetsIncoming' => FleetIncomingResource::collection($incomingFleets),
            ];
        }

        return abort(403);
    }*/

    public function send(Request $request, FleetService $fleetService)
    {
        //dump($request->coordX);
        //dd($request->all());

        $user = Auth::user();
        // TODO calculate gold for sending fleet
        $response = $fleetService->send($request, $user);

        return $response;

        // check target coords - that it exists
        // check task id
        // check fleet details: qty, max qty of warships and total qty

        // TODO
        // check if we have enough gold for task - take it from player

        // make fleet, set target, details
        // calculate time to target
        // remove warships from city
    }
}
