<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProfileViewMilestoneNotification extends Notification
{
    use Queueable;

    public function __construct(public int $milestone) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'      => 'milestone',
            'milestone' => $this->milestone,
        ];
    }
}
