<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResearchImprovementResource;
use Illuminate\Support\Facades\Auth;

class ResearchImprovementController extends Controller
{
    public function get()
    {
        $user = Auth::user();

        $researchImprovements = $user->researchImprovements;

        return [
            'researchImprovements' => ResearchImprovementResource::collection($researchImprovements),
        ];
    }
}
