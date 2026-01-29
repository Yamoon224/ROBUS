<?php

namespace App\Listeners;

use App\Events\ChooseBankSendEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BankSendChoose 
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
     * @param  \App\Events\ChooseBankSendEvent  $event
     * @return void
     */
    public function handle(ChooseBankSendEvent $event)
    {
        EventLog::create([
            'event_name' => $event->phone->provider->name,
            'event_data' =>  $event->phone ,
            'type_user_event' => $event->phone->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->phone->wallet->country->name ,
            'amount_send' => $event->phone->amount ,
            'currencie' => $event->phone->wallet->currencie->name, 
            'event_user' =>  $event->phone->wallet->sidUser->firstname . ' ' . $event->phone->wallet->sidUser->lastname,
        ]);
    }
}
