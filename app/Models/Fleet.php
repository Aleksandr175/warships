<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $city_id
 * @property integer $target_city_id
 * @property integer $fleet_task_id
 * @property integer $status_id
 * @property integer $speed
 * @property integer $gold
 * @property integer $repeating
 * @property integer $time
 * @property integer $deadline
 */
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

    public const FLEET_STATUS_ATTACK_GOING_TO_TARGET_ID = 1;
    public const FLEET_STATUS_ATTACK_GOING_BACK_ID      = 3;
    public const FLEET_STATUS_ATTACK_IN_PROGRESS        = 4;

    protected $guarded = [];

    public function isTradeTask()
    {
        return $this->fleet_task_id === self::FLEET_TASK_TRADE_ID;
    }

    public function isMovingTask()
    {
        return $this->fleet_task_id === self::FLEET_TASK_MOVE_ID;
    }

    public function isTrasnsportTask()
    {
        return $this->fleet_task_id === self::FLEET_TASK_TRANSPORT_ID;
    }

    public function isAttackTask()
    {
        return $this->fleet_task_id === self::FLEET_TASK_ATTACK_ID;
    }

    public function isTradeGoingToTarget()
    {
        return $this->isTradeTask() && $this->status_id === self::FLEET_STATUS_TRADE_GOING_TO_TARGET_ID;
    }

    public function isTrading()
    {
        return $this->isTradeTask() && $this->status_id === self::FLEET_STATUS_TRADING_ID;
    }

    public function isTradeGoingBack()
    {
        return $this->isTradeTask() && $this->status_id === self::FLEET_STATUS_TRADE_GOING_BACK_ID;
    }

    public function isMovingFleetGoingToTarget()
    {
        return $this->isMovingTask() && $this->status_id === self::FLEET_STATUS_MOVING_GOING_TO_TARGET_ID;
    }

    public function isMovingFleetGoingBack()
    {
        return $this->isMovingTask() && $this->status_id === self::FLEET_STATUS_MOVING_GOING_BACK_ID;
    }

    public function isTransportFleetGoingToTarget()
    {
        return $this->isMovingTask() && $this->status_id === self::FLEET_STATUS_TRANSPORT_GOING_TO_TARGET_ID;
    }

    public function isTransportFleetGoingBack()
    {
        return $this->isTrasnsportTask() && $this->status_id === self::FLEET_STATUS_TRANSPORT_GOING_BACK_ID;
    }

    public function isAttackFleetGoingToTarget()
    {
        return $this->isAttackTask() && $this->status_id === self::FLEET_STATUS_ATTACK_GOING_TO_TARGET_ID;
    }

    public function isAttackFleetGoingBack()
    {
        return $this->isAttackTask() && $this->status_id === self::FLEET_STATUS_ATTACK_GOING_BACK_ID;
    }

    public function isAttackFleetAttackInProgress()
    {
        return $this->isAttackTask() && $this->status_id === self::FLEET_STATUS_ATTACK_IN_PROGRESS;
    }
}
