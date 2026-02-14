<?php

namespace App\Listeners;

use App\Events\CreateCardOrderEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateOderBank 
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
    public function handle(CreateCardOrderEvent $event)
    {
        EventLog::create([
            'event_name' => $event->card_bank->provider->name ?? null,
            'event_data' =>  $event->card_bank ?? null,
            'type_user_event' => $event->card_bank->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->card_bank->wallet->country->name ,
            'amount_send' => $event->card_bank->amount ,
            'currencie' => $event->card_bank->wallet->currencie->name,
            'type_user' =>  "Utilisateur",
            'event_user' => "Utilisateur",
        ]); 
    }
}
