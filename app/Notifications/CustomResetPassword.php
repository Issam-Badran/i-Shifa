<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    use Queueable;

    public function toMail($notifiable)
    {
        $frontend = config('app.frontend_url');

        $url = $frontend . "/reset-password?token={$this->token}&email={$notifiable->email}";

        return (new MailMessage)
            ->subject('إعادة تعيين كلمة المرور')
            ->view('emails.reset', [
                'resetUrl' => $url
            ]);
    }
}
