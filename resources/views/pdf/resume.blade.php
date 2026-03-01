<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>{{ $profile->display_name }} — Resume</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #1f2937;
        line-height: 1.5;
        background: #fff;
    }
    .page { padding: 36px 40px; }

    /* Header */
    .header { border-bottom: 3px solid #4f46e5; padding-bottom: 16px; margin-bottom: 20px; }
    .header-name { font-size: 24px; font-weight: bold; color: #111827; }
    .header-title { font-size: 13px; color: #4f46e5; margin-top: 2px; font-weight: 600; }
    .header-contact { margin-top: 8px; font-size: 10px; color: #6b7280; }
    .header-contact span { margin-right: 14px; }

    /* Bio */
    .bio { margin-bottom: 20px; font-size: 10.5px; color: #4b5563; line-height: 1.6; }

    /* Section */
    .section { margin-bottom: 20px; }
    .section-title {
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #4f46e5;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 4px;
        margin-bottom: 10px;
    }

    /* Skills */
    .skills-box {
        background: #f3f4f6;
        border-radius: 4px;
        padding: 8px 10px;
        font-size: 10px;
        color: #374151;
    }

    /* Experience / Education timeline */
    .entry { margin-bottom: 12px; }
    .entry-header { display: flex; justify-content: space-between; }
    .entry-title { font-size: 11px; font-weight: bold; color: #111827; }
    .entry-sub { font-size: 10px; color: #4f46e5; font-weight: 600; }
    .entry-meta { font-size: 9px; color: #9ca3af; text-align: right; }
    .entry-body { font-size: 10px; color: #4b5563; margin-top: 4px; line-height: 1.5; }

    /* Certifications */
    .cert-grid { /* simple block */ }
    .cert-item { margin-bottom: 8px; }
    .cert-name { font-size: 10.5px; font-weight: bold; color: #111827; }
    .cert-issuer { font-size: 9.5px; color: #6b7280; }
    .cert-date { font-size: 9px; color: #9ca3af; }

    /* Projects */
    .proj-item { margin-bottom: 10px; border-left: 3px solid #e5e7eb; padding-left: 8px; }
    .proj-name { font-size: 11px; font-weight: bold; color: #111827; }
    .proj-status {
        display: inline-block;
        font-size: 8.5px;
        padding: 1px 6px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1d4ed8;
        margin-left: 4px;
    }
    .proj-desc { font-size: 10px; color: #6b7280; margin-top: 3px; }
    .proj-url { font-size: 9.5px; color: #4f46e5; margin-top: 3px; }

    /* Footer */
    .footer { margin-top: 28px; border-top: 1px solid #e5e7eb; padding-top: 8px; text-align: center; font-size: 8.5px; color: #9ca3af; }
</style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-name">{{ $profile->display_name }}</div>
        @if($profile->job_title)
            <div class="header-title">{{ $profile->job_title }}</div>
        @endif
        <div class="header-contact">
            @if($profile->email)   <span>✉ {{ $profile->email }}</span> @endif
            @if($profile->phone)   <span>✆ {{ $profile->phone }}</span> @endif
            @if($profile->location)<span>⊙ {{ $profile->location }}</span> @endif
            @if($profile->website) <span>⊕ {{ $profile->website }}</span> @endif
            @if($profile->linkedin)<span>in {{ $profile->linkedin }}</span> @endif
        </div>
    </div>

    {{-- Bio --}}
    @if($profile->short_bio)
        <div class="bio">{{ $profile->short_bio }}</div>
    @endif

    {{-- Skills --}}
    @if($profile->skills)
        <div class="section">
            <div class="section-title">Skills</div>
            <div class="skills-box">{{ $profile->skills }}</div>
        </div>
    @endif

    {{-- Experience --}}
    @if($experiences->count())
        <div class="section">
            <div class="section-title">Work Experience</div>
            @foreach($experiences as $exp)
                <div class="entry">
                    <div class="entry-header">
                        <div>
                            <div class="entry-title">{{ $exp->position }}</div>
                            <div class="entry-sub">{{ $exp->company }}{{ $exp->location ? ' · ' . $exp->location : '' }}</div>
                        </div>
                        <div class="entry-meta">
                            {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} —
                            {{ $exp->is_current ? 'Present' : ($exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : '') }}
                        </div>
                    </div>
                    @if($exp->description)
                        <div class="entry-body">{{ $exp->description }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Education --}}
    @if($educations->count())
        <div class="section">
            <div class="section-title">Education</div>
            @foreach($educations as $edu)
                <div class="entry">
                    <div class="entry-header">
                        <div>
                            <div class="entry-title">{{ $edu->degree }}{{ $edu->field_of_study ? ' — ' . $edu->field_of_study : '' }}</div>
                            <div class="entry-sub">{{ $edu->institution }}</div>
                        </div>
                        <div class="entry-meta">
                            {{ $edu->start_year }} — {{ $edu->is_current ? 'Present' : ($edu->end_year ?? '') }}
                        </div>
                    </div>
                    @if($edu->description)
                        <div class="entry-body">{{ $edu->description }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Certifications --}}
    @if($certifications->count())
        <div class="section">
            <div class="section-title">Certifications</div>
            <div class="cert-grid">
                @foreach($certifications as $cert)
                    <div class="cert-item">
                        <div class="cert-name">{{ $cert->title }}</div>
                        <div class="cert-issuer">{{ $cert->issuer }}</div>
                        <div class="cert-date">
                            Issued {{ \Carbon\Carbon::parse($cert->issue_date)->format('M Y') }}
                            @if($cert->expiry_date) · Expires {{ \Carbon\Carbon::parse($cert->expiry_date)->format('M Y') }} @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Projects --}}
    @if($projects->count())
        <div class="section">
            <div class="section-title">Projects</div>
            @foreach($projects as $proj)
                <div class="proj-item">
                    <div class="proj-name">
                        {{ $proj->name }}
                        <span class="proj-status">{{ ucfirst($proj->status) }}</span>
                    </div>
                    @if($proj->description)
                        <div class="proj-desc">{{ $proj->description }}</div>
                    @endif
                    @if($proj->project_url)
                        <div class="proj-url">{{ $proj->project_url }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="footer">Generated by Digital Profile · {{ now()->format('d M Y') }}</div>

</div>
</body>
</html>
