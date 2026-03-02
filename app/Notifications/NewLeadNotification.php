<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLeadNotification extends Notification
{
    use Queueable;

    public function __construct(public Lead $lead) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'new_lead',
            'visitor_name' => $this->lead->visitor_name,
            'message'      => \Illuminate\Support\Str::limit($this->lead->message ?? '', 80),
        ];
    }
}
