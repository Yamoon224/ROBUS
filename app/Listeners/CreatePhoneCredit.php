<?php

namespace App\Listeners;

use App\Events\CreateWalletPhoneCreditEvent;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePhoneCredit
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
     * @param  \App\Events\CreateWalletPhoneCreditEvent  $event
     * @return void
     */
    public function handle(CreateWalletPhoneCreditEvent $event)
    {
        EventLog::create([
            'event_name' => $event->phone->provider->name ?? null,
            'event_data' =>  $event->phone ?? null,
            'type_user_event' => $event->phone->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->phone->wallet->country->name ,
            'amount_send' => $event->phone->amount ,
            'currencie' => $event->phone->wallet->currencie->name,
            'type_user' => $event->phone->wallet->sidUser->is_under_distributer	 ? "Sous distributeur " : " Client",
            'event_user' =>  $event->phone->wallet->sidUser->firstname . ' ' . $event->phone->wallet->sidUser->lastname,
        ]); 
    }
}
