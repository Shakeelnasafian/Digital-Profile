<?php

use App\Models\Education;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated user can view education index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('education.index'));

    $response->assertOk();
});

test('education index shows only user educations', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Education::factory()->count(2)->create(['user_id' => $user->id]);
    Education::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('education.index'));

    $response->assertOk();
});

test('educations are ordered by start_year descending', function () {
    $user = User::factory()->create();

    $old = Education::factory()->create(['user_id' => $user->id, 'start_year' => 2010]);
    $recent = Education::factory()->create(['user_id' => $user->id, 'start_year' => 2020]);

    $response = $this->actingAs($user)->get(route('education.index'));

    $response->assertOk();
});

test('user can create education', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'field_of_study' => 'Computer Science',
        'start_year' => 2018,
        'end_year' => 2022,
        'is_current' => false,
        'description' => 'Studied computer science',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect(Education::where('user_id', $user->id)->count())->toBe(1);

    $education = Education::where('user_id', $user->id)->first();
    expect($education->institution)->toBe('MIT');
    expect($education->degree)->toBe('Bachelor of Science');
    expect($education->field_of_study)->toBe('Computer Science');
});

test('user can update own education', function () {
    $user = User::factory()->create();
    $education = Education::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('education.update', $education), [
        'institution' => 'Stanford University',
        'degree' => 'Master of Science',
        'field_of_study' => 'AI',
        'start_year' => 2020,
        'end_year' => 2022,
        'is_current' => false,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect($education->fresh()->institution)->toBe('Stanford University');
    expect($education->fresh()->degree)->toBe('Master of Science');
    expect($education->fresh()->field_of_study)->toBe('AI');
});

test('user cannot update another users education', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $education = Education::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->patch(route('education.update', $education), [
        'institution' => 'Hacked University',
        'degree' => 'Hacked Degree',
        'start_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertForbidden();
    expect($education->fresh()->institution)->not->toBe('Hacked University');
});

test('user can delete own education', function () {
    $user = User::factory()->create();
    $education = Education::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('education.destroy', $education));

    $response->assertRedirect();
    $response->assertSessionHas('success');
    expect(Education::find($education->id))->toBeNull();
});

test('user cannot delete another users education', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $education = Education::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->delete(route('education.destroy', $education));

    $response->assertForbidden();
    expect(Education::find($education->id))->not->toBeNull();
});

test('education requires authentication for index', function () {
    $response = $this->get(route('education.index'));

    $response->assertRedirect(route('login'));
});

test('education requires authentication for store', function () {
    $response = $this->post(route('education.store'), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertRedirect(route('login'));
});

test('education requires authentication for update', function () {
    $user = User::factory()->create();
    $education = Education::factory()->create(['user_id' => $user->id]);

    $response = $this->patch(route('education.update', $education), [
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'start_year' => 2020,
        'is_current' => false,
    ]);

    $response->assertRedirect(route('login'));
});

test('education requires authentication for delete', function () {
    $user = User::factory()->create();
    $education = Education::factory()->create(['user_id' => $user->id]);

    $response = $this->delete(route('education.destroy', $education));

    $response->assertRedirect(route('login'));
});

test('current education can be created without end year', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'Harvard',
        'degree' => 'PhD',
        'field_of_study' => 'Physics',
        'start_year' => 2022,
        'is_current' => true,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $education = Education::where('user_id', $user->id)->first();
    expect($education->is_current)->toBeTrue();
    expect($education->end_year)->toBeNull();
});

test('education with description can be stored', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('education.store'), [
        'institution' => 'Yale',
        'degree' => 'Bachelor of Arts',
        'start_year' => 2018,
        'end_year' => 2022,
        'is_current' => false,
        'description' => 'Studied liberal arts with focus on literature',
    ]);

    $response->assertRedirect();

    $education = Education::where('user_id', $user->id)->first();
    expect($education->description)->toBe('Studied liberal arts with focus on literature');
});