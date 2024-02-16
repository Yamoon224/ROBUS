<?php

namespace App\Listeners;

use App\Events\chooseTransactionEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransactionChoose
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
     * @param  \App\Events\chooseTransactionEvent  $event
     * @return void
     */
    public function handle(chooseTransactionEvent $event)
    {
        EventLog::create([
            'event_name' => $event->transfers->provider->name ?? null,
            'event_data' =>  $event->transfers ?? null,
            'user_countrie' => $event->transfers->wallet->country->name ?? null ,
            'amount_send' => $event->transfers->amount ,
            'currencie' => $event->transfers->wallet->currencie->name, 
            'type_user_event' => $event->transfers->updated_by != null ? "Choisir" : "Creation",
            'event_user' =>  $event->transfers->wallet->sidUser->firstname . ' ' . $event->transfers->wallet->sidUser->lastname,
        ]);
    }
}
