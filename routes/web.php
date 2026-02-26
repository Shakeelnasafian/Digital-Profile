<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExperienceController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Public profile page — no auth required
Route::get('/p/{slug}', [ProfileController::class, 'publicShow'])->name('profile.public');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();
        $profile = \App\Models\Profile::where('user_id', $user->id)->first();
        $projectCount = \App\Models\Project::where('user_id', $user->id)->count();
        $experienceCount = \App\Models\Experience::where('user_id', $user->id)->count();
        $profileViews = $profile ? $profile->profile_views : 0;

        return Inertia::render('dashboard', [
            'stats' => [
                'profile_views'    => $profileViews,
                'project_count'    => $projectCount,
                'experience_count' => $experienceCount,
                'has_profile'      => $profile ? true : false,
                'profile_slug'     => $profile ? $profile->slug : null,
            ],
        ]);
    })->name('dashboard');

    // Profile (owner views their own profile by slug)
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
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
