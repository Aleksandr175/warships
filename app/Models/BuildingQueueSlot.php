<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 *
 * @mixin Builder
 */
class BuildingQueueSlot extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function slots(int $buildingId, int $buildingLvl)
    {
        return BuildingQueueSlot::where('building_id', $buildingId)->where('building_lvl', '<=', $buildingLvl)->orderBy('building_lvl', 'desc')->first();
    }
}
