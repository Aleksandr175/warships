<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\BattleLogRequest;
use App\Http\Resources\BattleLogDetailResource;
use App\Http\Resources\BattleLogResource;
use App\Http\Resources\CityShortInfoResource;
use App\Models\BattleLog;
use App\Models\BattleLogDetail;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

class BattleLogController extends Controller
{
    public function get()
    {
        $userId = Auth::user()->id;

        $battleLogs      = BattleLog::where('attacker_user_id', $userId)->orWhere('defender_user_id', $userId)->paginate(10);
        $battleLogsCount = BattleLog::where('attacker_user_id', $userId)->orWhere('defender_user_id', $userId)->count();

        $cityIds = $battleLogs->pluck('city_id')->toArray();

        $cities = City::whereIn('id', array_merge($cityIds))->get();

        return [
            'battleLogs'      => BattleLogResource::collection($battleLogs),
            'battleLogsCount' => $battleLogsCount,
            'cities'          => CityShortInfoResource::collection($cities),
        ];
    }

    public function getBattleDetails(BattleLogRequest $request)
    {
        $userId      = Auth::user()->id;
        $battleLogId = $request->battleLogId;
        $battleLog   = BattleLog::where('battle_log_id', $battleLogId)->first();

        if ($battleLog->attacker_user_id === $userId || $battleLog->defender_user_id === $userId) {
            $battleLogDetails = BattleLogDetail::where('battle_log_id', $battleLogId)->get();

            return [
                'battleLog'        => new BattleLogResource($battleLog),
                'battleLogDetails' => BattleLogDetailResource::collection($battleLogDetails),
            ];
        }

        abort(403);
    }
}
