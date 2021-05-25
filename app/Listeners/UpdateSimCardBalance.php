<?php

namespace App\Listeners;

use App\Models\Carrier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateSimCardBalance
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $simCard = $event->simCard;
        $carrier = Carrier::query()->where('id', $simCard->carrier_id)->firstOrFail();
        $smsCost = $carrier ? $carrier->sms_cost : 50;
        $newBalance = $simCard->balance - $smsCost;
        if($newBalance < 0) {
            $newBalance = 0;
        }
        $simCard->update(['balance' => $newBalance]);
    }
}
