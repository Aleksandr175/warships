<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetDetail extends Model
{
    use HasFactory;

    protected $table = 'fleet_details';

    protected $guarded = [];

    static function getFleetDetails($fleetIds) {
        return FleetDetail::whereIn('fleet_id', $fleetIds)->get();
    }
}
