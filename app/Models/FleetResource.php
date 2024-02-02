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
 * @property integer $resource_id
 * @property integer $qty
 *
 * @package App\Models
 * @mixin Builder
 */
class FleetResource extends Model
{
    use HasFactory;

    protected $guarded = [];
}
