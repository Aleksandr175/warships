<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function isTradeFleet()
    {
        return $this->fleet_task_id === 1;
    }

    public function isTradeGoingToTarget()
    {
        return $this->isTradeFleet() && $this->status_id === 1;
    }

    public function isTrading()
    {
        return $this->isTradeFleet() && $this->status_id === 2;
    }

    public function isTradeGoingBack()
    {
        return $this->isTradeFleet() && $this->status_id === 3;
    }
}
