<?php

namespace App\Jobs;

use App\Models\Fleet;
use App\Services\BattleService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BattleJob implements ShouldQueue
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
    public function handle(BattleService $battleService)
    {
        // TODO add limit?
        $fleetQueue = Fleet::where('deadline', '<', Carbon::now())->get();

        foreach ($fleetQueue as $fleet) {
            $battleService->handle($fleet);
        }
        //
    }
}
