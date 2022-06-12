<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 *
 * @property integer $user_id
 * @property string  $title
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
}
