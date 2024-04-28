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
class ResearchQueue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function resources() {
        return $this->hasMany(ResearchQueueResource::class);
    }
}
