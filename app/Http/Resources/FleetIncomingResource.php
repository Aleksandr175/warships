<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FleetIncomingResource extends JsonResource
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
            'id'            => $this->id,
            'cityId'        => $this->city_id,
            'targetCityId'  => $this->target_city_id,
            'fleetTaskId'   => $this->fleet_task_id,
            'fleetStatusId' => $this->status_id,
            'deadline'      => $this->deadline,
        ];
    }
}
