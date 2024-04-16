<?php

namespace App\Listeners\Visitors;

use App\Events\Visitors\CreateWalletPhoneCreditVisEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePhoneCreditVis 
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
     * @param  \App\Events\CreateWalletPhoneCreditVisEvent  $event
     * @return void
     */
    public function handle(CreateWalletPhoneCreditVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->phone->provider->name ?? null,
            'event_data' =>  $event->phone ?? null,
            'type_user_event' => $event->phone->updated_by != null ? "Choisir" : "Creation",
            'amount_send' => $event->phone->amount ,
        ]); 
    }
}
