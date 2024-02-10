<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @mixin Builder
 */
class Building extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function buildingResources()
    {
        return $this->hasMany(BuildingResource::class);
    }

    public function buildingProduction(int $lvl)
    {
        return $this->hasMany(BuildingProduction::class)->where('lvl', $lvl);
    }
}
