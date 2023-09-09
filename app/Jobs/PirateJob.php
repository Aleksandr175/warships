<?php

namespace App\Jobs;

use App\Models\City;
use App\Services\PirateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// This controller decides what pirates should do
// It
class PirateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO: change 2 to constant, 2 means pirate island
        $cities = City::get()->where('city_dictionary_id', 2);
        $pirateService = new PirateService();

        foreach ($cities as $city) {
            $pirateService->handle($city);
        }
    }
}
