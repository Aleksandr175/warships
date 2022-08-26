<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FleetResource extends JsonResource
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
            'id'            => $this->id,
            'cityId'        => $this->city_id,
            'targetCityId'  => $this->target_city_id,
            'fleetTaskId'   => $this->fleet_task_id,
            'fleetStatusId' => $this->status_id,
            'speed'         => $this->speed,
            'gold'          => $this->gold,
            'recursive'     => $this->recursive,
            'time'          => $this->time,
            'deadline'      => $this->deadline,
        ];
    }
}
