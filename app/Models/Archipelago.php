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
}
