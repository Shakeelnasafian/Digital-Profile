<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('education requires institution', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('institution');
});

test('education requires degree', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'start_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('degree');
});

test('education requires start_year', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('start_year');
});

test('education start_year must be integer', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 'not-an-integer',
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('start_year');
});

test('education start_year must be at least 1950', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 1949,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('start_year');
});

test('education start_year must be at most 2035', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2036,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('start_year');
});

test('education end_year must be integer', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'end_year' => 'not-an-integer',
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('end_year');
});

test('education end_year must be at least 1950', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 1950,
        'end_year' => 1949,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('end_year');
});

test('education end_year must be at most 2035', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'end_year' => 2036,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('end_year');
});

test('education end_year must be greater than or equal to start_year', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'end_year' => 2019,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('end_year');
});

test('education end_year can equal start_year', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Certificate Program',
        'start_year' => 2020,
        'end_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertSessionHasNoErrors();
});

test('education field_of_study is optional', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'end_year' => 2024,
        'is_current' => false,
    ]);

    $response->assertSessionHasNoErrors();
});

test('education field_of_study must be string', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'field_of_study' => 123,
        'start_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('field_of_study');
});

test('education field_of_study has max length', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'field_of_study' => str_repeat('a', 256),
        'start_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('field_of_study');
});

test('education description is optional', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'end_year' => 2024,
        'is_current' => false,
    ]);

    $response->assertSessionHasNoErrors();
});

test('education description has max length', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'description' => str_repeat('a', 2001),
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('description');
});

test('education is_current must be boolean', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'is_current' => 'not-a-boolean',
    ]);

    $response->assertSessionHasErrors('is_current');
});

test('education end_year is optional', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'is_current' => true,
    ]);

    $response->assertSessionHasNoErrors();
});

test('education accepts valid data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'field_of_study' => 'Computer Science',
        'start_year' => 2018,
        'end_year' => 2022,
        'is_current' => false,
        'description' => 'Great education experience',
    ]);

    $response->assertSessionHasNoErrors();
});

test('education institution has max length', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => str_repeat('a', 256),
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('institution');
});

test('education degree has max length', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => str_repeat('a', 256),
        'start_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertSessionHasErrors('degree');
});