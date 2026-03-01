# Digital Profile — Feature Roadmap

> Agent instructions: Work through tasks in phase order. Each task has an ID, status, acceptance criteria, and file hints.
> Update status: `[ ]` pending → `[~]` in progress → `[x]` done
> Never skip to a later phase task if earlier phase tasks are incomplete.

---

## Phase 1 — Stabilize & Core Differentiation

### P1-T1 — Fix Projects Table Migration
- **Status:** `[ ]`
- **Priority:** CRITICAL (blocks all project features)
- **Task:** Run the fix migration to rebuild the broken projects table schema.
- **Files:**
  - `database/migrations/2026_02_27_184409_fix_projects_table_schema.php` (already created)
- **Commands:**
  ```bash
  php artisan migrate
  ```
- **Acceptance criteria:**
  - `projects` table has columns: id, user_id, name, description, project_url, image, start_date, end_date, status, timestamps
  - Can create/edit/delete a project without SQL errors

---

### P1-T2 — Profile Templates (3 Designs)
- **Status:** `[ ]`
- **Priority:** HIGH — biggest visual differentiator
- **Task:** Add 3 distinct public profile card layouts. User picks template in profile edit form. Template controls layout, color scheme, and typography on `/p/{slug}`.
- **Templates to build:**
  - `default` — current layout (keep as-is, clean minimal)
  - `bold` — full-width hero with large name, accent color bar, dark header
  - `glass` — frosted glass card effect, gradient background, blurred backdrop
- **Files to create/edit:**
  - `resources/js/pages/profile/public.tsx` — add template switcher logic
  - `resources/js/components/profile-templates/DefaultTemplate.tsx` (extract current)
  - `resources/js/components/profile-templates/BoldTemplate.tsx` (new)
  - `resources/js/components/profile-templates/GlassTemplate.tsx` (new)
  - `resources/js/pages/profile/edit.tsx` — add template picker with live preview
- **DB change:** None — `template` column already exists on profiles table
- **Acceptance criteria:**
  - 3 templates render correctly on public profile page
  - User can select template in edit form with visual preview thumbnails
  - Selected template persists after save

---

### P1-T3 — Custom Vanity Slug
- **Status:** `[ ]`
- **Priority:** HIGH — core UX improvement
- **Task:** Let user choose their own profile slug (e.g. `/p/johndoe`) instead of auto-generated. Show live availability check.
- **Files to edit:**
  - `app/Models/Profile.php` — modify slug generation to allow manual override
  - `app/Http/Requests/ProfileRequest.php` — add slug validation rule (unique, alphanumeric-dashes, 3-30 chars)
  - `app/Actions/UpdateProfileAction.php` — regenerate QR code if slug changes
  - `resources/js/pages/profile/edit.tsx` — add slug input with availability indicator
  - `routes/web.php` — add `GET /api/check-slug/{slug}` for availability check
- **Acceptance criteria:**
  - User can set custom slug on profile edit page
  - Real-time availability check (debounced, green/red indicator)
  - Slug change regenerates QR code pointing to new URL
  - Old slug no longer works after change (404)

---

### P1-T4 — Social Links Expansion
- **Status:** `[ ]`
- **Priority:** MEDIUM
- **Task:** Expand social links beyond LinkedIn + GitHub + website.
- **Platforms to add:** Twitter/X, Instagram, YouTube, TikTok, Dribbble, Behance, Medium
- **Files to edit:**
  - `database/migrations/` — new migration to add columns: twitter, instagram, youtube, tiktok, dribbble, behance, medium (all nullable strings)
  - `app/Models/Profile.php` — add to fillable
  - `app/Http/Requests/ProfileRequest.php` — add nullable|url rules
  - `resources/js/pages/profile/edit.tsx` — add inputs with platform icons
  - `resources/js/pages/profile/public.tsx` — render new social icons (use FontAwesome brands)
  - `resources/js/components/profile-templates/*.tsx` — show icons in all templates
- **Acceptance criteria:**
  - All 7 new platforms can be saved and displayed
  - Icons use FontAwesome brand icons
  - Only show icon if URL is set (no empty icons)

---

### P1-T5 — Availability Badge
- **Status:** `[ ]`
- **Priority:** MEDIUM
- **Task:** Add availability status shown on public profile.
- **Options:** `available` (green), `open_to_opportunities` (yellow), `not_available` (gray)
- **Files to edit:**
  - `database/migrations/` — new migration: add `availability_status` string nullable to profiles
  - `app/Models/Profile.php` — add to fillable
  - `app/Http/Requests/ProfileRequest.php` — add `in:available,open_to_opportunities,not_available,null` rule
  - `resources/js/pages/profile/edit.tsx` — add select/radio for availability
  - `resources/js/pages/profile/public.tsx` — render badge near name/title
- **Acceptance criteria:**
  - Badge appears next to name on public profile when set
  - Color-coded: green/yellow/gray
  - Not shown if null/unset

---

## Phase 2 — Analytics & Professional Tools

### P2-T1 — Analytics Dashboard (Views & Devices)
- **Status:** `[ ]`
- **Priority:** HIGH
- **Task:** Track detailed profile view events and display in dashboard.
- **Data to capture per view:** profile_id, ip_address, country (from IP), device_type (mobile/desktop/tablet), referrer, is_qr_scan, viewed_at
- **Files to create/edit:**
  - `database/migrations/` — create `profile_view_events` table with above columns
  - `app/Models/ProfileViewEvent.php` — new model
  - `app/Http/Controllers/ProfileController.php` — update `publicShow()` to log event + detect device/referrer
  - `resources/js/pages/dashboard.tsx` — add charts: views over 30 days (line), device breakdown (donut), top referrers (bar)
  - ApexCharts already installed — use it
- **Acceptance criteria:**
  - Every public profile view logs an event
  - Dashboard shows views over time (last 30 days), device split, referrer breakdown
  - QR scan detected via `?ref=qr` query param appended to QR code URL

---

### P2-T2 — vCard (.vcf) Download
- **Status:** `[ ]`
- **Priority:** HIGH — #1 requested digital card feature
- **Task:** Generate downloadable .vcf contact file from profile data.
- **Files to create/edit:**
  - `routes/web.php` — add `GET /p/{slug}/vcard`
  - `app/Http/Controllers/ProfileController.php` — add `downloadVCard()` method, return .vcf response
  - `resources/js/pages/profile/public.tsx` — add "Save Contact" / "Download vCard" button
- **vCard fields to include:** name, job_title, email, phone, whatsapp, website, linkedin, github, photo URL
- **Acceptance criteria:**
  - Clicking button downloads `{display_name}.vcf`
  - File imports correctly into iOS Contacts, Android Contacts, Outlook

---

### P2-T3 — Email Signature Generator
- **Status:** `[ ]`
- **Priority:** MEDIUM
- **Task:** Generate copy-paste HTML email signature from profile.
- **Files to create/edit:**
  - `resources/js/pages/profile/show.tsx` — add "Email Signature" tab/section
  - `resources/js/components/EmailSignatureGenerator.tsx` — renders HTML signature preview, copy button
- **No backend needed** — generate HTML entirely in JS from profile data
- **Acceptance criteria:**
  - Preview shows formatted signature with name, title, phone, email, social links, profile QR code
  - "Copy HTML" button copies raw HTML to clipboard
  - Works when pasted into Gmail signature settings

---

### P2-T4 — Education Section
- **Status:** `[ ]`
- **Priority:** MEDIUM
- **Task:** Add education history (currently missing entirely).
- **Fields:** institution, degree, field_of_study, start_year, end_year, is_current, description
- **Files to create:**
  - `database/migrations/` — create `educations` table
  - `app/Models/Education.php`
  - `app/Http/Controllers/EducationController.php` (index, store, update, destroy)
  - `resources/js/pages/education/index.tsx` — CRUD page (mirror experience/index.tsx pattern)
- **Files to edit:**
  - `routes/web.php` — add education routes
  - `resources/js/pages/profile/public.tsx` — render education section
  - `resources/js/layouts/app-layout.tsx` — add Education nav item
- **Acceptance criteria:**
  - Full CRUD for education entries
  - Displayed on public profile between experience and projects sections

---

### P2-T5 — Certifications Section
- **Status:** `[ ]`
- **Priority:** MEDIUM
- **Task:** Add certifications and awards.
- **Fields:** title, issuer, issue_date, expiry_date (nullable), credential_url (nullable), credential_id (nullable)
- **Files to create:**
  - `database/migrations/` — create `certifications` table
  - `app/Models/Certification.php`
  - `app/Http/Controllers/CertificationController.php`
  - `resources/js/pages/certification/index.tsx`
- **Files to edit:**
  - `routes/web.php`
  - `resources/js/pages/profile/public.tsx` — render certifications grid
  - `resources/js/layouts/app-layout.tsx` — nav item
- **Acceptance criteria:**
  - Full CRUD for certifications
  - "Verify" link opens credential_url in new tab
  - Shown on public profile as card grid

---

### P2-T6 — Profile Completion Score
- **Status:** `[ ]`
- **Priority:** LOW-MEDIUM
- **Task:** Show profile completeness % with field-level hints.
- **Scoring:** Define weights per field (photo: 20%, bio: 15%, skills: 10%, projects: 15%, experience: 15%, social links: 10%, etc.)
- **Files to edit:**
  - `app/Http/Controllers/ProfileController.php` — compute score and pass to `show()` / `edit()`
  - `resources/js/pages/profile/show.tsx` — show progress bar + "Complete your profile" checklist
  - `resources/js/pages/dashboard.tsx` — show completion score widget
- **Acceptance criteria:**
  - Percentage calculated correctly from filled fields
  - Checklist shows which sections are incomplete with direct edit links

---

## Phase 3 — Social Proof & Lead Generation

### P3-T1 — Lead Capture (Visitor Contact Form)
- **Status:** `[ ]`
- **Priority:** HIGH for B2B use case
- **Task:** Visitors can leave their contact info on a profile. Profile owner gets notified.
- **Fields captured:** visitor_name, visitor_email, visitor_phone (optional), message (optional)
- **Files to create:**
  - `database/migrations/` — create `leads` table (profile_id, visitor_name, visitor_email, visitor_phone, message, created_at)
  - `app/Models/Lead.php`
  - `app/Http/Controllers/LeadController.php` — `store()` (public, no auth), `index()` (auth, own leads only)
  - `resources/js/pages/leads/index.tsx` — view leads list + CSV export
  - `resources/js/components/LeadCaptureForm.tsx` — form shown on public profile
- **Files to edit:**
  - `routes/web.php` — `POST /p/{slug}/lead` (public), `GET /leads` (auth)
  - `resources/js/pages/profile/public.tsx` — add lead capture section / button
- **Acceptance criteria:**
  - Visitors submit form without logging in
  - Owner sees list of leads in dashboard
  - CSV export of leads works
  - Basic spam protection (rate limiting)

---

### P3-T2 — Testimonials Section
- **Status:** `[ ]`
- **Priority:** HIGH for freelancers / consultants
- **Task:** Profile owners request testimonials via shareable link. Approve before display.
- **Fields:** reviewer_name, reviewer_title, reviewer_company, content, rating (1-5), is_approved
- **Files to create:**
  - `database/migrations/` — create `testimonials` table
  - `app/Models/Testimonial.php`
  - `app/Http/Controllers/TestimonialController.php` — public submit, owner approve/reject/delete
  - `resources/js/pages/testimonials/index.tsx` — manage testimonials (approve/reject)
  - `resources/js/pages/testimonials/submit.tsx` — public submission form (no auth)
- **Files to edit:**
  - `routes/web.php` — `GET /p/{slug}/testimonial` (public form), `POST /p/{slug}/testimonial`, `GET /testimonials` (auth)
  - `resources/js/pages/profile/public.tsx` — render approved testimonials section
- **Acceptance criteria:**
  - Shareable link to testimonial form
  - Owner approves/rejects in dashboard
  - Approved testimonials shown as cards on public profile
  - Star rating displayed

---

### P3-T3 — Services Section
- **Status:** `[ ]`
- **Priority:** MEDIUM — for freelancers
- **Task:** List services offered with optional pricing and CTA.
- **Fields:** title, description, starting_price (nullable), currency, cta_label, cta_url
- **Files to create:**
  - `database/migrations/` — create `services` table (user_id, title, description, starting_price, currency, cta_label, cta_url, sort_order)
  - `app/Models/Service.php`
  - `app/Http/Controllers/ServiceController.php`
  - `resources/js/pages/services/index.tsx`
- **Files to edit:**
  - `routes/web.php`
  - `resources/js/pages/profile/public.tsx` — render services grid
  - `resources/js/layouts/app-layout.tsx`
- **Acceptance criteria:**
  - Full CRUD for services
  - Displayed as card grid on public profile
  - CTA button links to booking/contact URL

---

### P3-T4 — WhatsApp / Social Share Buttons
- **Status:** `[ ]`
- **Priority:** MEDIUM
- **Task:** Add sharing shortcuts on public profile and in dashboard.
- **Shares to add:** WhatsApp, Copy Link, Share via Email, Download QR as PNG
- **Files to edit:**
  - `resources/js/pages/profile/public.tsx` — add share bar
  - `resources/js/pages/profile/show.tsx` — add share section in dashboard profile view
  - `resources/js/components/ShareBar.tsx` — reusable share component
- **No backend needed** — use Web Share API + clipboard API + WhatsApp deep link
- **Acceptance criteria:**
  - WhatsApp share opens with pre-filled message + profile URL
  - Copy link copies to clipboard with toast confirmation
  - Download QR downloads PNG version of QR (convert SVG → PNG in browser)

---

### P3-T5 — PDF Resume Export
- **Status:** `[ ]`
- **Priority:** HIGH — differentiates from all card-only apps
- **Task:** Generate downloadable PDF resume from profile data.
- **Approach:** Use `barryvdh/laravel-dompdf` or browser print CSS
- **Files to create/edit:**
  - `routes/web.php` — `GET /profile/{profile}/export-pdf`
  - `app/Http/Controllers/ProfileController.php` — `exportPdf()` method
  - `resources/views/pdf/resume.blade.php` — PDF template (Blade, not React)
  - `resources/js/pages/profile/show.tsx` — add "Export PDF" button
- **Composer package:** `composer require barryvdh/laravel-dompdf`
- **Acceptance criteria:**
  - PDF includes: profile info, skills, experience, education, projects, certifications
  - Clean typography, avatar if uploaded
  - Formatted dates, status badges

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

_(move tasks here when done)_

---

## Notes for Agents

- Always run `php artisan migrate` after creating new migration files
- Always run `npm run build` or check that `npm run dev` is running after frontend changes
- Public profile page is the most important page — all new sections must appear there
- Follow existing patterns: controllers use Form Requests, actions for complex logic, Inertia props for data passing
- Use existing Radix UI components from `resources/js/components/ui/` — do not install new UI libraries
- Use FontAwesome brands (`@fortawesome/free-brands-svg-icons`) for social platform icons
- Use Lucide React for other icons
- SQLite DB file should be `database/database.sqlite` (ignored by git). If you use a different path, set `DB_DATABASE` in `.env`.
- After slug change, QR code must be regenerated via `GeneratesQrCode` trait
