<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityWarshipQueueResource;
use App\Http\Resources\WarshipResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarshipController extends Controller
{
    public function get(Request $request) {
        $userId = Auth::user()->id;
        $cityId = $request->get('cityId');

        $city = City::where('id', $cityId)->where('user_id', $userId)->first();

        // TODO: calculate slots depends on shipyard building
        $warshipSlots = 2;

        if ($city && $city->id) {
            return [
                'warships' => $city->warships ? WarshipResource::collection($city->warships) : [],
                'warshipSlots' => $warshipSlots,
                'queue' => $city->warshipQueue && count($city->warshipQueue) ? CityWarshipQueueResource::collection($city->warshipQueue) : [],
            ];
        }

        return abort(403);
    }
}
