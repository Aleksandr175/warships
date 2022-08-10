<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\WarshipQueue;
use App\Services\FleetService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FleetJob implements ShouldQueue
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
    public function handle(FleetService $fleetService)
    {
        dump('checking fleets...');
        $fleetQueue = Fleet::where('deadline', '<', Carbon::now())->get();

        foreach ($fleetQueue as $fleet) {
            $fleetService->handleFleet($fleet);
        }

    }
}
