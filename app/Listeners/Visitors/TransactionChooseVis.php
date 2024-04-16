<?php

namespace App\Listeners\Visitors;

use App\Events\Visitors\ChooseTransactionVisEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransactionChooseVis

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
     * @param  \App\Events\ChooseTransactionVisEvent  $event
     * @return void
     */
    public function handle(ChooseTransactionVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->transfers->provider->name ?? null,
            'event_data' =>  $event->transfers ?? null,
            'amount_send' => $event->transfers->amount ,
           
        ]);
    }
}
