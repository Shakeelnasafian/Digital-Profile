<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ServiceController;

// Welcome page
Route::get('/', fn() => Inertia::render('welcome'))->name('home');

// Public profile — no auth required
Route::get('/p/{slug}', [ProfileController::class, 'publicShow'])->name('profile.public');
Route::get('/p/{slug}/vcard', [ProfileController::class, 'downloadVCard'])->name('profile.vcard');
Route::post('/p/{slug}/lead', [LeadController::class, 'store'])->middleware('throttle:5,1')->name('lead.store');
Route::get('/p/{slug}/testimonial', [TestimonialController::class, 'create'])->name('testimonial.create');
Route::post('/p/{slug}/testimonial', [TestimonialController::class, 'store'])->name('testimonial.store');

// Slug availability check — auth only, no email verification needed
Route::middleware('auth')->group(function () {
    Route::get('/api/check-slug/{slug}', [ProfileController::class, 'checkSlug'])->name('profile.check-slug');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');

    // Profile management
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('profile/{slug}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/{profile}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile/{profile}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile/{profile}', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::patch('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Experience
    Route::get('experience', [ExperienceController::class, 'index'])->name('experience.index');
    Route::post('experience', [ExperienceController::class, 'store'])->name('experience.store');
    Route::patch('experience/{experience}', [ExperienceController::class, 'update'])->name('experience.update');
    Route::delete('experience/{experience}', [ExperienceController::class, 'destroy'])->name('experience.destroy');

    // Education (resourceful)
    Route::resource('education', EducationController::class)
        ->except(['create', 'edit', 'show']);

    // Certifications (resourceful)
    Route::resource('certifications', CertificationController::class)
        ->except(['create', 'edit', 'show']);

    // Leads
    Route::get('leads', [LeadController::class, 'index'])->name('leads.index');

    // Testimonials
    Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::patch('testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Services (resourceful)
    Route::resource('services', ServiceController::class)
        ->except(['create', 'edit', 'show']);

    // PDF Export
    Route::get('profile/{profile}/export-pdf', [ProfileController::class, 'exportPdf'])->name('profile.export-pdf');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
