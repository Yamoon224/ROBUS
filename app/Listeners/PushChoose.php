<?php

namespace App\Listeners;

use App\Events\ChoosePushEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PushChoose 
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
     * @param  \App\Events\ChoosePushEvent  $event
     * @return void
     */
    public function handle(ChoosePushEvent $event)
    {
        EventLog::create([
            'event_name' => $event->push->provider->name,
            'event_data' =>  $event->push ,
            'type_user_event' => $event->push->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->push->wallet->country->name ,
            'amount_send' => $event->push->amount ,
            'currencie' => $event->push->wallet->currencie->name, 
            'event_user' =>  $event->push->wallet->sidUser->firstname . ' ' . $event->push->wallet->sidUser->lastname,
        ]);
    }
}
