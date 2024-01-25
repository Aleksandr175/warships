<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResourceResource extends JsonResource
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
            'buildingId'   => $this->building_id,
            'resourceId'   => $this->resource_id,
            'qty'          => $this->qty,
            'lvl'          => $this->lvl,
            'timeRequired' => $this->time_required
        ];
    }
}
