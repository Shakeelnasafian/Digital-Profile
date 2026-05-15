# Digital Profile

Create, manage, and share your professional digital profile with a scannable digital card, analytics, social proof tools, and a polished public page.

## Live Demo

🌐 **[digital-profile-ki8m.onrender.com](https://digital-profile-ki8m.onrender.com)**

> Hosted on Render Free — first request after idle may take ~30 seconds to wake the container.

Sign in with either demo account:

| Email | Password |
| --- | --- |
| `maya.chen@demo.digitalprofile.test` | `DemoPass#2026` |
| `omar.rahman@demo.digitalprofile.test` | `DemoPass#2026` |

Both accounts are pre-populated with portfolio data (projects, experience, services, testimonials, leads, analytics) so you can explore the full feature surface without setting anything up.

> If the demo accounts haven't been seeded yet, registration is open — sign up at [/register](https://digital-profile-ki8m.onrender.com/register) to create your own profile and try the platform end-to-end.

## Overview

Digital Profile lets you build a public profile that serves as your single professional link. It combines a digital business card with a full portfolio — projects, experience, education, certifications, services, and testimonials — and gives you analytics, lead capture, PDF resume export, and sharing tools.

## Features

### Identity & Card

- Authentication and user settings (profile, password, appearance)
- Digital card builder with public/private toggle
- Custom vanity slug with live availability check
- Three public profile templates: `default` (clean minimal), `bold` (dark hero), `glass` (frosted gradient)
- Availability badge: Available / Open to Opportunities / Not Available
- Scheduling link integration (Calendly, Cal.com, etc.)
- **AI bio generator** — Groq-powered (Llama 3.3 70B) one-click bio drafting from your role and a short free-text context (requires `GROQ_API_KEY`)

### Contact & Social

- Contact badges: email, phone, WhatsApp, website
- Social links: LinkedIn, GitHub, X/Twitter, Instagram, YouTube, TikTok, Dribbble, Behance, Medium
- vCard (.vcf) download — imports into iOS Contacts, Android, Outlook
- Email signature generator (copy-paste HTML for Gmail, Outlook)

### Portfolio Sections

- Skills (comma-separated, displayed as tags)
- Projects (with status, dates, URL)
- Work Experience (timeline)
- Education (timeline)
- Certifications (card grid with verify link)
- Services (card grid with pricing and CTA buttons)

### Teams

- Group multiple profiles under a shared team workspace
- Owner + member roles, managed from the team's settings page
- Auto-generated unique slug, team logo, description, and website
- Public team page at `/t/{slug}` showcasing every member's profile

### Social Proof & Lead Generation

- **Lead Capture Form** — visitors submit contact info directly on your public profile; you view them in `/leads` with CSV export
- **Testimonials** — visitors submit starred reviews via shareable link; you approve before display; shown on public profile
- **PDF Resume Export** — one-click download of a clean A4 PDF from your profile data (powered by dompdf)

### Analytics

- Total profile views counter
- Views over last 30 days (area chart)
- Device breakdown — mobile / tablet / desktop (donut chart)
- Top referrers (bar list)
- QR scan tracking via `?ref=qr`

### Profile Completion

- Completion score (weighted %)
- Checklist of incomplete sections with direct edit links

### Sharing

- Copy profile link
- WhatsApp share with pre-filled message
- Download vCard (Save Contact)
- Download QR code as PNG (canvas-based in-browser conversion)
- QR code auto-generated on profile creation; regenerated on slug change
- **Custom domain mapping** — point your own domain at your profile via CNAME with token-based verification; verified domains automatically serve the public page

## User Flow

1. Sign up and create your digital card.
2. Fill in core details, choose a template, upload a photo, and set visibility.
3. Add skills, projects, experience, education, certifications, and services.
4. Customize your slug and social links; set your availability status.
5. Share your profile via QR code, link, WhatsApp, vCard, or PDF resume.
6. Collect leads and testimonials from visitors.
7. Track views, device splits, and referrers in the dashboard.
8. Improve your profile completion score using the checklist.

## Tech Stack

- **Laravel 12** — backend, routing, Eloquent ORM
- **React 19 + Inertia.js** — SPA frontend (no full-page reloads)
- **TypeScript** — typed frontend
- **Vite** — bundler
- **Tailwind CSS 4** — styling
- **Radix UI** — accessible component primitives
- **SQLite** (local dev) or **PostgreSQL** (production)
- **SimpleSoftwareIO/QrCode** — QR code generation
- **barryvdh/laravel-dompdf** — PDF resume export
- **ApexCharts** — dashboard analytics charts
- **FontAwesome brands** — social platform icons
- **Lucide React** — UI icons
- **Groq Cloud API** (Llama 3.3 70B) — AI bio generation
- **Docker · nginx · PHP-FPM · Supervisord** — containerized production image
- **Render** — production hosting target
- **Neon** — managed Postgres provider used in production

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js and npm
- SQLite (default) or MySQL

### Installation

1. Clone the repository:

    ```sh
    git clone https://github.com/Shakeelnasafian/Digital-Profile.git
    cd Digital-Profile
    ```

2. Install PHP dependencies:

    ```sh
    composer install
    ```

3. Install JS dependencies:

    ```sh
    npm install
    ```

4. Copy `.env.example` to `.env` and set your environment variables.

5. If using SQLite, ensure the database file exists at `database/database.sqlite` and set:

    ```env
    DB_CONNECTION=sqlite
    DB_DATABASE=database/database.sqlite
    ```

6. Generate app key:

    ```sh
    php artisan key:generate
    ```

7. Run migrations:

    ```sh
    php artisan migrate
    ```

8. Create storage symlink (for profile images and QR codes):

    ```sh
    php artisan storage:link
    ```

9. Build frontend assets:

    ```sh
    npm run build
    ```

10. Start the development servers:

    ```sh
    php artisan serve
    npm run dev
    ```

    Or run everything together:

    ```sh
    composer run dev
    ```

## Deployment

The repository ships with a production-ready Docker image and a Render-friendly entrypoint.

### Live deployment

- **Hosting:** [Render](https://render.com) — Web Service, Docker runtime
- **Database:** [Neon](https://neon.tech) — managed PostgreSQL
- **Live URL:** <https://digital-profile-ki8m.onrender.com>

### Image architecture

The `Dockerfile` is a three-stage build:

1. **`composer-deps`** — installs PHP dependencies with `--no-dev` and an optimized autoloader.
2. **`node-deps`** — runs `npm ci` and builds Vite assets (`public/build`).
3. **`production`** — final `php:8.2-fpm-alpine` image with nginx serving `${PORT}`, PHP-FPM on port 9000, and Supervisord managing both processes. Build-time PHP extensions (`pdo_mysql`, `pdo_pgsql`, `gd`, `zip`, `mbstring`, `bcmath`, `pcntl`, `redis`) are compiled against a virtual `.build-deps` group that is purged after install.

On first boot, [`docker/entrypoint.sh`](docker/entrypoint.sh) templates the nginx config with `${PORT}`, runs `php artisan storage:link --force`, caches config/routes/views when `APP_ENV=production`, and runs `php artisan migrate --force`. Supervisord then takes over as PID 1.

### Required environment variables

Set these in your hosting provider's environment panel before the first deploy:

| Key | Example / Required value | Purpose |
| --- | --- | --- |
| `APP_KEY` | `base64:...` (from `php artisan key:generate --show`) | Laravel encryption key |
| `APP_ENV` | `production` | Triggers config/route/view caching at boot |
| `APP_DEBUG` | `false` | — |
| `APP_URL` | `https://your-app.onrender.com` | Must be `https://` so canonical URLs and emails are correct |
| `LOG_CHANNEL` | `stderr` | Streams logs to stdout/stderr for the platform to capture |
| `DB_CONNECTION` | `pgsql` | — |
| `DB_URL` | `postgresql://user:pass@host/db?sslmode=require` | Full Neon connection string (preferred), or use the split `DB_HOST` / `DB_PORT` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` form instead |
| `GROQ_API_KEY` | `gsk_...` (optional) | Enables the AI bio generator; omit to disable that feature |

### Trusted proxies

Laravel is configured to trust Render's reverse proxy (`X-Forwarded-Proto`, `X-Forwarded-Host`, etc.) in [`bootstrap/app.php`](bootstrap/app.php). This makes asset URLs render with `https://` even though traffic reaches the container over plain HTTP inside Render's network.

### Deploy

With env vars set, push to `main` and Render's auto-deploy rebuilds the image, runs the entrypoint (migrations included), and serves on the assigned `$PORT`. A manual deploy is available from the Render dashboard under **Manual Deploy → Deploy latest commit**.

### Caveats

- Render Free instances **sleep on idle** — the first request after sleep can take ~30 seconds to wake the container.
- The container filesystem is **ephemeral**; user-uploaded files in `storage/app/public/` are lost on restart. Attach a Render Disk (paid) or move uploads to S3-compatible storage for persistence.

## Useful Scripts

- `npm run dev` — Vite dev server
- `npm run build` — production build
- `npm run lint` — ESLint
- `npm run types` — TypeScript checks
- `npm run format` — Prettier
- `composer run format` — Pint (PHP formatting)
- `composer run lint` — Pint (lint mode)
- `composer test` — run tests

## Contributing

Pull requests are welcome. For major changes, open an issue first to discuss your proposal.

## License

[MIT](LICENSE)
