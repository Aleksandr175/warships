<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\BuildRequest;
use App\Models\CityBuildingQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityBuildingQueueController extends Controller
{
    public function build(BuildRequest $request) {
        $user = Auth::user();
        $data = $request->only('cityId', 'buildingId');
        $cityId = $data['cityId'];
        $buildingId = $data['buildingId'];

        $city = $user->cities()->where('id', $cityId)->first();

        if ($city && $city->id && $city->canBuild($buildingId)) {
            // check if queue is empty
            $queue = CityBuildingQueue::where('city_id', $city->id)->first();

            if (!$queue) {
                // order build
                $city->build($buildingId);

                return true;
            }
        }

        return abort(403);
    }
}
