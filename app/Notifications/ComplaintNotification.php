<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintNotification extends Notification
{
   
    use Queueable;

    private $details;
   
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
         return ['database'];
    }

    // public function toMail($notifiable)
    // {
    //      return (new MailMessage)
    //                 ->greeting($this->details['greeting'])
    //                 ->line($this->details['body'])
    //                 ->line($this->details['thanks']);
                   
    // }

    public function toDatabase($notifiable)
    {
        return [
           'custom_notification_id' => 00,
           'title' => $this->details['title'],
           'by' => $this->details['by'],
           'complaint_id' => $this->details['complaint_id'],
        ];
    }
}

