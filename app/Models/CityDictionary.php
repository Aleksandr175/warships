<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityDictionary extends Model
{
    use HasFactory;

    protected $table = 'city_dictionary';

    public const PLAYERS_ISLAND = 1;
    public const PIRATE_BAY     = 2;
}
