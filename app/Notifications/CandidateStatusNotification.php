<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidateStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $registration;
    public $setupUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(Registration $registration, ?string $setupUrl = null)
    {
        $this->registration = $registration;
        $this->setupUrl = $setupUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->registration->status;
        $seminarTitle = $this->registration->seminar->title;
        $userName = $notifiable->name;

        $message = (new MailMessage)
            ->subject('Mise à jour de votre candidature - PFPT CFPT')
            ->greeting("Bonjour $userName,");

        if ($status === 'pending') {
            $message->line("Nous avons bien reçu votre inscription pour le séminaire **$seminarTitle**. Votre dossier est actuellement en cours d'examen par nos services.");
        } elseif ($status === 'approved') {
            $message->line("Félicitations ! Nous avons le plaisir de vous informer que vous avez été sélectionné pour participer au séminaire **$seminarTitle**.");
        } elseif ($status === 'rejected') {
            $message->line("Après examen attentif de votre dossier pour le séminaire **$seminarTitle**, nous avons le regret de vous informer que votre candidature n'a pas été retenue pour cette session.");
        } elseif ($status === 'waiting_list') {
            $message->line("Votre dossier pour le séminaire **$seminarTitle** a été placé sur liste d'attente. Nous vous contacterons si une place se libère.");
        }

        if ($this->setupUrl) {
            $message->line("Pour activer votre compte et suivre l'évolution de votre dossier en toute sécurité, veuillez définir votre mot de passe d'accès en cliquant sur le bouton ci-dessous :");
            $message->action('Définir mon mot de passe', $this->setupUrl);
        } else {
            $message->line("Vous pouvez suivre l'évolution de votre dossier en vous connectant à votre espace candidat.")
                ->action('Accéder à mon espace', route('login'));
        }

        return $message->salutation("Cordialement,\nL'équipe du " . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'pending' => 'Dossier en cours d\'examen',
            'approved' => 'Candidature Sélectionnée & Validée !',
            'rejected' => 'Candidature non retenue',
            'waiting_list' => 'Candidature sur liste d\'attente',
        ];

        return [
            'registration_id' => $this->registration->id,
            'seminar_title' => $this->registration->seminar->title ?? 'Séminaire',
            'status' => $this->registration->status,
            'title' => 'Statut de candidature mis à jour',
            'action' => $statusLabels[$this->registration->status] ?? 'Mise à jour du statut',
            'description' => "Votre candidature pour « " . ($this->registration->seminar->title ?? 'Séminaire') . " » a été mise à jour : " . ($statusLabels[$this->registration->status] ?? $this->registration->status),
            'icon' => $this->registration->status === 'approved' ? 'check_circle' : ($this->registration->status === 'rejected' ? 'cancel' : 'info'),
        ];
    }
}
