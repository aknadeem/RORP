<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
   
    use Queueable;

    private $details;
    
    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database'];
    }
    public function toDatabase($notifiable)
    {
          return [
            'custom_notification_id' => $this->details['custom_notification_id'],
            'sender_id' => $this->details['sender_id'],
            'sender_name' => $this->details['sender_name'],
          ];
    }
}


