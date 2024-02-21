<?php

namespace App\Listeners;

use App\Events\ChooseInterEvent;
use App\Models\EventLog;

class InterChoose 
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
     * @param  \App\Events\chooseInterEvent  $event
     * @return void
     */
    public function handle(ChooseInterEvent $event)
    {
        EventLog::create([
            'event_name' => $event->inter->provider->name,
            'event_data' =>  $event->inter ,
            'type_user_event' => $event->inter->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->inter->wallet->country->name ,
            'amount_send' => $event->inter->amount ,
            'currencie' => $event->inter->wallet->currencie->name, 
            'event_user' =>  $event->inter->wallet->sidUser->firstname . ' ' . $event->inter->wallet->sidUser->lastname,
        ]);
    }
}
