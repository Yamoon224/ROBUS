<?php

namespace App\Listeners\Visitors;


use App\Events\Visitors\ChooseInterVisEvent;
use App\Models\EventLog;

class InterChooseVis
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
     * @param  \App\Events\ChooseInterVisEvent  $event
     * @return void
     */
    public function handle(ChooseInterVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->inter->provider->name,
            'event_data' =>  $event->inter ,
            'type_user_event' => $event->inter->updated_by != null ? "Choisir" : "Creation",
            'amount_send' => $event->inter->amount ,
           
        ]);
    }
}
