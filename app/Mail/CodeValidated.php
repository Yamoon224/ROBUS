<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CodeValidated extends Mailable
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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Code de validation ' . env('APP_NAME') . '  ' . ' Retrait ROBUS')
            ->markdown('pages.emails.codeValidated');
        return $email;
    }
}
