<?php

namespace App\Listeners;

use App\Events\chooseVirementEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VirementChoose
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
     * @param  \App\Events\chooseVirementEvent  $event
     * @return void
     */
    public function handle(chooseVirementEvent $event)
    {
        EventLog::create([
            'event_name' => $event->bank->provider->name ?? null,
            'event_data' =>  $event->bank ?? null ,
            'user_countrie' => $event->bank->wallet->country->name ,
            'amount_send' => $event->bank->amount ,
            'currencie' => $event->bank->wallet->currencie->name,
            'type_user_event' => $event->bank->updated_by != null ? "Choisir" : "Creation",
            'event_user' =>  $event->bank->wallet->sidUser->firstname . ' ' . $event->bank->wallet->sidUser->lastname,
        ]);
    }
}
