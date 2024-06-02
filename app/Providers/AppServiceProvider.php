<?php

namespace App\Providers;

use App\Jobs\BattleJob;
use App\Jobs\BuildJob;
use App\Jobs\ExpeditionJob;
use App\Jobs\FleetJob;
use App\Jobs\PirateJob;
use App\Jobs\RefiningJob;
use App\Jobs\ResearchJob;
use App\Jobs\ResourceJob;
use App\Jobs\WarshipQueueJob;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // TODO: add job for researches

        Queue::after(function (JobProcessed $event) {
            if ($event->job->getQueue() === 'resource') {
                ResourceJob::dispatch()->onQueue('resource')->delay(now()->addMinutes(1));
            }

            if ($event->job->getQueue() === 'warshipQueue') {
                WarshipQueueJob::dispatch()->onQueue('warshipQueue')->delay(now()->addSeconds(3));
            }

            if ($event->job->getQueue() === 'buildingQueue') {
                BuildJob::dispatch()->onQueue('buildingQueue')->delay(now()->addSeconds(3));
            }

            if ($event->job->getQueue() === 'fleet') {
                FleetJob::dispatch()->onQueue('fleet')->delay(now()->addSeconds(3));
            }

            if ($event->job->getQueue() === 'pirateLogic') {
                PirateJob::dispatch()->onQueue('pirateLogic')->delay(now()->addMinutes(1));
            }

            if ($event->job->getQueue() === 'battle') {
                BattleJob::dispatch()->onQueue('battle')->delay(now()->addSeconds(3));
            }

            if ($event->job->getQueue() === 'expedition') {
                ExpeditionJob::dispatch()->onQueue('expedition')->delay(now()->addSeconds(3));
            }

            if ($event->job->getQueue() === 'refining') {
                RefiningJob::dispatch()->onQueue('refining')->delay(now()->addSeconds(3));
            }

            if ($event->job->getQueue() === 'researchQueue') {
                ResearchJob::dispatch()->onQueue('researchQueue')->delay(now()->addSeconds(3));
            }
        });
    }
}
