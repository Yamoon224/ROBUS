<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\MailController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class SendPhoneCreditEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  WalletPhone  $walletPhone
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Exécutez le job.
     *
     * @return void
     */
    public function handle()
    {
    
    }
}
