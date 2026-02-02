<?php

namespace App\Listeners;

use App\Events\ChooseDrawalEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DrawalChoose 
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
     * @param  \App\Events\ChooseDrawalEvent  $event
     * @return void
     */
    public function handle(ChooseDrawalEvent $event)
    {
        EventLog::create([
            'event_name' => $event->drawal->provider->name,
            'event_data' =>  $event->drawal ,
            'type_user_event' => $event->drawal->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->drawal->wallet->country->name ,
            'amount_send' => $event->drawal->amount ,
            'currencie' => $event->drawal->wallet->currencie->name, 
            'event_user' =>  $event->drawal->wallet->sidUser->firstname . ' ' . $event->drawal->wallet->sidUser->lastname,
        ]);
    }
}
