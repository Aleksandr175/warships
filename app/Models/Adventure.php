<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Adventure
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $adventure_level
 * @property integer $archipelago_id
 * @property integer $status
 *
 * @package App\Models
 * @mixin Builder
 */
class Adventure extends Model
{
    use HasFactory;

    protected $guarded = [];
}
