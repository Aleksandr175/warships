<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MapCityResource extends JsonResource
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
            'id'               => $this->id,
            'userId'           => $this->user_id,
            'cityTypeId'       => $this->city_dictionary_id,
            'cityAppearanceId' => $this->appearance_id,
            'archipelagoId'    => $this->archipelago_id,
            'title'            => $this->title,
            'coordX'           => $this->coord_x,
            'coordY'           => $this->coord_y,
        ];
    }
}
