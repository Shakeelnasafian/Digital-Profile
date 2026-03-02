<?php

namespace App\Notifications;

use App\Models\Testimonial;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTestimonialNotification extends Notification
{
    use Queueable;

    public function __construct(public Testimonial $testimonial) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'          => 'new_testimonial',
            'reviewer_name' => $this->testimonial->reviewer_name,
            'rating'        => $this->testimonial->rating,
        ];
    }
}
