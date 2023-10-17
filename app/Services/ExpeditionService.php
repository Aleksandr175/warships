<?php

namespace App\Services;

use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\WarshipDictionary;

class ExpeditionService
{
    public function handle(Fleet $fleet): void
    {
        dump('Handle expedition result...');
        // Generate a random number between 1 and 100
        $randomNumber = random_int(1, 100);

        $fleetDetails = FleetDetail::getFleetDetails([$fleet->id]);

        if ($randomNumber <= 75) {
            // 75% chance: Gain resources
            $this->gainResources($fleet, $fleetDetails);
        } elseif ($randomNumber <= 76) {
            // 1% chance: Lose the entire fleet
            $this->loseEntireFleet($fleet, $fleetDetails);
        } elseif ($randomNumber <= 80) {
            // 4% chance: A storm damages 20% of the fleet
            $this->handleStormDamage($fleet, $fleetDetails);
        } else {
            // 20% chance: Nothing happens
            // Continue with the expedition without any changes
            // TODO: add message about nothing
        }

        // TODO: change fleet status if fleet exist
    }

    public function gainResources(Fleet $fleet, $fleetDetails): void {
        $warshipsDictionary = WarshipDictionary::get();

        $fleetDetails = (new BattleService)->populateFleetDetailsWithCapacityAndHealth($fleetDetails, $warshipsDictionary);

        $availableCapacity = (new BattleService)->getAvailableCapacity($fleet, $fleetDetails);

        if ($availableCapacity > 0) {
            $goldAmount = random_int(1, $availableCapacity);

            $fleet->increment('gold', $goldAmount);

            dump('gain resources', $goldAmount);
        }
    }

    public function loseEntireFleet(Fleet $fleet, $fleetDetails): void {
        $fleet->delete();

        foreach ($fleetDetails as $fleetDetail) {
            $fleetDetail->delete();
        }

        dump('delete fleet');
    }

    public function handleStormDamage(Fleet $fleet, $fleetDetails): void {
        foreach ($fleetDetails as $fleetDetail) {
            $fleetDetail->update(['qty' => floor($fleetDetail['qty'] * 0.8)]);

            if ($fleetDetail['qty'] < 1) {
                $fleetDetail->delete();
            }
        }

        if (!count($fleetDetails)) {
            // TODO: add message that fleet has been destroyed completely
            $fleet->delete();
        }

        dump('handle storm damage');
    }
}
