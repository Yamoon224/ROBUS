<?php

namespace App\Listeners\Visitors;


use App\Events\Visitors\ChoosePhoneVisEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PhoneCreditChooseVis 
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
     * @param  \App\Events\choosePhoneCreditVisEvent  $event
     * @return void
     */
    public function handle(ChoosePhoneVisEvent $event)
    {
        EventLog::create([
            'event_name' => $event->phone->provider->name ?? null,
            'event_data' =>  $event->phone ?? null,
            'amount_send' => $event->phone->amount ,
           
        ]);
    }
}
