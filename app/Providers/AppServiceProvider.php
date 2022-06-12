<?php

namespace App\Providers;

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
        Queue::after(function (JobProcessed $event) {
            if ($event->job->getQueue() === 'resource') {
                sleep(1);
                ResourceJob::dispatch()->onQueue('resource');;
            }

            if ($event->job->getQueue() === 'warshipQueue') {
                sleep(1);
                WarshipQueueJob::dispatch()->onQueue('warshipQueue');;
            }
        });
    }
}
