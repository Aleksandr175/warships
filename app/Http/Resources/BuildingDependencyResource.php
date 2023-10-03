<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingDependencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'buildingId'          => $this->building_id,
            'buildingLvl'         => $this->building_lvl,
            'required_entity'     => $this->required_entity,
            'required_entity_id'  => $this->required_entity_id,
            'required_entity_lvl' => $this->required_entity_lvl
        ];
    }
}
