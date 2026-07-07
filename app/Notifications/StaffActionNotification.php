<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffActionNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $action;
    protected $description;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $action, $description)
    {
        $this->user = $user;
        $this->action = $action;
        $this->description = $description;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_name' => $this->user->name,
            'action' => $this->action,
            'description' => $this->description,
            'icon' => $this->getIcon(),
        ];
    }

    private function getIcon()
    {
        if (str_contains($this->action, 'Séminaire')) return 'event';
        if (str_contains($this->action, 'Candidat')) return 'person_add';
        return 'notifications';
    }
}
