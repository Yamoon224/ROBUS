<?php

namespace App\Listeners\Visitors;

use App\Events\Visitors\CreateWalletReloadCardVisEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateReloadCardVis 
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
     * @param  \App\Events\CreateWalletReloadCardVisEvent  $event
     * @return void
     */
    public function handle(CreateWalletReloadCardVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->card->provider->name ?? null,
            'event_data' =>  $event->card ?? null,
            'type_user_event' => $event->card->updated_by != null ? "Choisir" : "Creation",
            'amount_send' => $event->card->amount , 
        ]); 
    }
}
