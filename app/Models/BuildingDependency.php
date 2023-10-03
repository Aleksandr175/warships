<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BuildingDependency
 *
 * @package App\Models
 * @mixin Builder
 */
class BuildingDependency extends Model
{
    use HasFactory;

    protected $guarded = [];
}
