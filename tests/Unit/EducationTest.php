<?php

use App\Models\Education;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('education belongs to user', function () {
    $user = User::factory()->create();
    $education = Education::factory()->create(['user_id' => $user->id]);

    expect($education->user)->toBeInstanceOf(User::class);
    expect($education->user->id)->toBe($user->id);
});

test('education casts is_current to boolean', function () {
    $education = Education::factory()->create(['is_current' => true]);

    expect($education->is_current)->toBeTrue();
    expect($education->is_current)->toBeBool();
});

test('education casts start_year to integer', function () {
    $education = Education::factory()->create(['start_year' => 2020]);

    expect($education->start_year)->toBe(2020);
    expect($education->start_year)->toBeInt();
});

test('education casts end_year to integer', function () {
    $education = Education::factory()->create(['end_year' => 2024]);

    expect($education->end_year)->toBe(2024);
    expect($education->end_year)->toBeInt();
});

test('education can have null end_year when current', function () {
    $education = Education::factory()->current()->create();

    expect($education->is_current)->toBeTrue();
    expect($education->end_year)->toBeNull();
});

test('education stores all required fields', function () {
    $user = User::factory()->create();
    $education = Education::factory()->create([
        'user_id' => $user->id,
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'field_of_study' => 'Computer Science',
        'start_year' => 2018,
        'end_year' => 2022,
        'description' => 'A great education',
    ]);

    expect($education->institution)->toBe('MIT');
    expect($education->degree)->toBe('Bachelor of Science');
    expect($education->field_of_study)->toBe('Computer Science');
    expect($education->start_year)->toBe(2018);
    expect($education->end_year)->toBe(2022);
    expect($education->description)->toBe('A great education');
});

test('education can have nullable field_of_study', function () {
    $education = Education::factory()->create(['field_of_study' => null]);

    expect($education->field_of_study)->toBeNull();
});

test('education can have nullable description', function () {
    $education = Education::factory()->create(['description' => null]);

    expect($education->description)->toBeNull();
});

test('current education is properly marked', function () {
    $current = Education::factory()->current()->create();
    $past = Education::factory()->create(['is_current' => false]);

    expect($current->is_current)->toBeTrue();
    expect($past->is_current)->toBeFalse();
});