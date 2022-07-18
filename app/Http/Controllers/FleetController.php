<?php

namespace App\Http\Controllers;

use App\Services\FleetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FleetController extends Controller
{
    public function send(Request $request, FleetService $fleetService) {
        //dump($request->coordX);
        //dd($request->all());

        $user = Auth::user();
        // TODO calculate gold for sending fleet
        $response = $fleetService->send($request, $user);

        //dd($response);

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
