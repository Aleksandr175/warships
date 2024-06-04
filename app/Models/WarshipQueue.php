<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarshipQueue extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    protected $table = 'warship_queues';
}
