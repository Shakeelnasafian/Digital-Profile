<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('certification requires title', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasErrors('title');
});

test('certification requires issuer', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasErrors('issuer');
});

test('certification requires issue_date', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
    ]);

    $response->assertSessionHasErrors('issue_date');
});

test('certification issue_date must be valid date', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => 'not-a-date',
    ]);

    $response->assertSessionHasErrors('issue_date');
});

test('certification expiry_date must be valid date', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
        'expiry_date' => 'not-a-date',
    ]);

    $response->assertSessionHasErrors('expiry_date');
});

test('certification expiry_date must be after or equal to issue_date', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
        'expiry_date' => '2023-06-14',
    ]);

    $response->assertSessionHasErrors('expiry_date');
});

test('certification expiry_date can equal issue_date', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'One Day Certificate',
        'issuer' => 'Test Org',
        'issue_date' => '2023-06-15',
        'expiry_date' => '2023-06-15',
    ]);

    $response->assertSessionHasNoErrors();
});

test('certification expiry_date is optional', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'Lifetime Certification',
        'issuer' => 'Professional Org',
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasNoErrors();
});

test('certification credential_url is optional', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'Physical Certificate',
        'issuer' => 'University',
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasNoErrors();
});

test('certification credential_url must be valid url', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
        'credential_url' => 'not-a-url',
    ]);

    $response->assertSessionHasErrors('credential_url');
});

test('certification credential_id is optional', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasNoErrors();
});

test('certification credential_id must be string', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
        'credential_id' => 123,
    ]);

    $response->assertSessionHasErrors('credential_id');
});

test('certification title has max length', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => str_repeat('a', 256),
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasErrors('title');
});

test('certification issuer has max length', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => str_repeat('a', 256),
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasErrors('issuer');
});

test('certification credential_id has max length', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
        'credential_id' => str_repeat('a', 256),
    ]);

    $response->assertSessionHasErrors('credential_id');
});

test('certification accepts valid data with all fields', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
        'expiry_date' => '2026-06-15',
        'credential_url' => 'https://aws.amazon.com/cert/123',
        'credential_id' => 'AWS-123456',
    ]);

    $response->assertSessionHasNoErrors();
});

test('certification accepts valid data with minimal fields', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'Simple Certification',
        'issuer' => 'Test Organization',
        'issue_date' => '2023-01-01',
    ]);

    $response->assertSessionHasNoErrors();
});

test('certification title must be string', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 123,
        'issuer' => 'Amazon Web Services',
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasErrors('title');
});

test('certification issuer must be string', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'AWS Certified Developer',
        'issuer' => 123,
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasErrors('issuer');
});

test('certification with expiry after issue is valid', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'Time-Limited Cert',
        'issuer' => 'Test Org',
        'issue_date' => '2023-01-01',
        'expiry_date' => '2025-12-31',
    ]);

    $response->assertSessionHasNoErrors();
});

test('certification accepts various date formats', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('certification.store'), [
        'title' => 'Date Test Cert',
        'issuer' => 'Test Org',
        'issue_date' => '2023-06-15',
    ]);

    $response->assertSessionHasNoErrors();
});