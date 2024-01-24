<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarshipResourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'warshipId'  => $this->warship_id,
            'resourceId' => $this->resource_id,
            'qty'        => $this->qty
        ];
    }
}
