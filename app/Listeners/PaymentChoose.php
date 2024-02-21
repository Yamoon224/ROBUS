<?php

namespace App\Listeners;

use App\Events\choosePaymentEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PaymentChoose 
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
     * @param  \App\Events\choosePaymentEvent  $event
     * @return void
     */
    public function handle(choosePaymentEvent $event)
    {
        EventLog::create([
            'event_name' => $event->paiement->provider->name,
            'event_data' =>  $event->paiement ,
            'type_user_event' => $event->paiement->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->paiement->wallet->country->name ,
            'amount_send' => $event->paiement->amount ,
            'currencie' => $event->paiement->wallet->currencie->name, 
            'event_user' =>  $event->paiement->wallet->sidUser->firstname . ' ' . $event->paiement->wallet->sidUser->lastname,
        ]);
    }
}
