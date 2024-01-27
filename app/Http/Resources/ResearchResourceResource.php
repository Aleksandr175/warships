<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResearchResourceResource extends JsonResource
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
            'researchId'   => $this->research_id,
            'resourceId'   => $this->resource_id,
            'qty'          => $this->qty,
            'lvl'          => $this->lvl,
            'timeRequired' => $this->time_required
        ];
    }
}
