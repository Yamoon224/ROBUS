<?php

namespace App\Listeners\Visitors;

use App\Events\Visitors\ChooseCardVisEvent;
use App\Models\EventLog;

class ReloadCardChooseVis
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
     * @param  \App\Events\ChooseCardVisEvent  $event
     * @return void
     */
    public function handle(ChooseCardVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->card->provider->name ?? null,
            'event_data' =>  $event->card ?? null,
            'amount_send' => $event->card->amount ,
           
        ]);
    }
}
