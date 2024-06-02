<?php

namespace App\Jobs;

use App\Models\ResearchQueue;
use App\Services\ResearchQueueService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResearchJob implements ShouldQueue
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
    public function handle(): void
    {
        // TODO add limit?
        $researchQueue = ResearchQueue::where('deadline', '<', Carbon::now())->get();
        $researchService   = new ResearchQueueService();

        foreach ($researchQueue as $researchQueueItem) {
            $researchService->handle($researchQueueItem);
        }
    }
}
