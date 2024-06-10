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

    public function buildingProductions()
    {
        return $this->hasMany(BuildingProduction::class, 'building_id', 'building_id');
    }

    public function getBuildingProductionByLevel($lvl)
    {
        return $this->buildingProductions()->where('lvl', $lvl)->get();
    }
}
