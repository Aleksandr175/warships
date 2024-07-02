<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string  $improvement_type
 * @property integer $base_increment
 * @property string  $description
 * @property string  $title
 *
 * @mixin Builder
 */
class ResearchDictionary extends Model
{
    use HasFactory;

    protected $table = 'research_dictionary';
}
