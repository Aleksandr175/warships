<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index() {
        $user = Auth::user();
        $cities = $user->cities()->get();

        TestEvent::dispatch($cities);
    }
}
