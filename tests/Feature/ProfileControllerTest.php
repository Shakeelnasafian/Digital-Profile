<?php

use App\Models\Profile;
use App\Models\User;
use App\Models\Education;
use App\Models\Certification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('authenticated user can view profile index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('profile.index'));

    $response->assertOk();
});

test('profile index shows only user profiles', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Profile::factory()->create(['user_id' => $user->id]);
    Profile::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('profile.index'));

    $response->assertOk();
});

test('user can create profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('profile.create'));

    $response->assertOk();
});

test('user with existing profile is redirected to profile show', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('profile.create'));

    $response->assertRedirect(route('profile.show', $profile->slug));
});

test('user can view own profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('profile.show', $profile->slug));

    $response->assertOk();
});

test('user cannot view another users private profile via show route', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('profile.show', $profile->slug));

    $response->assertNotFound();
});

test('public profile can be viewed without authentication', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->public()->create(['user_id' => $user->id]);

    $response = $this->get(route('profile.public', $profile->slug));

    $response->assertOk();
});

test('private profile cannot be viewed publicly', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->private()->create(['user_id' => $user->id]);

    $response = $this->get(route('profile.public', $profile->slug));

    $response->assertNotFound();
});

test('user can edit own profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('profile.edit', $profile->id));

    $response->assertOk();
});

test('user cannot edit another users profile', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('profile.edit', $profile->id));

    $response->assertNotFound();
});

test('user can update own profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'Updated Name',
        'job_title' => 'Senior Developer',
        'email' => 'updated@example.com',
        'is_public' => true,
    ]);

    $response->assertRedirect(route('profile.show', $profile->fresh()->slug));

    expect($profile->fresh()->display_name)->toBe('Updated Name');
    expect($profile->fresh()->job_title)->toBe('Senior Developer');
    expect($profile->fresh()->email)->toBe('updated@example.com');
});

test('user cannot update another users profile', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'Hacked Name',
        'email' => 'hacked@example.com',
        'is_public' => true,
    ]);

    $response->assertNotFound();

    expect($profile->fresh()->display_name)->not->toBe('Hacked Name');
});

test('profile update handles image upload', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $file = UploadedFile::fake()->image('profile.jpg');

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => $profile->display_name,
        'email' => $profile->email,
        'profile_image' => $file,
        'is_public' => true,
    ]);

    $response->assertRedirect();
    Storage::disk('public')->assertExists('profiles/' . $file->hashName());
});

test('profile update with custom slug updates slug', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);
    $originalSlug = $profile->slug;

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => $profile->display_name,
        'email' => $profile->email,
        'custom_slug' => 'my-custom-slug',
        'is_public' => true,
    ]);

    $response->assertRedirect();
    expect($profile->fresh()->slug)->toBe('my-custom-slug');
    expect($profile->fresh()->slug)->not->toBe($originalSlug);
});

test('user can delete own profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('profile.destroy', $profile->id));

    $response->assertRedirect(route('profile.index'));
    expect(Profile::find($profile->id))->toBeNull();
});

test('user cannot delete another users profile', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->delete(route('profile.destroy', $profile->id));

    $response->assertNotFound();
    expect(Profile::find($profile->id))->not->toBeNull();
});

test('profile deletion removes qr code file', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'qr_code_url' => 'qr_codes/test.svg',
    ]);

    Storage::disk('public')->put('qr_codes/test.svg', 'test content');

    $response = $this->actingAs($user)->delete(route('profile.destroy', $profile->id));

    $response->assertRedirect();
    Storage::disk('public')->assertMissing('qr_codes/test.svg');
});

test('vcard can be downloaded for public profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->public()->create([
        'user_id' => $user->id,
        'display_name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
    ]);

    $response = $this->get(route('profile.downloadVCard', $profile->slug));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/vcard; charset=utf-8');
    expect($response->getContent())->toContain('BEGIN:VCARD');
    expect($response->getContent())->toContain('FN:John Doe');
    expect($response->getContent())->toContain('EMAIL;TYPE=INTERNET:john@example.com');
    expect($response->getContent())->toContain('END:VCARD');
});

test('vcard download fails for private profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->private()->create(['user_id' => $user->id]);

    $response = $this->get(route('profile.downloadVCard', $profile->slug));

    $response->assertNotFound();
});

test('vcard includes all profile information', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->public()->create([
        'user_id' => $user->id,
        'display_name' => 'John Doe',
        'job_title' => 'Developer',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'website' => 'https://example.com',
        'linkedin' => 'https://linkedin.com/in/johndoe',
        'github' => 'https://github.com/johndoe',
        'location' => 'New York, USA',
        'short_bio' => 'Software developer',
    ]);

    $response = $this->get(route('profile.downloadVCard', $profile->slug));

    $content = $response->getContent();
    expect($content)->toContain('TITLE:Developer');
    expect($content)->toContain('TEL;TYPE=CELL:+1234567890');
    expect($content)->toContain('URL:https://example.com');
    expect($content)->toContain('ADR;TYPE=WORK:;;New York, USA;;;;');
    expect($content)->toContain('NOTE:Software developer');
});

test('slug availability can be checked', function () {
    $user = User::factory()->create();
    Profile::factory()->create(['user_id' => $user->id, 'slug' => 'existing-slug']);

    $responseAvailable = $this->actingAs($user)->get(route('profile.checkSlug', ['slug' => 'new-slug']));
    $responseUnavailable = $this->actingAs($user)->get(route('profile.checkSlug', ['slug' => 'existing-slug']));

    $responseAvailable->assertOk();
    expect($responseAvailable->json('available'))->toBeTrue();

    $responseUnavailable->assertOk();
    expect($responseUnavailable->json('available'))->toBeFalse();
});

test('slug availability check excludes current profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id, 'slug' => 'my-slug']);

    $response = $this->actingAs($user)->get(route('profile.checkSlug', [
        'slug' => 'my-slug',
        'profile_id' => $profile->id,
    ]));

    $response->assertOk();
    expect($response->json('available'))->toBeTrue();
});

test('public profile shows educations', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->public()->create(['user_id' => $user->id]);
    Education::factory()->count(2)->create(['user_id' => $user->id]);

    $response = $this->get(route('profile.public', $profile->slug));

    $response->assertOk();
});

test('public profile shows certifications', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->public()->create(['user_id' => $user->id]);
    Certification::factory()->count(2)->create(['user_id' => $user->id]);

    $response = $this->get(route('profile.public', $profile->slug));

    $response->assertOk();
});

test('profile update regenerates qr code', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);
    $oldQrCode = $profile->getRawOriginal('qr_code_url');

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'Updated Name',
        'email' => $profile->email,
        'custom_slug' => 'new-unique-slug',
        'is_public' => true,
    ]);

    $profile->refresh();
    $newQrCode = $profile->getRawOriginal('qr_code_url');

    $response->assertRedirect();
    expect($newQrCode)->toContain('qr_codes/');
    Storage::disk('public')->assertExists($newQrCode);
});