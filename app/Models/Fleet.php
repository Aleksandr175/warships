<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    /**
     * @var int
     */
    public const FLEET_TASK_TRADE_ID     = 1;
    public const FLEET_TASK_MOVE_ID      = 2;
    public const FLEET_TASK_ATTACK_ID    = 3;
    public const FLEET_TASK_TRANSPORT_ID = 4;

    public const FLEET_STATUS_TRADE_GOING_TO_TARGET_ID  = 1;
    public const FLEET_STATUS_TRADING_ID                = 2;
    public const FLEET_STATUS_TRADE_GOING_BACK_ID       = 3;
    public const FLEET_STATUS_MOVING_GOING_TO_TARGET_ID = 1;
    public const FLEET_STATUS_MOVING_GOING_BACK_ID      = 3;

    public const FLEET_STATUS_TRANSPORT_GOING_TO_TARGET_ID = 1;
    public const FLEET_STATUS_TRANSPORT_GOING_BACK_ID      = 3;

    protected $guarded = [];

    public function isTradeFleet()
    {
        return $this->fleet_task_id === self::FLEET_TASK_TRADE_ID;
    }

    public function isMovingFleet()
    {
        return $this->fleet_task_id === self::FLEET_TASK_MOVE_ID;
    }

    public function isTrasnsportFleet()
    {
        return $this->fleet_task_id === self::FLEET_TASK_TRANSPORT_ID;
    }

    public function isTradeGoingToTarget()
    {
        return $this->isTradeFleet() && $this->status_id === self::FLEET_STATUS_TRADE_GOING_TO_TARGET_ID;
    }

    public function isTrading()
    {
        return $this->isTradeFleet() && $this->status_id === self::FLEET_STATUS_TRADING_ID;
    }

    public function isTradeGoingBack()
    {
        return $this->isTradeFleet() && $this->status_id === self::FLEET_STATUS_TRADE_GOING_BACK_ID;
    }

    public function isMovingFleetGoingToTarget()
    {
        return $this->isMovingFleet() && $this->status_id === self::FLEET_STATUS_MOVING_GOING_TO_TARGET_ID;
    }

    public function isMovingFleetGoingBack()
    {
        return $this->isMovingFleet() && $this->status_id === self::FLEET_STATUS_MOVING_GOING_BACK_ID;
    }

    public function isTransportFleetGoingToTarget()
    {
        return $this->isTrasnsportFleet() && $this->status_id === self::FLEET_STATUS_TRANSPORT_GOING_TO_TARGET_ID;
    }

    public function isTransportFleetGoingBack()
    {
        return $this->isTrasnsportFleet() && $this->status_id === self::FLEET_STATUS_TRANSPORT_GOING_BACK_ID;
    }

}
