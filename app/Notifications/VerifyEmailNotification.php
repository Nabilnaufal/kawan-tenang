<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $data = [
            'user' => $notifiable,
            'url' => $this->verificationUrl($notifiable),
            'expires' => config('auth.verification.expire', 60),
        ];

        return (new MailMessage)
            ->subject('Konfirmasi email kamu — Kawan Tenang')
            ->view('emails.verify-email', $data)
            ->text('emails.verify-email-text', $data);
    }
}
