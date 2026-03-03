<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Testimonial;

class ApproveTestimonialAction
{
    public function __invoke(Testimonial $testimonial): void
    {
        $testimonial->update(['is_approved' => true]);
    }
}
