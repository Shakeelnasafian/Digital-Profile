<?php

use App\Models\Profile;
use App\Models\ProfileViewEvent;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('profile view event belongs to profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);
    $event = ProfileViewEvent::factory()->create(['profile_id' => $profile->id]);

    expect($event->profile)->toBeInstanceOf(Profile::class);
    expect($event->profile->id)->toBe($profile->id);
});

test('profile view event has timestamps disabled', function () {
    $event = new ProfileViewEvent();

    expect($event->timestamps)->toBeFalse();
});

test('profile view event casts is_qr_scan to boolean', function () {
    $event = ProfileViewEvent::factory()->create(['is_qr_scan' => true]);

    expect($event->is_qr_scan)->toBeTrue();
    expect($event->is_qr_scan)->toBeBool();
});

test('profile view event casts viewed_at to datetime', function () {
    $event = ProfileViewEvent::factory()->create(['viewed_at' => now()]);

    expect($event->viewed_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('profile view event stores device type', function () {
    $mobileEvent = ProfileViewEvent::factory()->mobile()->create();
    $desktopEvent = ProfileViewEvent::factory()->create(['device_type' => 'desktop']);

    expect($mobileEvent->device_type)->toBe('mobile');
    expect($desktopEvent->device_type)->toBe('desktop');
});

test('profile view event can track qr scan', function () {
    $qrEvent = ProfileViewEvent::factory()->qrScan()->create();

    expect($qrEvent->is_qr_scan)->toBeTrue();
    expect($qrEvent->referrer)->toBeNull();
});

test('profile view event can have referrer', function () {
    $event = ProfileViewEvent::factory()->create([
        'referrer' => 'https://google.com',
    ]);

    expect($event->referrer)->toBe('https://google.com');
});

test('profile view event can have null referrer', function () {
    $event = ProfileViewEvent::factory()->create(['referrer' => null]);

    expect($event->referrer)->toBeNull();
});

test('profile view event stores all required fields', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $event = ProfileViewEvent::factory()->create([
        'profile_id' => $profile->id,
        'device_type' => 'tablet',
        'referrer' => 'https://linkedin.com',
        'is_qr_scan' => false,
        'viewed_at' => now(),
    ]);

    expect($event->profile_id)->toBe($profile->id);
    expect($event->device_type)->toBe('tablet');
    expect($event->referrer)->toBe('https://linkedin.com');
    expect($event->is_qr_scan)->toBeFalse();
    expect($event->viewed_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('qr scan event has no referrer by default', function () {
    $qrEvent = ProfileViewEvent::factory()->qrScan()->create();

    expect($qrEvent->is_qr_scan)->toBeTrue();
    expect($qrEvent->referrer)->toBeNull();
});

test('profile view event tracks different device types', function () {
    $mobile = ProfileViewEvent::factory()->create(['device_type' => 'mobile']);
    $tablet = ProfileViewEvent::factory()->create(['device_type' => 'tablet']);
    $desktop = ProfileViewEvent::factory()->create(['device_type' => 'desktop']);

    expect($mobile->device_type)->toBe('mobile');
    expect($tablet->device_type)->toBe('tablet');
    expect($desktop->device_type)->toBe('desktop');
});