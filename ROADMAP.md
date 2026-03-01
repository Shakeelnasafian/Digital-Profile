# Digital Profile — Feature Roadmap

> Agent instructions: Work through tasks in phase order. Each task has an ID, status, acceptance criteria, and file hints.
> Update status: `[ ]` pending → `[~]` in progress → `[x]` done
> Never skip to a later phase task if earlier phase tasks are incomplete.

---

## Phase 4 — Growth Features

### P4-T1 — Scheduling Link Integration

- **Status:** `[ ]`
- **Priority:** MEDIUM
- **Task:** Add a booking/scheduling link field (Calendly, Cal.com, etc.) to profile.
- **Files to edit:**
  - `database/migrations/` — add `scheduling_url` column to profiles
  - `app/Models/Profile.php` — add to fillable
  - `app/Http/Requests/ProfileRequest.php` — nullable|url rule
  - `resources/js/pages/profile/edit.tsx` — add input for scheduling URL
  - `resources/js/pages/profile/public.tsx` — show "Book a Meeting" button if set
- **Acceptance criteria:**
  - "Book a Meeting" button appears on public profile when URL is set
  - Opens scheduling URL in new tab

---

### P4-T2 — Team Profiles

- **Status:** `[ ]`
- **Priority:** LOW (B2B feature, implement last)
- **Task:** Company/team workspace where multiple member profiles are grouped.
- **Fields:** team_name, team_slug, logo, description, website
- **Files to create:**
  - `database/migrations/` — `teams` table, `team_members` pivot table (team_id, user_id, role)
  - `app/Models/Team.php`, `TeamMember.php`
  - `app/Http/Controllers/TeamController.php`
  - `resources/js/pages/team/` — create, show, manage pages
- **Files to edit:**
  - `routes/web.php` — `/team/{slug}` public page, `/teams` auth management
- **Acceptance criteria:**
  - Team owner can invite members by email
  - Public team page shows all member profile cards
  - Members can display team affiliation on their own profile

---

## Completed Tasks

### P1-T1 — Fix Projects Table Migration `[x]`

Ran fix migration to rebuild the broken projects table schema. `projects` table now has: id, user_id, name, description, project_url, image, start_date, end_date, status, timestamps. Create/edit/delete projects works without SQL errors.

---

### P1-T2 — Profile Templates (3 Designs) `[x]`

Added 3 distinct public profile card layouts controlled by `template` column on profiles table. User picks template in profile edit form with visual preview thumbnails.

- `default` — clean minimal layout, blue gradient banner
- `bold` — full-width dark hero with large name, violet accent
- `glass` — frosted glass card on gradient background

Template switcher logic lives in `resources/js/pages/profile/public.tsx` via a `themes` config map.

---

### P1-T3 — Custom Vanity Slug `[x]`

Users can set a custom profile slug on the edit page. Live availability check (debounced, green/red indicator) via `GET /api/check-slug/{slug}`. Slug change regenerates QR code. Old slug returns 404.

---

### P1-T4 — Social Links Expansion `[x]`

Added 7 new social platforms: Twitter/X, Instagram, YouTube, TikTok, Dribbble, Behance, Medium. Migration added nullable string columns. FontAwesome brand icons used. Icons only shown when URL is set.

---

### P1-T5 — Availability Badge `[x]`

Added `availability_status` column to profiles. Options: `available` (green dot), `open_to_opportunities` (yellow), `not_available` (gray). Badge renders near name on public profile. Hidden when null.

---

### P2-T1 — Analytics Dashboard (Views & Devices) `[x]`

Tracks profile view events (`profile_view_events` table): profile_id, ip, country, device_type, referrer, is_qr_scan, viewed_at. Dashboard shows area chart (views last 30 days), donut chart (device breakdown), top referrers list. QR scans detected via `?ref=qr`.

---

### P2-T2 — vCard (.vcf) Download `[x]`

`GET /p/{slug}/vcard` returns a `.vcf` file with name, title, email, phone, website, LinkedIn, GitHub, location, bio, and profile URL. "Save Contact" button on public profile.

---

### P2-T3 — Email Signature Generator `[x]`

Collapsible panel on profile show page. Generates table-based HTML email signature from profile data (avatar, name, title, contact, profile link). "Copy HTML" button copies raw HTML to clipboard. No backend needed — generated entirely in JS.

---

### P2-T4 — Education Section `[x]`

Full CRUD for education entries (institution, degree, field_of_study, start_year, end_year, is_current, description). Timeline display on public profile between experience and projects. Nav item in sidebar.

---

### P2-T5 — Certifications Section `[x]`

Full CRUD for certifications (title, issuer, issue_date, expiry_date, credential_url, credential_id). Card grid on public profile. "Verify" link opens credential_url in new tab. Nav item in sidebar.

---

### P2-T6 — Profile Completion Score `[x]`

Weighted completion score computed in `ProfileCompletionService`. Progress ring widget on dashboard. Checklist shows incomplete sections with direct edit links.

---

### P3-T1 — Lead Capture (Visitor Contact Form) `[x]`

`leads` table (profile_id, visitor_name, visitor_email, visitor_phone, message, created_at). Visitors submit "Get in touch" collapsible form on public profile (no auth, rate-limited 5/min). Owner views leads at `/leads` with CSV export button.

---

### P3-T2 — Testimonials Section `[x]`

`testimonials` table (profile_id, reviewer_name, reviewer_title, reviewer_company, content, rating, is_approved). Public submission form at `/p/{slug}/testimonial` (star-picker, no AppLayout). Owner approves/rejects in `/testimonials` dashboard. Approved testimonials shown as star cards on public profile.

---

### P3-T3 — Services Section `[x]`

`services` table (user_id, title, description, starting_price, currency, cta_label, cta_url, sort_order). Full CRUD at `/services` (card grid + modal). Services card grid on public profile after Skills section. CTA button links to booking/contact URL.

---

### P3-T4 — WhatsApp / Social Share Buttons `[x]`

Share bar on public profile: Copy Link, WhatsApp (pre-filled message), Save Contact (vCard), Download QR (canvas SVG→PNG in-browser). All implemented without backend.

---

### P3-T5 — PDF Resume Export `[x]`

`barryvdh/laravel-dompdf` v3.1.1 installed. `GET /profile/{profile}/export-pdf` (auth) generates A4 PDF with DejaVu Sans: header (name, title, contact), bio, skills, experience, education, certifications, projects. "Export PDF" button in profile show action bar.

---

## Notes for Agents

- Always run `php artisan migrate` after creating new migration files
- Always run `npm run build` or check that `npm run dev` is running after frontend changes
- Public profile page is the most important page — all new sections must appear there
- Follow existing patterns: controllers use Form Requests, actions for complex logic, Inertia props for data passing
- Use existing Radix UI components from `resources/js/components/ui/` — do not install new UI libraries
- Use FontAwesome brands (`@fortawesome/free-brands-svg-icons`) for social platform icons
- Use Lucide React for other icons
- SQLite DB file should be `database/database.sqlite` (ignored by git). If you use a different path, set `DB_DATABASE` in `.env`
- After slug change, QR code must be regenerated via `GeneratesQrCode` trait
- `JsonResource::withoutWrapping()` is set in `AppServiceProvider::boot()` — required for Inertia v2 compatibility
- `Education` model has `protected $table = 'educations'` to override Laravel's uncountable-noun pluralizer
