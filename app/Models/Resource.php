<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string  $title
 * @property string  $description
 * @property string  $slug
 * @property integer $value
 *
 * @mixin Builder
 */
class Resource extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'resources';
}
