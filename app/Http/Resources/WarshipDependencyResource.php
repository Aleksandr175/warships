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
            'warshipId'           => $this->warship_id,
            'required_entity'     => $this->required_entity,
            'required_entity_id'  => $this->required_entity_id,
            'required_entity_lvl' => $this->required_entity_lvl
        ];
    }
}
