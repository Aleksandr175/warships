<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CityResource
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $resource_id
 * @property string  $qty
 *
 * @package App\Models
 * @mixin Builder
 */
class CityResource extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
