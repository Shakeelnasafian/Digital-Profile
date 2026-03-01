<?php

use App\Models\Certification;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('certification belongs to user', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create(['user_id' => $user->id]);

    expect($certification->user)->toBeInstanceOf(User::class);
    expect($certification->user->id)->toBe($user->id);
});

test('certification casts issue_date to date', function () {
    $certification = Certification::factory()->create([
        'issue_date' => '2023-01-15',
    ]);

    expect($certification->issue_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($certification->issue_date->format('Y-m-d'))->toBe('2023-01-15');
});

test('certification casts expiry_date to date', function () {
    $certification = Certification::factory()->create([
        'expiry_date' => '2025-01-15',
    ]);

    expect($certification->expiry_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($certification->expiry_date->format('Y-m-d'))->toBe('2025-01-15');
});

test('certification can have null expiry_date', function () {
    $certification = Certification::factory()->noExpiry()->create();

    expect($certification->expiry_date)->toBeNull();
});

test('certification stores all required fields', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create([
        'user_id' => $user->id,
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-01',
        'credential_url' => 'https://aws.amazon.com/cert/123',
        'credential_id' => 'AWS-123456',
    ]);

    expect($certification->title)->toBe('AWS Certified Developer');
    expect($certification->issuer)->toBe('Amazon Web Services');
    expect($certification->credential_url)->toBe('https://aws.amazon.com/cert/123');
    expect($certification->credential_id)->toBe('AWS-123456');
});

test('certification can have nullable credential_url', function () {
    $certification = Certification::factory()->create(['credential_url' => null]);

    expect($certification->credential_url)->toBeNull();
});

test('certification can have nullable credential_id', function () {
    $certification = Certification::factory()->create(['credential_id' => null]);

    expect($certification->credential_id)->toBeNull();
});

test('certification with expiry date is properly stored', function () {
    $certification = Certification::factory()->create([
        'issue_date' => '2023-01-01',
        'expiry_date' => '2026-01-01',
    ]);

    expect($certification->expiry_date)->not->toBeNull();
    expect($certification->expiry_date->isAfter($certification->issue_date))->toBeTrue();
});

test('certification without expiry does not expire', function () {
    $certification = Certification::factory()->noExpiry()->create();

    expect($certification->expiry_date)->toBeNull();
});