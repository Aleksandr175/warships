<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarshipMultiplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'warshipAttackerId' => $this->warship_attacker_id,
            'warshipDefenderId' => $this->warship_defender_id,
            'multiplier'        => $this->multiplier,
        ];
    }
}
