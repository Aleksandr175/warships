<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\Warship;
use App\Models\WarshipQueue;
use App\Services\WarshipService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WarshipQueueJob implements ShouldQueue
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
        $queues = WarshipQueue::where('deadline', '<', Carbon::now())->get();

        foreach ($queues as $queue) {

            $warshipInfo = Warship::where('user_id', $queue->user_id)
                ->where('city_id', $queue->city_id)
                ->where('warship_id', $queue->warship_id)
                ->first();

            if ($warshipInfo && $warshipInfo->id) {
                $warshipInfo->increment('qty', $queue->qty);
            } else {
                Warship::create([
                    'user_id'    => $queue->user_id,
                    'warship_id' => $queue->warship_id,
                    'city_id'    => $queue->city_id,
                    'qty'        => $queue->qty
                ]);
            }

            $queue->delete();

            $city = City::find($queue->city_id);

            if ($city && $city->id) {
                $maxWarshipSlots = (new WarshipService())->getMaxWarshipSlots($city);

                // websockets, notification about warships updates
                (new WarshipService())->sendCityWarshipsDataUpdatedEvent($queue->user_id, $queue->city_id, $city->warships, $city->warshipQueue, $maxWarshipSlots);
            }
        }
    }

}
