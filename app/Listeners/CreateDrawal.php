<?php

namespace App\Listeners;

use App\Events\CreateWalletDrawalEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDrawal 
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
     * @param  \App\Events\CreateWalletDrawalEvent  $event
     * @return void
     */
    public function handle(CreateWalletDrawalEvent $event)
    {
        EventLog::create([
            'event_name' => $event->push->provider->name ?? null,
            'event_data' =>  $event->push ?? null,
            'type_user_event' => $event->push->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->push->wallet->country->name ,
            'amount_send' => $event->push->amount ,
            'type_user' => $event->push->wallet->sidUser->is_under_distributer	 ? "Sous distributeur " : " Client",
            'currencie' => $event->push->wallet->currencie->name,
            'event_user' =>  $event->push->wallet->sidUser->firstname . ' ' . $event->push->wallet->sidUser->lastname,
        ]); 
    }
}
