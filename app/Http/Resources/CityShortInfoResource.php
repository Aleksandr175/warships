<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityShortInfoResource extends JsonResource
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
            'id'            => $this->id,
            'title'         => $this->title,
            'userId'        => $this->user_id,
            'archipelagoId' => $this->archipelago_id,
            'coordX'        => $this->coord_x,
            'coordY'        => $this->coord_y,
        ];
    }
}
