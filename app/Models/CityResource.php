<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityResource extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'resource_id', 'qty'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
