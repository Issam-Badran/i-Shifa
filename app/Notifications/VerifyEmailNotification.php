<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
{
    $signedUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $notifiable->id, 'hash' => sha1($notifiable->email)]
    );

    $verificationUrl = $signedUrl; // أو تحوله لفرونت إند لو حاب

    return (new \Illuminate\Notifications\Messages\MailMessage)
        ->subject('Verify Your Email Address')
        ->view('emails.verify', [
            'verificationUrl' => $verificationUrl,
        ]);
}

}
