<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarshipDependencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'warshipId'         => $this->warship_id,
            'requiredEntity'    => $this->required_entity,
            'requiredEntityId'  => $this->required_entity_id,
            'requiredEntityLvl' => $this->required_entity_lvl
        ];
    }
}
