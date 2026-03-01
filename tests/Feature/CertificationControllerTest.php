<?php

use App\Models\Certification;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated user can view certification index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('certification.index'));

    $response->assertOk();
});

test('certification index shows only user certifications', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Certification::factory()->count(2)->create(['user_id' => $user->id]);
    Certification::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('certification.index'));

    $response->assertOk();
});

test('certifications are ordered by issue_date descending', function () {
    $user = User::factory()->create();

    $old = Certification::factory()->create([
        'user_id' => $user->id,
        'issue_date' => '2020-01-01',
    ]);
    $recent = Certification::factory()->create([
        'user_id' => $user->id,
        'issue_date' => '2024-01-01',
    ]);

    $response = $this->actingAs($user)->get(route('certification.index'));

    $response->assertOk();
});

test('user can create certification', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
        'expiry_date' => '2026-06-15',
        'credential_url' => 'https://aws.amazon.com/cert/123',
        'credential_id' => 'AWS-123456',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect(Certification::where('user_id', $user->id)->count())->toBe(1);

    $certification = Certification::where('user_id', $user->id)->first();
    expect($certification->title)->toBe('AWS Certified Developer');
    expect($certification->issuer)->toBe('Amazon Web Services');
    expect($certification->credential_id)->toBe('AWS-123456');
});

test('user can update own certification', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('certification.update', $certification), [
        'title' => 'Updated Certification',
        'issuer' => 'Updated Issuer',
        'issue_date' => '2024-01-01',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect($certification->fresh()->title)->toBe('Updated Certification');
    expect($certification->fresh()->issuer)->toBe('Updated Issuer');
});

test('user cannot update another users certification', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $certification = Certification::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->patch(route('certification.update', $certification), [
        'title' => 'Hacked Certification',
        'issuer' => 'Hacked Issuer',
        'issue_date' => '2024-01-01',
    ]);

    $response->assertForbidden();
    expect($certification->fresh()->title)->not->toBe('Hacked Certification');
});

test('user can delete own certification', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('certification.destroy', $certification));

    $response->assertRedirect();
    $response->assertSessionHas('success');
    expect(Certification::find($certification->id))->toBeNull();
});

test('user cannot delete another users certification', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $certification = Certification::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->delete(route('certification.destroy', $certification));

    $response->assertForbidden();
    expect(Certification::find($certification->id))->not->toBeNull();
});

test('certification requires authentication for index', function () {
    $response = $this->get(route('certification.index'));

    $response->assertRedirect(route('login'));
});

test('certification requires authentication for store', function () {
    $response = $this->post(route('certification.store'), [
        'title' => 'Test Cert',
        'issuer' => 'Test Issuer',
        'issue_date' => '2024-01-01',
    ]);

    $response->assertRedirect(route('login'));
});

test('certification requires authentication for update', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create(['user_id' => $user->id]);

    $response = $this->patch(route('certification.update', $certification), [
        'title' => 'Test Cert',
        'issuer' => 'Test Issuer',
        'issue_date' => '2024-01-01',
    ]);

    $response->assertRedirect(route('login'));
});

test('certification requires authentication for delete', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create(['user_id' => $user->id]);

    $response = $this->delete(route('certification.destroy', $certification));

    $response->assertRedirect(route('login'));
});

test('certification can be created without expiry date', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'Lifetime Certification',
        'issuer' => 'Professional Organization',
        'issue_date' => '2023-01-01',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $certification = Certification::where('user_id', $user->id)->first();
    expect($certification->expiry_date)->toBeNull();
});

test('certification can be created without credential url', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'Physical Certificate',
        'issuer' => 'University',
        'issue_date' => '2020-05-15',
    ]);

    $response->assertRedirect();

    $certification = Certification::where('user_id', $user->id)->first();
    expect($certification->credential_url)->toBeNull();
});

test('certification with all optional fields can be created', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'Complete Certification',
        'issuer' => 'Complete Issuer',
        'issue_date' => '2023-01-01',
        'expiry_date' => '2025-01-01',
        'credential_url' => 'https://example.com/cert',
        'credential_id' => 'CERT-12345',
    ]);

    $response->assertRedirect();

    $certification = Certification::where('user_id', $user->id)->first();
    expect($certification->credential_url)->toBe('https://example.com/cert');
    expect($certification->credential_id)->toBe('CERT-12345');
    expect($certification->expiry_date)->not->toBeNull();
});

test('certification dates are properly formatted', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'Date Test Certification',
        'issuer' => 'Date Issuer',
        'issue_date' => '2023-06-15',
        'expiry_date' => '2026-06-15',
    ]);

    $certification = Certification::where('user_id', $user->id)->first();
    expect($certification->issue_date->format('Y-m-d'))->toBe('2023-06-15');
    expect($certification->expiry_date->format('Y-m-d'))->toBe('2026-06-15');
});