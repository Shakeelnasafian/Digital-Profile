<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
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
    Route::get('/profiles', [DigitalProfileController::class, 'index'])->name('digital-profiles.index');
    Route::get('/profiles/create', [DigitalProfileController::class, 'create'])->name('digital-profiles.create');
    Route::post('/profiles', [DigitalProfileController::class, 'store'])->name('digital-profiles.store');
    Route::get('/profiles/{digitalProfile}/edit', [DigitalProfileController::class, 'edit'])->name('digital-profiles.edit');
    Route::put('/profiles/{digitalProfile}', [DigitalProfileController::class, 'update'])->name('digital-profiles.update');
    Route::delete('/profiles/{digitalProfile}', [DigitalProfileController::class, 'destroy'])->name('digital-profiles.destroy');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
