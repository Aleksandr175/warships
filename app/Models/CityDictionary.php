<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityDictionary extends Model
{
    use HasFactory;

    protected $table = 'city_dictionary';

    protected $guarded = [];
}
