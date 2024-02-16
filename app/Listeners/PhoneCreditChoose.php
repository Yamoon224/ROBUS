<?php

namespace App\Listeners;

use App\Events\choosePhoneCreditEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PhoneCreditChoose
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
     * @param  \App\Events\choosePhoneCreditEvent  $event
     * @return void
     */
    public function handle(choosePhoneCreditEvent $event)
    {
        EventLog::create([
            'event_name' => $event->phone->provider->name ?? null,
            'event_data' =>  $event->phone ?? null,
            'user_countrie' => $event->phone->wallet->country->name ?? null,
            'amount_send' => $event->phone->amount ,
            'currencie' => $event->phone->wallet->currencie->name, 
            'type_user_event' => $event->phone->updated_by != null ? "Choisir" : "Creation",
            'event_user' =>  $event->phone->wallet->sidUser->firstname . ' ' . $event->phone->wallet->sidUser->lastname,
        ]);
    }
}
