<?php

namespace App\Services;

use App\Events\CityResourcesDataChangesEvent;
use App\Models\BuildingProduction;
use App\Models\City;
use App\Models\CityResource;
use Carbon\Carbon;

class ResourceService
{
    public function handle(City $city): void
    {
        $now = Carbon::now();
        // building ids which produce some resources
        $productionBuildingIds = BuildingProduction::distinct()->pluck('building_id');

        $cityResourceProductionCoefficients = $city->resourcesProductionCoefficient->toArray();

        $buildingsInCity = $city->buildings()->whereIn('building_id', $productionBuildingIds)->get();

        // [resource_id => produced_value]
        $resourcesGap = [];

        foreach ($buildingsInCity as $building) {
            $productionResources = $building->getBuildingProductionByLevel($building->lvl);

            foreach ($productionResources as $productionResource) {
                $cityResource = CityResource::where('city_id', $city->id)->where('resource_id', $productionResource->resource_id)->first();

                // if it is a new resource for city - we need to create new row
                if (!$cityResource) {
                    $cityResource = CityResource::create([
                        'city_id'     => $city->id,
                        'resource_id' => $productionResource->resource_id,
                        'qty'         => 0
                    ]);
                }

                $resourceLastUpdated = $cityResource->resource_last_updated ?? $now;

                $timeDiff = $now->diffInSeconds($resourceLastUpdated);

                $productionCoefficient = 1;

                for ($i = 0, $iMax = count($cityResourceProductionCoefficients); $i < $iMax; $i++) {
                    if ($cityResourceProductionCoefficients[$i]['resource_id'] === $productionResource->resource_id) {
                        $productionCoefficient = $cityResourceProductionCoefficients[$i]['coefficient'];
                    }
                }

                $producedQty = $timeDiff * $productionResource->qty * $productionCoefficient / 3600;

                if (!isset($resourcesGap[$productionResource->resource_id])) {
                    $resourcesGap[$productionResource->resource_id] = $producedQty;
                } else {
                    // if we already produced same resource with another building - we increment value
                    $resourcesGap[$productionResource->resource_id] += $producedQty;
                }
            }
        }

        $resourcesChanges = [];
        foreach ($resourcesGap as $resourceId => $resourceQty) {
            /* try not to change resource if gap is less than 1 */
            if ($resourceQty >= 1) {
                $cityResource = CityResource::where('city_id', $city->id)->where('resource_id', $resourceId)->first();
                $cityResource->update([
                    'qty'                   => $cityResource->qty + $resourceQty,
                    'resource_last_updated' => $now
                ]);

                $resourcesChanges[] = [
                    'resource_id' => $resourceId,
                    'qty' => $resourceQty
                ];
            }
        }

        // notify user about resources changes
        CityResourcesDataChangesEvent::dispatch($city->user_id, $city->id, $resourcesChanges);
    }

    // type = add, remove
    public function getResourceChanges($resourceChanges, $type): array
    {
        $changes = [];

        foreach ($resourceChanges as $resourceChange) {
            $qty = $type === 'add' ? $resourceChange['qty'] : -$resourceChange['qty'];

            $changes[] = [
                'resource_id' => $resourceChange['resource_id'],
                'qty'         => $qty,
            ];
        }

        return $changes;
    }
}
