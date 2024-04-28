<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResearchQueueResource;
use App\Http\Resources\ResearchResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ResearchController extends Controller
{
    public function get() {
        $userId = Auth::user()->id;

        $user = User::where('id', $userId)->first();
        $queue = $user->researchesQueue()->first();

        if ($queue && $queue->id) {
            if ($queue->deadline <= Carbon::now()) {
                // add lvl
                if ($user->research($queue->research_id)) {
                    $user->research($queue->research_id)->increment('lvl');
                } else {
                    // create new research
                    $user->researches()->create([
                        'research_id' => $queue->research_id,
                        'user_id' => $userId,
                        'lvl' => 1,
                    ]);
                }

                $queue->resources()->delete();
                $queue->delete();
                $user->researchesQueue()->delete();
            }
        }

        return [
            'queue' => $user->researchesQueue ? new ResearchQueueResource($user->researchesQueue) : [],
            'researches' => ResearchResource::collection($user->researches)
        ];
    }
}
