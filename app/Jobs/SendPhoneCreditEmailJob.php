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

    public $walletPhone;

    /**
     * Create a new job instance.
     *
     * @param  WalletPhone  $walletPhone
     * @return void
     */
    public function __construct($walletPhone)
    {
        $this->walletPhone = $walletPhone;
    }

    /**
     * Exécutez le job.
     *
     * @return void
     */
    public function handle()
    {
        // $response = Http::get(url('clear-route-queue-robus'));
        Http::get(url('/process-queue'));
        $notify = new MailController();
        $notify->newPhoneCredit($this->walletPhone);
    }
}
