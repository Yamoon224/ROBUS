<?php

namespace App\Listeners;

use App\Events\chooseReloadCardEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReloadCardChoose
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
     * @param  \App\Events\chooseReloadCardEvent  $event
     * @return void
     */
    public function handle(chooseReloadCardEvent $event)
    {
        EventLog::create([
            'event_name' => $event->card->provider->name ?? null,
            'event_data' =>  $event->card ?? null,
            'user_countrie' => $event->card->wallet->country->name ,
            'amount_send' => $event->card->amount ,
            'currencie' => $event->card->wallet->currencie->name, 
            'type_user_event' => $event->card->updated_by != null ? "Choisir" : "Creation",
            'event_user' =>  $event->card->wallet->sidUser->firstname . ' ' . $event->card->wallet->sidUser->lastname,
        ]);
    }
}
