<?php

namespace App\Listeners;

use App\Events\CreateWalletBankEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateBank 
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
    public function handle(CreateWalletBankEvent $event)
    {
        EventLog::create([
            'event_name' => $event->bank->provider->name ?? null,
            'event_data' =>  $event->bank ?? null,
            'type_user_event' => $event->bank->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->bank->wallet->country->name ,
            'amount_send' => $event->bank->amount ,
            'currencie' => $event->bank->wallet->currencie->name,
            'type_user' => $event->bank->wallet->sidUser->is_under_distributer	 ? "Sous distributeur " : " Client",
            'event_user' =>  $event->bank->wallet->sidUser->firstname . ' ' . $event->bank->wallet->sidUser->lastname,
        ]); 
    }
}
