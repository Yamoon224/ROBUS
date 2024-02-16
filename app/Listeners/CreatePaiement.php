<?php

namespace App\Listeners;

use App\Events\CreateWalletPaiementEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePaiement
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
     * @param  \App\Events\CreateWalletPaiementEvent  $event
     * @return void
     */
    public function handle(CreateWalletPaiementEvent $event)
    {
        EventLog::create([
            'event_name' => $event->paiement->provider->name ?? null,
            'event_data' =>  $event->paiement ?? null,
            'type_user_event' => $event->paiement->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->paiement->wallet->country->name ,
            'amount_send' => $event->paiement->amount ,
            'currencie' => $event->paiement->wallet->currencie->name,
            'type_user' => $event->paiement->wallet->sidUser->is_under_distributer	 ? "Sous distributeur " : " Client",
            'event_user' =>  $event->paiement->wallet->sidUser->firstname . ' ' . $event->paiement->wallet->sidUser->lastname,
        ]); 
    }
}
