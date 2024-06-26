<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\BattleLogDetailResource;
use App\Http\Resources\BattleLogResource;
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
            "id"               => $this->id,
            "userId"           => $this->user_id,
            "content"          => $this->content,
            "templateId"       => $this->template_id,
            "isRead"           => $this->is_read,
            "eventType"        => $this->event_type,
            "archipelagoId"    => $this->archipelago_id,
            "coordX"           => $this->coord_x,
            "coordY"           => $this->coord_y,
            "battleLogId"      => $this->battle_log_id,
            "createdAt"        => $this->created_at,
            "cityId"           => $this->city_id,
            "targetCityId"     => $this->target_city_id,
            "resources"        => MessageFleetResourceResource::collection($this->resources),
            "fleetDetails"     => MessageFleetDetailResource::collection($this->fleetDetails),
            "battleLog"        => new BattleLogResource($this->battleLog),
            "battleLogDetails" => BattleLogDetailResource::collection($this->battleLogDetails)
        ];
    }
}
