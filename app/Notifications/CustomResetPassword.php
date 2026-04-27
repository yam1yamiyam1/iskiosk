<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class CustomResetPassword extends Notification
{
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
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->view('emails.reset-password', [
                'subject' => 'Reset Your Password',
                'greeting' => 'Hello!',
                'line1' => 'You are receiving this email because we received a password reset request for your account.',
                'actionText' => 'Reset Password',
                'actionUrl' => $url,
                'line2' => 'If you did not request a password reset, no further action is required.',
                'salutation' => 'Regards, PUPKiosk',
            ]);
    }
}
