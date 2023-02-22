<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BattleLogDetailResource extends JsonResource
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
            'battleLogId' => $this->battle_log_id,
            'round'       => $this->round,
            'warshipId'   => $this->warship_id,
            'qty'         => $this->qty,
            'destroyed'   => $this->destroyed,
            'userId'      => $this->user_id
        ];
    }
}
