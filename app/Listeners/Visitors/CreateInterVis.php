<?php

namespace App\Listeners\Visitors;

use App\Events\Visitors\CreateWalletInterVisEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateInterVis
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
     * @param  \App\Events\CreateWalletInterVisEvent  $event
     * @return void
     */
    public function handle(CreateWalletInterVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->inter->provider->name ?? null,
            'event_data' =>  $event->inter ?? null,
            'type_user_event' => $event->inter->updated_by != null ? "Choisir" : "Creation",
            'amount_send' => $event->inter->amount ,
        ]); 
    }
}
