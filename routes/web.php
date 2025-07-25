<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DigitalProfileController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('profiles', DigitalProfileController::class)
        ->parameters(['profiles' => 'digitalProfile'])
        ->names('digital-profiles');

    Route::resource('projects', ProjectController::class)
        ->parameters(['projects' => 'project'])
        ->names('projects');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
