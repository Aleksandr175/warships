<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarshipQueueResource extends JsonResource
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
            'warshipId' => $this->warship_id,
            'cityId'    => $this->city_id,
            'qty'       => $this->qty,
            'time'      => $this->time,
            'deadline'  => $this->deadline
        ];
    }
}
