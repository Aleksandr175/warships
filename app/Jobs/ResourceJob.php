<?php

namespace App\Jobs;

use App\Models\BuildingProduction;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResourceJob implements ShouldQueue
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
        $cities = City::all();
        foreach ($cities as $city) {
            $miner = $city->buildings()->where('id', 2)->first();
            $gold = $city->gold;
            $production = 0;
            $now = Carbon::now();

            if ($miner && $miner->lvl) {
                $minerLvl = $miner->lvl;

                $buildingProduction = BuildingProduction::where('building_id', 2)->where('lvl', $minerLvl)->first();

                $resourceLastUpdated = Carbon::parse($city->resource_last_updated);

                $timeDiff = $now->diffInSeconds($resourceLastUpdated);

                $production = $timeDiff * $buildingProduction->qty / 3600;
            }

            $city->update([
                'gold' => $gold + $production,
                'resource_last_updated' => $now
            ]);
        }
    }
}
