<?php

namespace App\Actions;

use App\Models\Profile;
use App\Models\Testimonial;
use App\Http\Requests\TestimonialRequest;
use App\Notifications\NewTestimonialNotification;

class SubmitTestimonialAction
{
    public function execute(TestimonialRequest $request, Profile $profile): Testimonial
    {
        $testimonial = Testimonial::create([
            ...$request->validated(),
            'profile_id'  => $profile->id,
            'is_approved' => false,
        ]);

        $profile->user->notify(new NewTestimonialNotification($testimonial));

        return $testimonial;
    }
}
