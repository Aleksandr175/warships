<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $input_resource_id
 * @property integer $input_qty
 * @property integer $output_resource_id
 * @property integer $output_qty
 * @property integer $refining_level_required
 * @property integer $time
 *
 * @mixin Builder
 */
class RefiningRecipe extends Model
{
    use HasFactory;
}
