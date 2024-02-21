<?php

namespace App\Listeners;

use App\Events\CreateWalletReloadCardEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateReloadCard 
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
     * @param  \App\Events\CreateWalletReloadCardEvent  $event
     * @return void
     */
    public function handle(CreateWalletReloadCardEvent $event)
    {
        EventLog::create([
            'event_name' => $event->card->provider->name ?? null,
            'event_data' =>  $event->card ?? null,
            'type_user_event' => $event->card->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->card->wallet->country->name ,
            'amount_send' => $event->card->amount ,
            'type_user' => $event->card->wallet->sidUser->is_under_distributer	 ? "Sous distributeur " : " Client",
            'currencie' => $event->card->wallet->currencie->name,
            'event_user' =>  $event->card->wallet->sidUser->firstname . ' ' . $event->card->wallet->sidUser->lastname,
        ]); 
    }
}
