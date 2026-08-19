<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\FormSubmission;

class NewFormSubmissionNotification extends Notification
{
    use Queueable;

    protected $submission;

    /**
     * Create a new notification instance.
     */
    public function __construct(FormSubmission $submission)
    {
        $this->submission = $submission;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $formTitle = $this->submission->form->title ?? 'Formulaire Inconnu';
        $email = $this->submission->answers_json['email'] ?? 'Inconnu';
        
        return [
            'type'        => 'new_form_submission',
            'action'      => 'Nouvelle Inscription (Formulaire)',
            'description' => "Le formulaire '{$formTitle}' a reçu une nouvelle réponse de {$email}.",
            'link'        => route('admin.forms.responses', $this->submission->form_template_id),
            'icon'        => 'assignment_turned_in',
        ];
    }
}
