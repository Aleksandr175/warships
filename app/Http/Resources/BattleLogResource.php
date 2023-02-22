<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BattleLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'battleLogId'    => $this->battle_log_id,
            'attackerUserId' => $this->attacker_user_id,
            'defenderUserId' => $this->defender_user_id,
            'date'           => $this->created_at,
            'round'          => $this->round,
        ];
    }
}
