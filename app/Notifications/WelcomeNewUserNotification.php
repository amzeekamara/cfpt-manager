<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class WelcomeNewUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Bienvenue sur la plateforme PFPT - Activez votre compte')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Un compte a été créé pour vous sur la plateforme de gestion du Programme de Formation en Pays Tiers (PFPT).")
            ->line("Pour commencer à utiliser la plateforme, vous devez définir votre mot de passe personnel.")
            ->action('Définir mon mot de passe', $url)
            ->line("Ce lien d'activation expirera dans 60 minutes.")
            ->line("Si vous n'avez pas demandé la création de ce compte, aucune action n'est requise.")
            ->salutation("Cordialement,\nL'équipe d'administration PFPT");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
