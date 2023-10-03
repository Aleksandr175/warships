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
            'buildingId'        => $this->building_id,
            'buildingLvl'       => $this->building_lvl,
            'requiredEntity'    => $this->required_entity,
            'requiredEntityId'  => $this->required_entity_id,
            'requiredEntityLvl' => $this->required_entity_lvl
        ];
    }
}
