<?php

namespace App\Actions;

use App\Models\Profile;
use App\Models\Testimonial;
use App\Http\Requests\TestimonialRequest;

class SubmitTestimonialAction
{
    public function execute(TestimonialRequest $request, Profile $profile): Testimonial
    {
        return Testimonial::create([
            ...$request->validated(),
            'profile_id'  => $profile->id,
            'is_approved' => false,
        ]);
    }
}
