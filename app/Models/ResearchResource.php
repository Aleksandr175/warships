<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 *
 * @mixin Builder
 */
class ResearchResource extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
