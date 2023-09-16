<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingDictionary extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'building_dictionary';
}
