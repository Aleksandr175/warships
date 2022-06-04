<?php

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

Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');

    return "Cleared!";
});

Route::get('/server-start', function() {
    \App\Jobs\ResourceJob::dispatch();
});

Route::middleware('auth')->group(function () {
    Route::get('/api/user', [\App\Http\Controllers\UserController::class, 'get']);
    Route::get('/api/buildings', [\App\Http\Controllers\BuildingController::class, 'get']);
    Route::get('/api/researches', [\App\Http\Controllers\ResearchController::class, 'get']);
    Route::get('/api/dictionaries', [\App\Http\Controllers\UserController::class, 'getDictionaries']);
    Route::get('/api/city/{cityId}', [\App\Http\Controllers\CityController::class, 'getCityResources']);

    Route::post('/api/build', [\App\Http\Controllers\CityBuildingQueueController::class, 'build']);
    Route::post('/api/build/{buildingId}/cancel', [\App\Http\Controllers\CityBuildingQueueController::class, 'cancel'])->where('buildingId', '[0-9]+');

    Route::post('/api/researches/{researchId}/run', [\App\Http\Controllers\ResearchQueueController::class, 'run']);
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
