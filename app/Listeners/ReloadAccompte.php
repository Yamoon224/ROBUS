<?php

namespace App\Listeners;

use App\Events\CreateReloadEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReloadAccompte 
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
    public function handle(CreateReloadEvent $event)
    {
        EventLog::create([
            'event_name' => $event->reload->provider->name ?? null,
            'event_data' =>  $event->reload ?? null,
            'user_countrie' => $event->reload->wallet->country->name ?? null ,
            'amount_send' => $event->reload->amount ,
            'currencie' => $event->reload->wallet->currencie->name, 
            'type_user_event' => $event->reload->updated_by != null ? "Choisir" : "Creation",
            'event_user' =>  $event->reload->wallet->sidUser->firstname . ' ' . $event->reload->wallet->sidUser->lastname,
        ]);
    }
}
