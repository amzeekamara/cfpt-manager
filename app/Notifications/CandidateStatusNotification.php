<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidateStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $enrollment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
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
        $status = $this->enrollment->status;
        $seminarTitle = $this->enrollment->seminar->title;
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

        return $message
            ->line("Vous pouvez suivre l'évolution de votre dossier en vous connectant à votre espace candidat.")
            ->action('Accéder à mon espace', route('login'))
            ->salutation("Cordialement,\nL'équipe du " . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'enrollment_id' => $this->enrollment->id,
            'status' => $this->enrollment->status,
        ];
    }
}
