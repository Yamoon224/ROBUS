<?php

namespace App\Listeners;

use App\Events\ChooseXbetEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class XbetChoose 
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
    public function handle(ChooseXbetEvent $event)
    {
        EventLog::create([
            'event_name' => $event->xbet->provider->name ?? null,
            'event_data' =>  $event->xbet ?? null,
            'user_countrie' => $event->xbet->wallet->country->name ,
            'amount_send' => $event->xbet->amount ,
            'currencie' => $event->xbet->wallet->currencie->name, 
            'type_user_event' => $event->xbet->updated_by != null ? "Choisir" : "Creation",
            'event_user' =>  $event->xbet->wallet->sidUser->firstname . ' ' . $event->xbet->wallet->sidUser->lastname,
        ]);
    }
}
