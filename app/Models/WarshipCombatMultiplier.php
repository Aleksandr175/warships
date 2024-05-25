<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarshipCombatMultiplier extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getMultipliers()
    {
        $multipliers = [];
        $data        = self::all();  // Assuming you don't have a huge number of combinations, this is feasible. Otherwise, consider optimizing.

        foreach ($data as $entry) {
            $multipliers[$entry->warship_attacker_id][$entry->warship_defender_id] = $entry->multiplier;
        }

        return $multipliers;
    }
}
