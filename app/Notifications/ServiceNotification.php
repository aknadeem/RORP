<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceNotification extends Notification
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
          'custom_notification_id' => 00,
          'title' => $this->details['title'],
          'sender_name' => $this->details['sender_name'],
          'sender_id' => $this->details['sender_id'],
          'service_id' => $this->details['service_id'],
          'service_request_id' => $this->details['service_request_id'],
        ];
    }
}
