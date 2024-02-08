<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResourceProductionCoefficient extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cityId'      => $this->city_id,
            'resourceId'  => $this->resource_id,
            'coefficient' => $this->coefficient
        ];
    }
}
