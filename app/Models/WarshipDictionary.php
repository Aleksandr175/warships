<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WarshipDictionary
 *
 * @property integer $id
 * @property string  $title
 * @property string  $description
 * @property integer $attack
 * @property integer $speed
 * @property integer $capacity
 * @property integer $gold
 * @property integer $population
 * @property integer $health
 * @property integer $time
 *
 * @package App\Models
 */
class WarshipDictionary extends Model
{
    use HasFactory;

    protected $table = 'warship_dictionary';
}
