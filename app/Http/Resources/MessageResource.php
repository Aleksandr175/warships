<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => $this->id,
            "userId"        => $this->user_id,
            "content"       => $this->content,
            "templateId"    => $this->template_id,
            "isRead"        => $this->is_read,
            "eventType"     => $this->event_type,
            "archipelagoId" => $this->archipelago_id,
            "coordX"        => $this->coord_x,
            "coordY"        => $this->coord_y,
            "battleLogId"   => $this->battle_log_id,
            "createaAt"     => $this->created_at
        ];
    }
}
