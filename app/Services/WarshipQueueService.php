<?php

namespace App\Services;

use App\Http\Requests\Api\WarshipCreateRequest;
use App\Models\City;
use App\Models\WarshipDictionary;
use App\Models\WarshipQueue;
use Carbon\Carbon;

class WarshipQueueService
{
    protected $userId;
    protected $warshipId;
    protected $qty;
    protected $city;

    public function store($userId, WarshipCreateRequest $request)
    {
        $queue     = null;
        $data      = $request->only('cityId', 'warshipId', 'qty');
        $cityId    = $data['cityId'];
        $warshipId = $data['warshipId'];
        $qty       = $data['qty'];

        $this->userId    = $userId;
        $this->warshipId = $warshipId;
        $this->qty       = $qty;

        $this->city = City::where('id', $cityId)->where('user_id', $this->userId)->first();

        if ($this->city && $this->city->id) {
            $queue = $this->updateWarshipQueue();
        } else {
            return abort(403);
        }

        return $queue;
    }

    public function updateWarshipQueue()
    {
        $warshipDict = WarshipDictionary::find($this->warshipId);

        $totalWarshipGold       = $this->qty * $warshipDict->gold;
        $totalWarshipPopulation = $this->qty * $warshipDict->population;
        $time                   = $this->qty * $warshipDict->time;
        $deadline               = null;

        $cityGold       = $this->city->gold;
        $cityPopulation = $this->city->population;

        if ($this->qty > $cityGold / $warshipDict->gold) {
            $this->qty = floor($cityGold / $warshipDict->gold);
        }

        if ($this->qty > $cityPopulation / $warshipDict->population) {
            $this->qty = floor($cityPopulation / $warshipDict->population);
        }

        $queue = WarshipQueue::where('user_id', $this->userId)->where('city_id', $this->city->id)->orderBy('deadline')->get();

        if ($this->qty > 0) {
            if (!count($queue)) {
                // just add time for first queue
                $deadline = Carbon::now()->addSeconds($time);
            } else {
                // calculate deadline for next item in queue
                $deadline = Carbon::create($queue[count($queue) - 1]->deadline)->addSeconds($time);
            }

            $queue->push(WarshipQueue::create([
                'user_id'    => $this->userId,
                'city_id'    => $this->city->id,
                'warship_id' => $this->warshipId,
                'qty'        => $this->qty,
                'time'       => $time,
                'deadline'   => $deadline
            ]));

            // take resources from city
            $this->city->update([
                'gold'       => $cityGold - $totalWarshipGold,
                'population' => $cityPopulation - $totalWarshipPopulation
            ]);
        }

        return $queue;
    }
}
