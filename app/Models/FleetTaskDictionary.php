<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetTaskDictionary extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'fleet_task_dictionary';
}
