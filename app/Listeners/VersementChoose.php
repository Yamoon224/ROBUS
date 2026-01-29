<?php

namespace App\Listeners;

use App\Events\ChooseVersementEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VersementChoose 
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
     * @param  \App\Events\ChooseVersementEvent  $event
     * @return void
     */
    public function handle(ChooseVersementEvent $event)
    {
        EventLog::create([
            'event_name' => $event->versement->provider->name,
            'event_data' =>  $event->versement ,
            'type_user_event' => $event->versement->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->versement->wallet->country->name ,
            'amount_send' => $event->versement->amount ,
            'currencie' => $event->versement->wallet->currencie->name, 
            'event_user' =>  $event->versement->wallet->sidUser->firstname . ' ' . $event->versement->wallet->sidUser->lastname,
        ]);
    }
}
