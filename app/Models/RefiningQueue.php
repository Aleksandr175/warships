<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $city_id
 * @property integer $input_resource_id
 * @property integer $input_qty
 * @property integer $output_resource_id
 * @property integer $output_qty
 * @property integer $time_required
 * @property string  $deadline
 *
 * @mixin Builder
 */
class RefiningQueue extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'deadline' => 'datetime',
    ];
}
