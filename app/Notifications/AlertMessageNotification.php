<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertMessageNotification extends Notification
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Message $message)
    {
        $this->message = $message;
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
        $senderName = $this->message->user_id 
            ? ($this->message->user->name ?? 'Administration') 
            : ($this->message->trainer_id ? ($this->message->trainer->name ?? 'Formateur') : 'Système');

        return [
            'message_id' => $this->message->id,
            'message' => "Message de {$senderName} : {$this->message->subject}",
            'subject' => $this->message->subject,
            'body' => $this->message->body,
            'attachment_path' => $this->message->attachment_path,
            'attachment_name' => $this->message->attachment_path ? basename($this->message->attachment_path) : null,
            'sender' => $senderName,
        ];
    }
}
