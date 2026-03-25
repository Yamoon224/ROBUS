<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalletReload extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $content;
    /**
     * Create a new message instance.
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->onQueue('emails');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Demande de paiement ' . config('app.name') . ' - ' . $this->content->provider->name)
            ->markdown('pages.emails.walletReloadUV');
    }

    /**
     * Gérer les échecs d'envoi
     */
    public function failed(\Throwable $exception) {}
}
