<?php

namespace App\Jobs;

use App\Models\RefiningQueue;
use App\Services\RefiningQueueService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefiningJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    public function handle()
    {
        // TODO add limit?
        $refiningQueue = RefiningQueue::where('deadline', '<', Carbon::now())->get();
        $refiningService   = new RefiningQueueService();

        foreach ($refiningQueue as $refiningQueueItem) {
            $refiningService->handle($refiningQueueItem);
        }
    }
}
