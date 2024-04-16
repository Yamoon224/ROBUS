<?php

namespace App\Listeners\Visitors;

use App\Events\Visitors\CreateWalletOneXbetVisEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateXbetVis 
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
     * @param  \App\Events\CreateWalletOneXbetVisEvent  $event
     * @return void
     */
    public function handle(CreateWalletOneXbetVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->xbet->provider->name ?? null,
            'event_data' =>  $event->xbet ?? null,
            'type_user_event' => $event->xbet->updated_by != null ? "Choisir" : "Creation",
            'amount_send' => $event->xbet->amount,
        ]);
    }
}
