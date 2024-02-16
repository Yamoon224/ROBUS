<?php

namespace App\Listeners;

use App\Events\chooseInterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InterChoose
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
     * @param  \App\Events\chooseInterEvent  $event
     * @return void
     */
    public function handle(chooseInterEvent $event)
    {
        //
    }
}
