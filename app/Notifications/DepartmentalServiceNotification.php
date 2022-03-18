<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepartmentalServiceNotification extends Notification
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
           'custom_notification_id' => 0,
           'title' => $this->details['title'],
           'by' => $this->details['by'],
           'service_id' => $this->details['service_id'],
           'request_id' => $this->details['request_id'],
        ];
    }
}

