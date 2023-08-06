<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'buildingId' => $this->building_id,
            'gold' => $this->gold,
            'population' => $this->population,
            'lvl' => $this->lvl,
            'time' => $this->time
        ];
    }
}
