<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 *
 * @property integer $user_id
 * @property integer $city_dictionary_id
 * @property string  $title
 * @property integer $archipelago_id
 * @property integer $coord_x
 * @property integer $coord_y
 * @property integer $gold
 * @property integer $population
 *
 * @package App\Models
 * @mixin Builder
 */
class City extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function building($buildingId)
    {
        return $this->buildings()->where('building_id', $buildingId)->first();
    }

    public function buildingQueue()
    {
        return $this->hasOne(CityBuildingQueue::class);
    }

    public function warshipQueues()
    {
        return $this->hasMany(WarshipQueue::class);
    }

    public function warships()
    {
        return $this->hasMany(Warship::class);
    }

    public function warship($warshipId)
    {
        return $this->hasMany(Warship::class)->where('warship_id', $warshipId)->first();
    }

    public function fleets()
    {
        return $this->hasMany(Fleet::class);
    }

    public function incomingFleets()
    {
        return $this->hasMany(Fleet::class, 'target_city_id');
    }
}
