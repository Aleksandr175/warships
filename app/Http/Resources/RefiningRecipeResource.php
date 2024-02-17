<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefiningRecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'refiningRecipeId'      => $this->id,
            'inputResourceId'       => $this->input_resource_id,
            'inputQty'              => $this->input_qty,
            'outputResourceId'      => $this->output_resource_id,
            'outputQty'             => $this->output_qty,
            'refiningLevelRequired' => $this->refining_level_required,
            'time'                  => $this->time
        ];
    }
}
