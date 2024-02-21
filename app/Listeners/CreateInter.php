<?php

namespace App\Listeners;

use App\Events\CreateWalletInterEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateInter 
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
     * @param  \App\Events\CreateWalletInterEvent  $event
     * @return void
     */
    public function handle(CreateWalletInterEvent $event)
    {
        EventLog::create([
            'event_name' => $event->inter->provider->name ?? null,
            'event_data' =>  $event->inter ?? null,
            'type_user_event' => $event->inter->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->inter->wallet->country->name ,
            'amount_send' => $event->inter->amount ,
            'currencie' => $event->inter->wallet->currencie->name,
            'type_user' => $event->inter->wallet->sidUser->is_under_distributer	 ? "Sous distributeur " : " Client",
            'event_user' =>  $event->inter->wallet->sidUser->firstname . ' ' . $event->inter->wallet->sidUser->lastname,
        ]); 
    }
}
