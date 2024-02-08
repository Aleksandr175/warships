<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'type'   => 'users',
            'name'   => $this->name,
            'email'  => $this->email,
            'userId' => $this->id,
            'cities' => CityResource::collection($this->cities->load('resources', 'resourcesProductionCoefficient')),
        ];
    }
}
