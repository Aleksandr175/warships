<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class City
 *
 * @property integer $user_id
 * @property string $title
 * @property integer $coord_x
 * @property integer $coord_y
 * @property integer $gold
 * @property integer $population
 *
 * @package App\Models
 */
class City extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function buildings() {
        return $this->hasMany(Building::class);
    }

    public function building($buildingId) {
        return $this->buildings()->where('building_id', $buildingId)->first();
    }

    public function buildingQueue() {
        return $this->hasOne(CityBuildingQueue::class);
    }

    public function canBuild($buildingId) {
        $nextLvl = 1;
        $cityBuilding = $this->building($buildingId);

        if ($cityBuilding && $cityBuilding->id) {
            $nextLvl = $cityBuilding->lvl + 1;
        }

        // found out what resources we need for building
        $buildingResources = BuildingResource::where('building_id', $buildingId)->where('lvl', $nextLvl)->first();

        if ($buildingResources && $buildingResources->id) {
            if ($this->gold >= $buildingResources->gold && $this->population >= $buildingResources->population) {
                return true;
            }
        }

        return false;
    }

    public function canResearch($id) {
        $nextLvl = 1;

        $user = Auth::user();
        $research = $user->research($id)->first();

        if ($research && $research->id) {
            $nextLvl = $research->lvl + 1;
        }

        // found out what resources we need for research
        $resources = ResearchResource::where('research_id', $id)->where('lvl', $nextLvl)->first();

        if ($resources && $resources->id) {
            if ($this->gold >= $resources->gold && $this->population >= $resources->population) {
                return true;
            }
        }

        return false;
    }

    public function build($buildingId) {
        $nextLvl = 1;
        $cityBuilding = $this->building($buildingId);

        if ($cityBuilding && $cityBuilding->id) {
            $nextLvl = $cityBuilding->lvl + 1;
        }

        // found out what resources we need for building
        $buildingResources = BuildingResource::where('building_id', $buildingId)->where('lvl', $nextLvl)->first();

        $time = ($buildingResources->gold + $buildingResources->population) / 10;

        // take resources from city
        $this->update([
            'gold' => $this->gold - $buildingResources->gold,
            'population' => $this->population - $buildingResources->population
        ]);

        return CityBuildingQueue::create([
            'building_id' => $buildingId,
            'city_id' => $this->id,

            'gold' => $buildingResources->gold,
            'population' => $buildingResources->population,
            'lvl' => $nextLvl,
            'time' => $time,
            'deadline' => Carbon::now()->addSeconds($time)
        ]);
    }

    public function orderResearch($researchId) {
        $nextLvl = 1;
        $user = Auth::user();
        $research = $user->research($researchId)->first();

        if ($research && $research->id) {
            $nextLvl = $research->lvl + 1;
        }

        // found out what resources we need for research
        $resources = ResearchResource::where('research_id', $researchId)->where('lvl', $nextLvl)->first();

        $time = ($resources->gold + $resources->population) / 10;

        // take resources from city
        $this->update([
            'gold' => $this->gold - $resources->gold,
            'population' => $this->population - $resources->population
        ]);

        return ResearchQueue::create([
            'research_id' => $researchId,
            'city_id' => $this->id,
            'user_id' => $user->id,
            'gold' => $resources->gold,
            'population' => $resources->population,
            'lvl' => $nextLvl,
            'time' => $time,
            'deadline' => Carbon::now()->addSeconds($time)
        ]);
    }
}
