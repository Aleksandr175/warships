<?php

namespace App\Http\Controllers;

use App\Models\Archipelago;

class ArchipelagoController extends Controller
{
    public function createArchipelagoForAdventure()
    {
        return Archipelago::create([
            'type' => config('constants.ARCHIPELAGO_TYPES.ADVENTURE'),
        ]);
    }
}
