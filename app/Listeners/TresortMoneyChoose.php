<?php

namespace App\Listeners;

use App\Events\ChooseTresortEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TresortMoneyChoose 
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
     * @param  \App\Events\ChooseTresortEvent  $event
     * @return void
     */
    public function handle(ChooseTresortEvent $event)
    {
        EventLog::create([
            'event_name' => $event->tresort->provider->name,
            'event_data' =>  $event->tresort ,
            'type_user_event' => $event->tresort->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->tresort->wallet->country->name ,
            'amount_send' => $event->tresort->amount ,
            'currencie' => $event->tresort->wallet->currencie->name, 
            'event_user' =>  $event->tresort->wallet->sidUser->firstname . ' ' . $event->tresort->wallet->sidUser->lastname,
        ]);
    }
}
