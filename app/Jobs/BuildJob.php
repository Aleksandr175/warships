<?php

namespace App\Jobs;

use App\Models\CityBuildingQueue;
use App\Services\BuildingQueueService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildJob implements ShouldQueue
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
        // TODO add limit?
        $cityBuildingQueue = CityBuildingQueue::where('deadline', '<', Carbon::now())->get();
        $buildingService   = new BuildingQueueService();

        foreach ($cityBuildingQueue as $buildingQueue) {
            $buildingService->handle($buildingQueue);
        }
    }
}
