<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchImprovementResource extends JsonResource
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
            'researchId'         => $this->research_id,
            'improvementType'    => $this->improvement_type,
            'level'              => $this->level,
            'percentImprovement' => $this->percent_improvement,
        ];
    }
}
