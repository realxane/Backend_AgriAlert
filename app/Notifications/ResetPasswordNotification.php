<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private string $url) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Réinitialisation de mot de passe')
            ->line('Tu as demandé une réinitialisation de mot de passe.')
            ->action('Réinitialiser mon mot de passe', $this->url)
            ->line('Si tu n’as pas demandé ça, ignore cet email.')
            ->salutation("Cordialement,\nAgriAlert");
    }
}