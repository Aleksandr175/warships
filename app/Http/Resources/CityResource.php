<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'id'                             => $this->id,
            'title'                          => $this->title,
            'coordX'                         => $this->coord_x,
            'coordY'                         => $this->coord_y,
            'gold'                           => $this->gold,
            'population'                     => $this->population,
            'resources'                      => CityResourceV2Resource::collection($this->resources),
            'resourcesProductionCoefficient' => CityResourceProductionCoefficient::collection($this->resourcesProductionCoefficient),
        ];
    }
}
