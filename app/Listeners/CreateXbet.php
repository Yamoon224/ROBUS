<?php

namespace App\Listeners;

use App\Events\CreateWalletOnexbet;
use App\Models\EventLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateXbet 
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
     * @param  \App\Events\CreateWalletOnexbet  $event
     * @return void
     */
    public function handle(CreateWalletOnexbet $event)
    {
        EventLog::create([
            'event_name' => $event->xbet->provider->name ?? null,
            'event_data' =>  $event->xbet ?? null,
            'type_user_event' => $event->xbet->updated_by != null ? "Choisir" : "Creation",
            'user_countrie' => $event->xbet->wallet->country->name,
            'amount_send' => $event->xbet->amount,
            'type_user' => $event->xbet->wallet->sidUser->is_under_distributer     ? "Sous distributeur " : " Client",
            'currencie' => $event->xbet->wallet->currencie->name,
            'event_user' =>  $event->xbet->wallet->sidUser->firstname . ' ' . $event->xbet->wallet->sidUser->lastname,
        ]);
    }
}
