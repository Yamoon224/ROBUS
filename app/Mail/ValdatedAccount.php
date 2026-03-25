<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ValdatedAccount extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $content = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
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
            ->subject('Validation du compte ' . env('APP_NAME') . '  ' . $this->content->firstname)
            ->markdown('pages.emails.valdated_account');
        return $email;
    }
}