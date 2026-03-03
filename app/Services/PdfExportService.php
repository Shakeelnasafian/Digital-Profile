<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PdfExportService
{
    public function export(Profile $profile, int $userId): Response
    {
        $experiences    = Experience::where('user_id', $userId)->orderBy('start_date', 'desc')->get();
        $educations     = Education::where('user_id', $userId)->orderByDesc('start_year')->get();
        $certifications = Certification::where('user_id', $userId)->orderByDesc('issue_date')->get();
        $projects       = Project::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        $pdf      = Pdf::loadView('pdf.resume', compact('profile', 'experiences', 'educations', 'certifications', 'projects'));
        $filename = Str::slug($profile->display_name ?? 'resume') . '_resume.pdf';

        return $pdf->download($filename);
    }
}
