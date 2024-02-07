<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MapAdventureCityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'userId'           => $this->user_id,
            'cityTypeId'       => $this->city_dictionary_id,
            'cityAppearanceId' => $this->appearance_id,
            'archipelagoId'    => $this->archipelago_id,
            'title'            => $this->title,
            'coordX'           => $this->coord_x,
            'coordY'           => $this->coord_y,
            'raided'           => $this->raided,
            'resources'        => CityResourceV2Resource::collection($this->resources)
        ];
    }
}
