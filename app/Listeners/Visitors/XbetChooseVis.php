<?php

namespace App\Listeners\Visitors;

use App\Events\Visitors\ChooseXbetVisEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class XbetChooseVis
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
    public function handle(ChooseXbetVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->xbet->provider->name ?? null,
            'event_data' =>  $event->xbet ?? null,
            'amount_send' => $event->xbet->amount ,
        ]);
    }
}
