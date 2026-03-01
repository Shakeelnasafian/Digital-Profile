<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Certification;
use Illuminate\Http\Request;
use App\Actions\CreateProfileAction;
use App\Actions\UpdateProfileAction;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\CertificationResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Service;
use App\Models\Testimonial;
use App\Services\AnalyticsService;
use App\Services\ProfileCompletionService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::where('user_id', auth()->id())->get();

        return Inertia::render('profile/index', [
            'profiles' => ProfileResource::collection($profiles),
        ]);
    }

    public function create()
    {
        $existingProfile = Profile::where('user_id', auth()->id())->first();

        if ($existingProfile) {
            return redirect()->route('profile.show', $existingProfile->slug);
        }

        return Inertia::render('profile/create');
    }

    public function store(ProfileRequest $request, CreateProfileAction $action)
    {
        $profile = $action->handle($request);

        return redirect()->route('profile.show', $profile->slug)
            ->with('success', 'Digital Card created successfully');
    }

    public function publicShow(string $slug, Request $request, AnalyticsService $analytics)
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $analytics->logView($profile, $request);

        $projects = Project::where('user_id', $profile->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $experiences = Experience::where('user_id', $profile->user_id)
            ->orderBy('start_date', 'desc')
            ->get();

        $educations = Education::where('user_id', $profile->user_id)
            ->orderByDesc('start_year')
            ->get();

        $certifications = Certification::where('user_id', $profile->user_id)
            ->orderByDesc('issue_date')
            ->get();

        $testimonials = Testimonial::where('profile_id', $profile->id)
            ->where('is_approved', true)
            ->orderByDesc('created_at')
            ->get();

        $services = Service::where('user_id', $profile->user_id)
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('profile/public', [
            'profile'        => new ProfileResource($profile),
            'projects'       => ProjectResource::collection($projects),
            'experiences'    => ExperienceResource::collection($experiences),
            'educations'     => EducationResource::collection($educations),
            'certifications' => CertificationResource::collection($certifications),
            'testimonials'   => TestimonialResource::collection($testimonials),
            'services'       => ServiceResource::collection($services),
        ]);
    }

    public function show(string $slug)
    {
        $profile = Profile::where('slug', $slug)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $projects = Project::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $experiences = Experience::where('user_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->get();

        $educations = Education::where('user_id', auth()->id())
            ->orderByDesc('start_year')
            ->get();

        $certifications = Certification::where('user_id', auth()->id())
            ->orderByDesc('issue_date')
            ->get();

        return Inertia::render('profile/show', [
            'profile'        => new ProfileResource($profile),
            'projects'       => ProjectResource::collection($projects),
            'experiences'    => ExperienceResource::collection($experiences),
            'educations'     => EducationResource::collection($educations),
            'certifications' => CertificationResource::collection($certifications),
        ]);
    }

    public function edit(string $id)
    {
        $profile = Profile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        return Inertia::render('profile/edit', [
            'profile' => new ProfileResource($profile),
        ]);
    }

    public function update(ProfileRequest $request, string $id, UpdateProfileAction $action)
    {
        $profile = $action->handle($request, $id);

        return redirect()->route('profile.show', $profile->slug)
            ->with('success', 'Digital Card updated successfully');
    }

    public function destroy(string $id)
    {
        $profile = Profile::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($profile->getRawOriginal('qr_code_url')) {
            Storage::disk('public')->delete($profile->getRawOriginal('qr_code_url'));
        }

        $profile->delete();

        return redirect()->route('profile.index')
            ->with('success', 'Digital Card deleted successfully');
    }

    public function downloadVCard(string $slug)
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $name  = $profile->display_name;
        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'FN:' . $name,
        ];

        if ($profile->job_title)  $lines[] = 'TITLE:' . $profile->job_title;
        if ($profile->email)      $lines[] = 'EMAIL;TYPE=INTERNET:' . $profile->email;
        if ($profile->phone)      $lines[] = 'TEL;TYPE=CELL:' . $profile->phone;
        if ($profile->website)    $lines[] = 'URL:' . $profile->website;
        if ($profile->linkedin)   $lines[] = 'URL;TYPE=linkedin:' . $profile->linkedin;
        if ($profile->github)     $lines[] = 'URL;TYPE=github:' . $profile->github;
        if ($profile->location)   $lines[] = 'ADR;TYPE=WORK:;;' . $profile->location . ';;;;';
        if ($profile->short_bio)  $lines[] = 'NOTE:' . str_replace(["\r", "\n"], ' ', $profile->short_bio);

        $lines[] = 'X-DIGITALPROFILE:' . route('profile.public', $profile->slug);
        $lines[] = 'END:VCARD';

        $vcf      = implode("\r\n", $lines) . "\r\n";
        $filename = preg_replace('/[^a-z0-9_-]/i', '_', $name) . '.vcf';

        return response($vcf, 200, [
            'Content-Type'        => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function checkSlug(Request $request, string $slug)
    {
        $profileId = $request->query('profile_id');

        $exists = Profile::where('slug', $slug)
            ->when($profileId, fn($q) => $q->where('id', '!=', $profileId))
            ->exists();

        return response()->json(['available' => !$exists]);
    }

    public function exportPdf(string $id)
    {
        $profile = Profile::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $experiences    = Experience::where('user_id', auth()->id())->orderBy('start_date', 'desc')->get();
        $educations     = Education::where('user_id', auth()->id())->orderByDesc('start_year')->get();
        $certifications = Certification::where('user_id', auth()->id())->orderByDesc('issue_date')->get();
        $projects       = Project::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('pdf.resume', compact(
            'profile', 'experiences', 'educations', 'certifications', 'projects'
        ));

        $filename = Str::slug($profile->display_name ?? 'resume') . '_resume.pdf';

        return $pdf->download($filename);
    }

    public function dashboard(AnalyticsService $analytics, ProfileCompletionService $completion)
    {
        $user    = auth()->user();
        $profile = Profile::where('user_id', $user->id)->first();

        $projectCount    = Project::where('user_id', $user->id)->count();
        $experienceCount = Experience::where('user_id', $user->id)->count();
        $profileViews    = $profile ? $profile->profile_views : 0;

        $analyticsData   = [
            'views_last_30_days' => [],
            'device_breakdown'   => ['mobile' => 0, 'tablet' => 0, 'desktop' => 0],
            'top_referrers'      => [],
        ];
        $completionScore = 0;
        $checklist       = [];

        if ($profile) {
            $analyticsData = [
                'views_last_30_days' => $analytics->getViewsLast30Days($profile->id),
                'device_breakdown'   => $analytics->getDeviceBreakdown($profile->id),
                'top_referrers'      => $analytics->getTopReferrers($profile->id),
            ];
            $completionScore = $completion->getScore($profile);
            $checklist       = $completion->getChecklist($profile);
        }

        return Inertia::render('dashboard', [
            'stats' => [
                'profile_views'        => $profileViews,
                'project_count'        => $projectCount,
                'experience_count'     => $experienceCount,
                'has_profile'          => (bool) $profile,
                'profile_slug'         => $profile?->slug,
                'profile_id'           => $profile?->id,
                'completion_score'     => $completionScore,
                'completion_checklist' => $checklist,
                ...$analyticsData,
            ],
        ]);
    }
}
