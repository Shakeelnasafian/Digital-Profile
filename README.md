# Digital Profile

Create, manage, and share your professional digital profile with a scannable digital card, analytics, social proof tools, and a polished public page.

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
- **SQLite** (default) or MySQL
- **SimpleSoftwareIO/QrCode** — QR code generation
- **barryvdh/laravel-dompdf** — PDF resume export
- **ApexCharts** — dashboard analytics charts
- **FontAwesome brands** — social platform icons
- **Lucide React** — UI icons

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
