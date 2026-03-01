<?php

use App\Http\Resources\ProfileResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\CertificationResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\ProjectResource;
use App\Models\Profile;
use App\Models\Education;
use App\Models\Certification;
use App\Models\User;
use Illuminate\Http\Request;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ProfileResource Tests
test('profile resource contains correct data', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $resource = new ProfileResource($profile);
    $data = $resource->toArray(Request::create('/'));

    expect($data)->toHaveKeys([
        'id', 'slug', 'display_name', 'job_title', 'short_bio',
        'email', 'phone', 'whatsapp', 'website', 'linkedin',
        'github', 'twitter', 'instagram', 'youtube', 'tiktok',
        'dribbble', 'behance', 'medium', 'location', 'skills',
        'template', 'is_public', 'profile_views', 'profile_image',
        'qr_code_url', 'availability_status', 'scheduling_url'
    ]);
});

test('profile resource transforms data correctly', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'display_name' => 'John Doe',
        'job_title' => 'Developer',
        'email' => 'john@example.com',
    ]);

    $resource = new ProfileResource($profile);
    $data = $resource->toArray(Request::create('/'));

    expect($data['display_name'])->toBe('John Doe');
    expect($data['job_title'])->toBe('Developer');
    expect($data['email'])->toBe('john@example.com');
});

test('profile resource uses default template when null', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'template' => null,
    ]);

    $resource = new ProfileResource($profile);
    $data = $resource->toArray(Request::create('/'));

    expect($data['template'])->toBe('default');
});

test('profile resource includes all social media fields', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'twitter' => 'https://twitter.com/test',
        'instagram' => 'https://instagram.com/test',
        'youtube' => 'https://youtube.com/test',
        'tiktok' => 'https://tiktok.com/test',
    ]);

    $resource = new ProfileResource($profile);
    $data = $resource->toArray(Request::create('/'));

    expect($data['twitter'])->toBe('https://twitter.com/test');
    expect($data['instagram'])->toBe('https://instagram.com/test');
    expect($data['youtube'])->toBe('https://youtube.com/test');
    expect($data['tiktok'])->toBe('https://tiktok.com/test');
});

// EducationResource Tests
test('education resource contains correct data', function () {
    $user = User::factory()->create();
    $education = Education::factory()->create(['user_id' => $user->id]);

    $resource = new EducationResource($education);
    $data = $resource->toArray(Request::create('/'));

    expect($data)->toHaveKeys([
        'id', 'institution', 'degree', 'field_of_study',
        'start_year', 'end_year', 'is_current', 'description'
    ]);
});

test('education resource transforms data correctly', function () {
    $user = User::factory()->create();
    $education = Education::factory()->create([
        'user_id' => $user->id,
        'institution' => 'MIT',
        'degree' => 'Bachelor of Science',
        'field_of_study' => 'Computer Science',
        'start_year' => 2018,
        'end_year' => 2022,
    ]);

    $resource = new EducationResource($education);
    $data = $resource->toArray(Request::create('/'));

    expect($data['institution'])->toBe('MIT');
    expect($data['degree'])->toBe('Bachelor of Science');
    expect($data['field_of_study'])->toBe('Computer Science');
    expect($data['start_year'])->toBe(2018);
    expect($data['end_year'])->toBe(2022);
});

test('education resource handles current education', function () {
    $user = User::factory()->create();
    $education = Education::factory()->current()->create(['user_id' => $user->id]);

    $resource = new EducationResource($education);
    $data = $resource->toArray(Request::create('/'));

    expect($data['is_current'])->toBeTrue();
    expect($data['end_year'])->toBeNull();
});

// CertificationResource Tests
test('certification resource contains correct data', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create(['user_id' => $user->id]);

    $resource = new CertificationResource($certification);
    $data = $resource->toArray(Request::create('/'));

    expect($data)->toHaveKeys([
        'id', 'title', 'issuer', 'issue_date', 'expiry_date',
        'credential_url', 'credential_id'
    ]);
});

test('certification resource formats dates correctly', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create([
        'user_id' => $user->id,
        'issue_date' => '2023-06-15',
        'expiry_date' => '2026-06-15',
    ]);

    $resource = new CertificationResource($certification);
    $data = $resource->toArray(Request::create('/'));

    expect($data['issue_date'])->toBe('2023-06-15');
    expect($data['expiry_date'])->toBe('2026-06-15');
});

test('certification resource handles null expiry date', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->noExpiry()->create(['user_id' => $user->id]);

    $resource = new CertificationResource($certification);
    $data = $resource->toArray(Request::create('/'));

    expect($data['expiry_date'])->toBeNull();
});

test('certification resource transforms data correctly', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create([
        'user_id' => $user->id,
        'title' => 'AWS Certified Developer',
        'issuer' => 'Amazon Web Services',
        'credential_id' => 'AWS-123456',
    ]);

    $resource = new CertificationResource($certification);
    $data = $resource->toArray(Request::create('/'));

    expect($data['title'])->toBe('AWS Certified Developer');
    expect($data['issuer'])->toBe('Amazon Web Services');
    expect($data['credential_id'])->toBe('AWS-123456');
});

// ExperienceResource Tests
test('experience resource contains correct keys', function () {
    $resource = new ExperienceResource((object)[
        'id' => 1,
        'company' => 'Tech Corp',
        'position' => 'Developer',
        'location' => 'NYC',
        'start_date' => now(),
        'end_date' => now(),
        'is_current' => false,
        'description' => 'Test',
    ]);

    $data = $resource->toArray(Request::create('/'));

    expect($data)->toHaveKeys([
        'id', 'company', 'position', 'location',
        'start_date', 'end_date', 'is_current', 'description'
    ]);
});

// ProjectResource Tests
test('project resource contains correct keys', function () {
    $resource = new ProjectResource((object)[
        'id' => 1,
        'name' => 'Test Project',
        'description' => 'A test project',
        'project_url' => 'https://example.com',
        'image' => 'image.jpg',
        'start_date' => '2023-01-01',
        'end_date' => '2023-12-31',
        'status' => 'completed',
        'created_at' => now(),
    ]);

    $data = $resource->toArray(Request::create('/'));

    expect($data)->toHaveKeys([
        'id', 'name', 'description', 'project_url',
        'image', 'start_date', 'end_date', 'status', 'created_at'
    ]);
});

test('profile resource collection works correctly', function () {
    $user = User::factory()->create();
    $profiles = Profile::factory()->count(3)->create(['user_id' => $user->id]);

    $collection = ProfileResource::collection($profiles);
    $data = $collection->toArray(Request::create('/'));

    expect($data)->toHaveCount(3);
    expect($data[0])->toHaveKey('display_name');
});

test('education resource collection works correctly', function () {
    $user = User::factory()->create();
    $educations = Education::factory()->count(2)->create(['user_id' => $user->id]);

    $collection = EducationResource::collection($educations);
    $data = $collection->toArray(Request::create('/'));

    expect($data)->toHaveCount(2);
    expect($data[0])->toHaveKey('institution');
});

test('certification resource collection works correctly', function () {
    $user = User::factory()->create();
    $certifications = Certification::factory()->count(2)->create(['user_id' => $user->id]);

    $collection = CertificationResource::collection($certifications);
    $data = $collection->toArray(Request::create('/'));

    expect($data)->toHaveCount(2);
    expect($data[0])->toHaveKey('title');
});

test('profile resource handles null values gracefully', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'job_title' => null,
        'short_bio' => null,
        'phone' => null,
    ]);

    $resource = new ProfileResource($profile);
    $data = $resource->toArray(Request::create('/'));

    expect($data['job_title'])->toBeNull();
    expect($data['short_bio'])->toBeNull();
    expect($data['phone'])->toBeNull();
});

test('education resource handles null field_of_study', function () {
    $user = User::factory()->create();
    $education = Education::factory()->create([
        'user_id' => $user->id,
        'field_of_study' => null,
    ]);

    $resource = new EducationResource($education);
    $data = $resource->toArray(Request::create('/'));

    expect($data['field_of_study'])->toBeNull();
});

test('certification resource handles nullable fields', function () {
    $user = User::factory()->create();
    $certification = Certification::factory()->create([
        'user_id' => $user->id,
        'credential_url' => null,
        'credential_id' => null,
        'expiry_date' => null,
    ]);

    $resource = new CertificationResource($certification);
    $data = $resource->toArray(Request::create('/'));

    expect($data['credential_url'])->toBeNull();
    expect($data['credential_id'])->toBeNull();
    expect($data['expiry_date'])->toBeNull();
});