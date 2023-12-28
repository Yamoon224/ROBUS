<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateCardNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $user ;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $notifiable->verification_code = rand(100000, 999999);
        $notifiable->save();

        $array['subject'] = 'Email creation de carte';
        $array['content'] = 'La demande de creation de carte N0 :' . ' ' . $notifiable->verification_code;

        return (new MailMessage)
            ->view('emails.card_active', ['array' => $array])
            ->subject('Email card') . ' - ' . env('APP_NAME');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}