<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @property integer $population
 * @property integer $repeating
 * @property integer $time
 * @property integer $deadline
 *
 * @mixin Builder
 */
class Fleet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function targetCity()
    {
        return $this->belongsTo(City::class, 'target_city_id');
    }

    public function isTradeTask()
    {
        return $this->fleet_task_id === config('constants.FLEET_TASKS.TRADE');
    }

    public function isMovingTask()
    {
        return $this->fleet_task_id === config('constants.FLEET_TASKS.MOVE');
    }

    public function isTrasnsportTask()
    {
        return $this->fleet_task_id === config('constants.FLEET_TASKS.TRANSPORT');
    }

    public function isAttackTask()
    {
        return $this->fleet_task_id === config('constants.FLEET_TASKS.ATTACK');
    }

    public function isExpeditionTask()
    {
        return $this->fleet_task_id === config('constants.FLEET_TASKS.EXPEDITION');
    }

    public function isTradeGoingToTarget()
    {
        return $this->isTradeTask() && $this->status_id === config('constants.FLEET_STATUSES.TRADE_GOING_TO_TARGET');
    }

    public function isTrading()
    {
        return $this->isTradeTask() && $this->status_id === config('constants.FLEET_STATUSES.TRADING');
    }

    public function isTradeGoingBack()
    {
        return $this->isTradeTask() && $this->status_id === config('constants.FLEET_STATUSES.TRADE_GOING_BACK');
    }

    public function isMovingFleetGoingToTarget()
    {
        return $this->isMovingTask() && $this->status_id === config('constants.FLEET_STATUSES.MOVING_GOING_TO_TARGET');
    }

    public function isMovingFleetGoingBack()
    {
        return $this->isMovingTask() && $this->status_id === config('constants.FLEET_STATUSES.MOVING_GOING_BACK');
    }

    public function isTransportFleetGoingToTarget()
    {
        return $this->isTrasnsportTask() && $this->status_id === config('constants.FLEET_STATUSES.TRANSPORT_GOING_TO_TARGET');
    }

    public function isTransportFleetGoingBack()
    {
        return $this->isTrasnsportTask() && $this->status_id === config('constants.FLEET_STATUSES.TRANSPORT_GOING_BACK');
    }

    public function isAttackFleetGoingToTarget()
    {
        return $this->isAttackTask() && $this->status_id === config('constants.FLEET_STATUSES.ATTACK_GOING_TO_TARGET');
    }

    public function isAttackFleetGoingBack()
    {
        return $this->isAttackTask() && $this->status_id === config('constants.FLEET_STATUSES.ATTACK_GOING_BACK');
    }

    public function isAttackFleetAttackInProgress()
    {
        return $this->isAttackTask() && $this->status_id === config('constants.FLEET_STATUSES.ATTACK_IN_PROGRESS');
    }

    public function isAttackFleetAttackCompleted()
    {
        return $this->isAttackTask() && $this->status_id === config('constants.FLEET_STATUSES.ATTACK_COMPLETED');
    }

    public function isExpeditionFleetGoingToTarget()
    {
        return $this->isExpeditionTask() && $this->status_id === config('constants.FLEET_STATUSES.EXPEDITION_GOING_TO_TARGET');
    }

    public function isExpeditionInProgress()
    {
        return $this->isExpeditionTask() && $this->status_id === config('constants.FLEET_STATUSES.EXPEDITION_IN_PROGRESS');
    }

    public function isExpeditionDone()
    {
        return $this->isExpeditionTask() && $this->status_id === config('constants.FLEET_STATUSES.EXPEDITION_DONE');
    }

    public function isExpeditionGoingBack()
    {
        return $this->isExpeditionTask() && $this->status_id === config('constants.FLEET_STATUSES.EXPEDITION_GOING_BACK');
    }

    public function resources() {
        return $this->hasMany(FleetResource::class);
    }

    public function resource($resourceId) {
        return $this->hasOne(FleetResource::class)->where('resource_id', $resourceId)->first();
    }
}
