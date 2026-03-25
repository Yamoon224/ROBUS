<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateDraw extends Mailable
{
    use Queueable, SerializesModels;

    public $message = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
        $this->onQueue('emails');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Transfert de commission ' . env('APP_NAME') . '  ' . ' ROBUS')
            ->markdown('pages.emails.uvSend');
        return $email;
    }
}
