<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResearchQueueResource extends JsonResource
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
            'researchId' => $this->research_id,
            'cityId'     => $this->city_id,
            'lvl'        => $this->lvl,
            'time'       => $this->time,
            'deadline'   => $this->deadline ? $this->deadline->format('Y-m-d H:i:s') : null,
        ];
    }
}
