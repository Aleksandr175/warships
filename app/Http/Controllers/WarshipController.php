<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityWarshipQueueResource;
use App\Http\Resources\WarshipImprovementResource;
use App\Http\Resources\WarshipResource;
use App\Models\City;
use App\Services\WarshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarshipController extends Controller
{
    public function get(Request $request)
    {
        $user   = Auth::user();
        $cityId = $request->get('cityId');

        $city = City::where('id', $cityId)->where('user_id', $user->id)->first();

        if (!$city) {
            return abort(403);
        }

        $maxWarshipSlots = (new WarshipService())->getMaxWarshipSlots($city);

        $warshipImprovements = $user->warshipImprovements;

        if ($city && $city->id) {
            return [
                'warships'            => $city->warships ? WarshipResource::collection($city->warships) : [],
                'warshipSlots'        => $maxWarshipSlots,
                'warshipQueue'        => $city->warshipQueue && count($city->warshipQueue) ? CityWarshipQueueResource::collection($city->warshipQueue) : [],
                'warshipImprovements' => WarshipImprovementResource::collection($warshipImprovements),
            ];
        }

        return abort(403);
    }
}
