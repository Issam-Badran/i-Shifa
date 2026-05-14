<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
{
    $resetUrl = config('app.frontend_url') . '/reset-password?' . http_build_query([
        'token' => $this->token,
        'email' => $notifiable->email,
    ]);

    return (new \Illuminate\Notifications\Messages\MailMessage)
        ->subject('Reset Your Password')
        ->view('emails.reset', [
            'resetUrl' => $resetUrl,
        ]);
}

}
