<?php

namespace App\Listeners;

use App\Events\ChooseDjamoEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DjamoChoose 
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
     * @param  \App\Events\ChooseDjamoEvent  $event
     * @return void
     */
    public function handle(ChooseDjamoEvent $event)
    {
        EventLog::create([
            'event_name' => $event->djamo->provider->name,
            'event_data' =>  $event->djamo ,
            'type_user_event' => $event->djamo->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->djamo->wallet->country->name ,
            'amount_send' => $event->djamo->amount ,
            'currencie' => $event->djamo->wallet->currencie->name, 
            'event_user' =>  $event->djamo->wallet->sidUser->firstname . ' ' . $event->djamo->wallet->sidUser->lastname,
        ]);
    }
}
