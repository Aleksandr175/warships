<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarshipImprovementRecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'warshipId'          => $this->warship_id,
            'improvementType'    => $this->improvement_type,
            'level'              => $this->level,
            'resourceId'         => $this->resource_id,
            'qty'                => $this->qty,
            'percentImprovement' => $this->percent_improvement,
        ];
    }
}
