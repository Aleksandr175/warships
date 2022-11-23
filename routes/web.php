<?php

use App\Models\Fleet;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');

    return "Cleared!";
});

Route::get('/test-event', [\App\Http\Controllers\TestController::class, 'index']);

Route::get('/server-start', function () {
    \App\Jobs\ResourceJob::dispatch()->onQueue('resource');
    \App\Jobs\WarshipQueueJob::dispatch()->onQueue('warshipQueue');
    \App\Jobs\FleetJob::dispatch()->onQueue('fleet');

    return "Server started!";
});

Route::get('/test-battle', function (\App\Services\BattleService $battleService) {
    $fleetQueue = Fleet::where('fleet_task_id', 3)->get();

    foreach ($fleetQueue as $fleet) {
        $battleService->handle($fleet);
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/api/user', [\App\Http\Controllers\UserController::class, 'get']);
    Route::get('/api/buildings', [\App\Http\Controllers\BuildingController::class, 'get']);
    Route::get('/api/researches', [\App\Http\Controllers\ResearchController::class, 'get']);
    Route::get('/api/warships', [\App\Http\Controllers\WarshipController::class, 'get']);
    Route::get('/api/dictionaries', [\App\Http\Controllers\UserController::class, 'getDictionaries']);
    Route::get('/api/city/{cityId}', [\App\Http\Controllers\CityController::class, 'getCityResources']);

    Route::post('/api/build', [\App\Http\Controllers\CityBuildingQueueController::class, 'build']);
    Route::post('/api/build/{buildingId}/cancel', [\App\Http\Controllers\CityBuildingQueueController::class, 'cancel'])->where('buildingId', '[0-9]+');

    Route::post('/api/researches/{researchId}/run', [\App\Http\Controllers\ResearchQueueController::class, 'run']);
    Route::post('/api/researches/{researchId}/cancel', [\App\Http\Controllers\ResearchQueueController::class, 'cancel']);

    Route::post('/api/warships/create', [\App\Http\Controllers\WarshipQueueController::class, 'run']);

    Route::get('/api/map', [\App\Http\Controllers\MapController::class, 'get']);

    Route::get('/api/fleets', [\App\Http\Controllers\FleetController::class, 'get']);
    Route::post('/api/fleets/send', [\App\Http\Controllers\FleetController::class, 'send']);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/buildings', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/researches', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/warships', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/map', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/fleets', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
