<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewMessageSend extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;
    public $name_firm;
    public $email;
    public $phone;
    public $sector;
    public $description;

    public function __construct($ticket, $name_firm, $email, $phone, $sector, $description)
    {
        $this->ticket = $ticket;
        $this->name_firm = $name_firm;
        $this->email = $email;
        $this->phone = $phone;
        $this->sector = $sector;
        $this->description = $description;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject('Nouveau prospect — Ticket ' . $this->ticket)
            ->markdown('pages.emails.new_prospect');
        return $email;
    }

}
