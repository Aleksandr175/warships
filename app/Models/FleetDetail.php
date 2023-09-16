<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FleetDetail
 *
 * @property integer $id
 * @property integer $fleet_id
 * @property integer $warship_id
 * @property integer $qty
 *
 * @package App\Models
 * @mixin Builder
 */
class FleetDetail extends Model
{
    use HasFactory;

    protected $table = 'fleet_details';

    protected $guarded = [];

    public static function getFleetDetails($fleetIds)
    {
        return self::whereIn('fleet_id', $fleetIds)->get();
    }
}
