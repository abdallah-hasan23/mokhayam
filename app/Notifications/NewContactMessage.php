<?php
namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification
{
    use Queueable;

    public function __construct(public ContactMessage $message) {}

    public function via($notifiable): array { return ['database']; }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'contact_message',
            'title'   => 'رسالة تواصل جديدة',
            'message' => 'رسالة من ' . $this->message->name . ': ' . \Str::limit($this->message->message, 60),
            'url'     => route('dashboard.contact.index'),
        ];
    }
}
