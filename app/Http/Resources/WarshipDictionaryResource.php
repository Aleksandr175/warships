<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarshipDictionaryResource extends JsonResource
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
            'id'                => $this->id,
            'title'             => $this->title,
            'description'       => $this->description,
            'attack'            => $this->attack,
            'speed'             => $this->speed,
            'capacity'          => $this->capacity,
            'health'            => $this->health,
            'time'              => $this->time,
            'requiredResources' => WarshipResourceResource::collection($this->requiredResources),
            'multipliers'       => WarshipMultiplierResource::collection($this->multipliers)
        ];
    }
}
