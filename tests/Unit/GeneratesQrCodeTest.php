<?php

use App\Actions\Traits\GeneratesQrCode;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('generates qr code for profile', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $trait = new class {
        use GeneratesQrCode;

        public function generate(Profile $profile): void
        {
            $this->generateQrCode($profile);
        }
    };

    $trait->generate($profile);

    $qrPath = "qr_codes/{$profile->slug}.svg";
    Storage::disk('public')->assertExists($qrPath);

    $profile->refresh();
    expect($profile->getRawOriginal('qr_code_url'))->toBe($qrPath);
});

test('qr code contains profile url with qr ref parameter', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $trait = new class {
        use GeneratesQrCode;

        public function generate(Profile $profile): void
        {
            $this->generateQrCode($profile);
        }
    };

    $trait->generate($profile);

    $qrPath = "qr_codes/{$profile->slug}.svg";
    $content = Storage::disk('public')->get($qrPath);

    expect($content)->toContain($profile->slug);
    expect($content)->toContain('ref=qr');
});

test('qr code is saved as svg format', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $trait = new class {
        use GeneratesQrCode;

        public function generate(Profile $profile): void
        {
            $this->generateQrCode($profile);
        }
    };

    $trait->generate($profile);

    $qrPath = "qr_codes/{$profile->slug}.svg";
    $content = Storage::disk('public')->get($qrPath);

    expect($qrPath)->toEndWith('.svg');
    expect($content)->toContain('<svg');
});

test('qr code updates profile qr_code_url field', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
        'qr_code_url' => null,
    ]);

    expect($profile->getRawOriginal('qr_code_url'))->toBeNull();

    $trait = new class {
        use GeneratesQrCode;

        public function generate(Profile $profile): void
        {
            $this->generateQrCode($profile);
        }
    };

    $trait->generate($profile);

    $profile->refresh();
    expect($profile->getRawOriginal('qr_code_url'))->not->toBeNull();
    expect($profile->getRawOriginal('qr_code_url'))->toContain('qr_codes/');
});

test('regenerating qr code replaces existing file', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $trait = new class {
        use GeneratesQrCode;

        public function generate(Profile $profile): void
        {
            $this->generateQrCode($profile);
        }
    };

    // Generate first time
    $trait->generate($profile);
    $qrPath = "qr_codes/{$profile->slug}.svg";
    $firstContent = Storage::disk('public')->get($qrPath);

    // Generate again
    $trait->generate($profile);
    $secondContent = Storage::disk('public')->get($qrPath);

    // File should exist and be updated
    Storage::disk('public')->assertExists($qrPath);
    expect($secondContent)->toBeString();
});

test('qr code path uses profile slug', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create([
        'user_id' => $user->id,
    ]);

    $trait = new class {
        use GeneratesQrCode;

        public function generate(Profile $profile): void
        {
            $this->generateQrCode($profile);
        }
    };

    $trait->generate($profile);

    $profile->refresh();
    expect($profile->getRawOriginal('qr_code_url'))->toContain($profile->slug);
    expect($profile->getRawOriginal('qr_code_url'))->toStartWith('qr_codes/');
});