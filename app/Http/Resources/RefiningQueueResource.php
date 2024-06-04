<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefiningQueueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cityId'           => $this->city_id,
            'inputResourceId'  => $this->input_resource_id,
            'inputQty'         => $this->input_qty,
            'outputResourceId' => $this->output_resource_id,
            'outputQty'        => $this->output_qty,
            'time'             => $this->time,
            'deadline'         => $this->deadline ? $this->deadline->format('Y-m-d H:i:s') : null,
        ];
    }
}
