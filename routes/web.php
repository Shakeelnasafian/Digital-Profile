<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::resource('profile', ProfileController::class)
        ->parameters(['profile' => 'profile'])
        ->names('profile');

    Route::resource('projects', ProjectController::class)
        ->parameters(['projects' => 'project'])
        ->names('projects');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
