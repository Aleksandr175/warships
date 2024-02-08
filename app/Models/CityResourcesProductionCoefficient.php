<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityResourcesProductionCoefficient extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'resource_id',
        'coefficient',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
