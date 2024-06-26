<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @mixin Builder
 */
class Archipelago extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cities() {
        return $this->hasMany(City::class);
    }

    public function userCities()
    {
        return $this->hasMany(City::class)->whereNotNull('user_id')->where('user_id', '<>', config('constants.DEFAULT_PIRATE_ID'));
    }
}
