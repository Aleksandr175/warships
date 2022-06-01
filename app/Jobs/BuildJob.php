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

class BuildJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cityId = $this->data['cityId'];
        $buildingId = $this->data['buildingId'];

        $city = City::find($cityId);

        $buildingQueue = $city->buildingQueue()->first();

        if ($buildingQueue && $buildingQueue->id) {
            if ($buildingQueue->deadline <= Carbon::now()) {
                // add lvl
                if ($city->building($buildingQueue->building_id)) {
                    $city->building($buildingQueue->building_id)->increment('lvl');
                } else {
                    // create new building
                    $city->buildings()->create([
                        'building_id' => $buildingId,
                        'city_id' => $cityId,
                        'lvl' => 1,
                    ]);
                }

                // TODO change 3 to HOUSE (dictionary)
                if ($buildingQueue->building_id === 3) {
                    $additionalPopulation = BuildingProduction::where('lvl', $buildingQueue->lvl)->where('resource', 'population')->first();

                    $city->increment('population', $additionalPopulation->qty);
                }

                $city->buildingQueue()->delete();
            }
        }
    }
}
