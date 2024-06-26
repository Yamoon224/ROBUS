<?php

namespace App\Listeners\Visitors;

use App\Events\Visitors\CreateWalletTransfersVisEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTransfertVis
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
     * @param  \App\Events\CreateWalletTransfertVisEvent  $event
     * @return void
     */
    public function handle(CreateWalletTransfersVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->transfers->provider->name ?? null,
            'event_data' =>  $event->transfers ?? null,
            'type_user_event' => $event->transfers->updated_by != null ? "Choisir" : "Creation",
            'amount_send' => $event->transfers->amount ,
        ]); 
    }
}
