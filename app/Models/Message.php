<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property integer $id
 * @property integer $template_id
 * @property string  $content
 * @property string  $createdAt
 *
 * @mixin Builder
 */
class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function resources()
    {
        return $this->hasMany(MessageFleetResource::class);
    }

    public function fleetDetails()
    {
        return $this->hasMany(MessageFleetDetail::class);
    }
}
