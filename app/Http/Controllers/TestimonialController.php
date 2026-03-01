<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Profile;
use App\Models\Testimonial;
use App\Actions\SubmitTestimonialAction;
use App\Http\Requests\TestimonialRequest;
use App\Http\Resources\TestimonialResource;

class TestimonialController extends Controller
{
    public function create(string $slug)
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        return Inertia::render('testimonials/submit', [
            'profile_name' => $profile->display_name,
            'slug'         => $profile->slug,
        ]);
    }

    public function store(TestimonialRequest $request, string $slug, SubmitTestimonialAction $action)
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $action->execute($request, $profile);

        return redirect()->back()->with('success', 'Thank you! Your testimonial is pending review.');
    }

    public function index()
    {
        $profile = Profile::where('user_id', auth()->id())->firstOrFail();

        $testimonials = Testimonial::where('profile_id', $profile->id)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('testimonials/index', [
            'testimonials' => TestimonialResource::collection($testimonials),
        ]);
    }

    public function approve(Testimonial $testimonial)
    {
        $profile = Profile::where('user_id', auth()->id())->firstOrFail();
        abort_if($testimonial->profile_id !== $profile->id, 403);

        $testimonial->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Testimonial approved.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $profile = Profile::where('user_id', auth()->id())->firstOrFail();
        abort_if($testimonial->profile_id !== $profile->id, 403);

        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial deleted.');
    }
}
