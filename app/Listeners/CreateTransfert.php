<?php

namespace App\Listeners;

use App\Events\CreateWalletTransfersEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTransfert
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
     * @param  \App\Events\CreateWalletTransfertEvent  $event
     * @return void
     */
    public function handle(CreateWalletTransfersEvent $event)
    {
        EventLog::create([
            'event_name' => $event->transfers->provider->name ?? null,
            'event_data' =>  $event->transfers ?? null,
            'type_user_event' => $event->transfers->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->transfers->wallet->country->name ,
            'amount_send' => $event->transfers->amount ,
            'type_user' => $event->phone->wallet->sidUser->is_under_distributer	 ? "Sous distributeur " : " Client",
            'currencie' => $event->transfers->wallet->currencie->name,
            'event_user' =>  $event->transfers->wallet->sidUser->firstname . ' ' . $event->transfers->wallet->sidUser->lastname,
        ]); 
    }
}
