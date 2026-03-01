<?php

use App\Models\Profile;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('profile requires display_name', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'email' => 'test@example.com',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('display_name');
});

test('profile requires email', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('email');
});

test('profile email must be valid', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'not-an-email',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('email');
});

test('profile email must be unique', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $profile1 = Profile::factory()->create([
        'user_id' => $user1->id,
        'email' => 'existing@example.com',
    ]);

    $profile2 = Profile::factory()->create(['user_id' => $user2->id]);

    $response = $this->actingAs($user2)->patch(route('profile.update', $profile2->id), [
        'display_name' => 'John Doe',
        'email' => 'existing@example.com',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('email');
});

test('profile can keep same email when updating', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'email' => 'same@example.com',
    ]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'Updated Name',
        'email' => 'same@example.com',
        'is_public' => true,
    ]);

    $response->assertSessionHasNoErrors();
});

test('profile website must be valid url', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'website' => 'not-a-url',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('website');
});

test('profile linkedin must be valid url', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'linkedin' => 'not-a-url',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('linkedin');
});

test('profile github must be valid url', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'github' => 'not-a-url',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('github');
});

test('profile accepts valid availability status', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'availability_status' => 'available',
        'is_public' => true,
    ]);

    $response->assertSessionHasNoErrors();
});

test('profile rejects invalid availability status', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'availability_status' => 'invalid_status',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('availability_status');
});

test('profile scheduling_url must be valid url', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'scheduling_url' => 'not-a-url',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('scheduling_url');
});

test('profile short_bio has max length', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'short_bio' => str_repeat('a', 1001),
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('short_bio');
});

test('profile skills has max length', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'skills' => str_repeat('a', 2001),
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('skills');
});

test('profile phone has max length', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'phone' => str_repeat('1', 26),
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('phone');
});

test('profile is_public must be boolean', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'is_public' => 'not-a-boolean',
    ]);

    $response->assertSessionHasErrors('is_public');
});

test('profile accepts all valid social media urls', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'twitter' => 'https://twitter.com/test',
        'instagram' => 'https://instagram.com/test',
        'youtube' => 'https://youtube.com/test',
        'tiktok' => 'https://tiktok.com/test',
        'dribbble' => 'https://dribbble.com/test',
        'behance' => 'https://behance.net/test',
        'medium' => 'https://medium.com/@test',
        'is_public' => true,
    ]);

    $response->assertSessionHasNoErrors();
});

test('profile rejects invalid social media urls', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
        'display_name' => 'John Doe',
        'email' => 'test@example.com',
        'twitter' => 'not-a-url',
        'is_public' => true,
    ]);

    $response->assertSessionHasErrors('twitter');
});

test('profile accepts valid availability statuses', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    foreach (['available', 'open_to_opportunities', 'not_available'] as $status) {
        $response = $this->actingAs($user)->patch(route('profile.update', $profile->id), [
            'display_name' => 'John Doe',
            'email' => 'test@example.com',
            'availability_status' => $status,
            'is_public' => true,
        ]);

        $response->assertSessionHasNoErrors();
    }
});