<?php

namespace App\Http\Controllers;

use App\Http\Resources\BattleLogDetailResource;
use App\Http\Resources\BattleLogResource;
use App\Models\BattleLog;
use App\Models\BattleLogDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BattleLogController extends Controller
{
    public function get()
    {
        $userId = Auth::user()->id;

        $battleLogs        = BattleLog::where('attacker_user_id', $userId)->orWhere('defender_user_id', $userId)->get();
        $battleLogIds      = $battleLogs->pluck('battle_log_id');
        $battleLogsDetails = BattleLogDetail::whereIn('battle_log_id', $battleLogIds)->get();

        return [
            'battleLogs'        => BattleLogResource::collection($battleLogs),
            'battleLogsDetails' => BattleLogDetailResource::collection($battleLogsDetails)
        ];
    }
}
