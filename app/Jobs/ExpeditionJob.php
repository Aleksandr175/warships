<?php

namespace App\Jobs;

use App\Models\Fleet;
use App\Services\ExpeditionService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpeditionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ExpeditionService $expeditionService): void
    {
        $fleetQueue = Fleet::where('deadline', '<', Carbon::now())
            ->where('fleet_task_id', config('constants.FLEET_TASKS.EXPEDITION'))
            ->where('status_id', config('constants.FLEET_STATUSES.EXPEDITION_IN_PROGRESS'))
            ->get();

        foreach ($fleetQueue as $fleet) {
            $expeditionService->handle($fleet);
        }
    }
}
