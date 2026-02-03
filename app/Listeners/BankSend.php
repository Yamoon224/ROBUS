<?php

namespace App\Listeners;

use App\Events\UpdateBankSendEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BankSend 
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
     * @param  \App\Events\UpdateBankSendEvent  $event
     * @return void
     */
    public function handle(UpdateBankSendEvent $event)
    {
        EventLog::create([
            'event_name' => $event->bank->provider->name ?? null,
            'event_data' =>  $event->bank ?? null ,
        ]);
    }
}
