<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailCreatedCard extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $message)
    {
        $this->user = $user;
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
        // $email = $this->from(env('MAIL_FROM_ADDRESS'))
        //     ->subject('Demande activation de carte ' . env('APP_NAME') . ' ' . $this->content['phone'] . ' ' . strtoupper($this->content['email']))
        //     ->markdown('pages.emails.active_card');
        // return $email;
        return $this->markdown('pages.emails.active_card')->subject('Activation de carte');
    }
}