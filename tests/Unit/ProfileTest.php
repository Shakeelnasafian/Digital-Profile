<?php

use App\Models\Profile;
use App\Models\User;
use App\Models\Education;
use App\Models\Certification;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('profile automatically generates unique slug on creation', function () {
    $user = User::factory()->create();

    $profile = Profile::create([
        'user_id' => $user->id,
        'display_name' => 'John Doe',
        'email' => 'john@example.com',
        'is_public' => true,
    ]);

    expect($profile->slug)->not->toBeNull();
    expect($profile->slug)->toContain('john-doe');
});

test('profile slug generation ensures uniqueness', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $profile1 = Profile::create([
        'user_id' => $user1->id,
        'display_name' => 'John Doe',
        'email' => 'john@example.com',
        'is_public' => true,
    ]);

    $profile2 = Profile::create([
        'user_id' => $user2->id,
        'display_name' => 'John Doe',
        'email' => 'john2@example.com',
        'is_public' => true,
    ]);

    expect($profile1->slug)->not->toBe($profile2->slug);
});

test('profile belongs to user', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    expect($profile->user)->toBeInstanceOf(User::class);
    expect($profile->user->id)->toBe($user->id);
});

test('profile has many educations', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    Education::factory()->count(3)->create(['user_id' => $user->id]);

    expect($profile->educations)->toHaveCount(3);
    expect($profile->educations->first())->toBeInstanceOf(Education::class);
});

test('profile has many certifications', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    Certification::factory()->count(2)->create(['user_id' => $user->id]);

    expect($profile->certifications)->toHaveCount(2);
    expect($profile->certifications->first())->toBeInstanceOf(Certification::class);
});

test('profile image accessor returns storage url', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'profile_image' => 'profiles/test.jpg',
    ]);

    expect($profile->profile_image)->toContain('storage/profiles/test.jpg');
});

test('profile image accessor returns null when no image', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'profile_image' => null,
    ]);

    expect($profile->profile_image)->toBeNull();
});

test('qr code url accessor returns storage url', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'qr_code_url' => 'qr_codes/test.svg',
    ]);

    expect($profile->qr_code_url)->toContain('storage/qr_codes/test.svg');
});

test('qr code url accessor returns null when no qr code', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'qr_code_url' => null,
    ]);

    expect($profile->qr_code_url)->toBeNull();
});

test('profile slug is lowercase', function () {
    $user = User::factory()->create();

    $profile = Profile::create([
        'user_id' => $user->id,
        'display_name' => 'UPPERCASE NAME',
        'email' => 'test@example.com',
        'is_public' => true,
    ]);

    expect($profile->slug)->toBe(strtolower($profile->slug));
    expect($profile->slug)->not->toContain('UPPERCASE');
});

test('profile can store all social media links', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'twitter' => 'https://twitter.com/test',
        'instagram' => 'https://instagram.com/test',
        'youtube' => 'https://youtube.com/test',
        'tiktok' => 'https://tiktok.com/test',
        'dribbble' => 'https://dribbble.com/test',
        'behance' => 'https://behance.net/test',
        'medium' => 'https://medium.com/@test',
    ]);

    expect($profile->twitter)->toBe('https://twitter.com/test');
    expect($profile->instagram)->toBe('https://instagram.com/test');
    expect($profile->youtube)->toBe('https://youtube.com/test');
    expect($profile->tiktok)->toBe('https://tiktok.com/test');
    expect($profile->dribbble)->toBe('https://dribbble.com/test');
    expect($profile->behance)->toBe('https://behance.net/test');
    expect($profile->medium)->toBe('https://medium.com/@test');
});

test('profile can store availability status', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'availability_status' => 'available',
        'scheduling_url' => 'https://calendly.com/test',
    ]);

    expect($profile->availability_status)->toBe('available');
    expect($profile->scheduling_url)->toBe('https://calendly.com/test');
});