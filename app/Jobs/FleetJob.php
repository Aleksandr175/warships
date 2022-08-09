<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\WarshipQueue;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FleetJob implements ShouldQueue
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
        dump('checking fleets...');
        $fleetQueue = Fleet::where('deadline', '<', Carbon::now())->get();

        foreach ($fleetQueue as $fleet) {
            // task: trade
            if ($fleet->isTradeFleet()) {
                if ($fleet->isTradeGoingToTarget()) {
                    dump('fleet trade');
                    $fleet->update([
                        'status_id' => 2,
                        // how long?
                        'deadline'  => Carbon::create($fleet->deadline)->addSecond(10)
                    ]);
                }

                if ($fleet->isTrading()) {
                    dump('fleet trading...');
                    // add gold to fleet? Formula?
                    $fleet->update([
                        'status_id' => 3,
                        // TODO: add formula
                        'gold'      => 100,
                        // TODO: add formula? constant?
                        'deadline'  => Carbon::create($fleet->deadline)->addSecond(5)
                    ]);
                }

                if ($fleet->isTradeGoingBack()) {
                    dump('fleet returns');

                    $city         = City::find($fleet->city_id);
                    $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

                    foreach ($fleetDetails as $fleetDetail) {
                        $city->warship($fleetDetail->warship_id)->increment('qty', $fleetDetail->qty);
                        $fleetDetail->delete();
                    }

                    $city->increment('gold', $fleet->gold);

                    $fleet->delete();

                    // TODO recursive flag
                }
            }

            // task: move fleet to other island

            // task: attack?

            // task: transport

            // TODO: move logic to service?
        }

    }
}
