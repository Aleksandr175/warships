<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResearchQueueResource;
use App\Http\Resources\ResearchResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ResearchController extends Controller
{
    public function get()
    {
        $userId = Auth::user()->id;

        $user = User::where('id', $userId)->first();

        return [
            'researchQueue' => $user->researchesQueue ? new ResearchQueueResource($user->researchesQueue) : [],
            'researches'    => ResearchResource::collection($user->researches)
        ];
    }
}
