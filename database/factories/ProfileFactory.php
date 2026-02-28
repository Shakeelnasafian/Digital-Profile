<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'display_name' => fake()->name(),
            'job_title' => fake()->jobTitle(),
            'short_bio' => fake()->paragraph(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'linkedin' => fake()->url(),
            'github' => fake()->url(),
            'location' => fake()->city() . ', ' . fake()->country(),
            'template' => 'default',
            'is_public' => true,
            'skills' => implode(',', fake()->words(5)),
            'profile_views' => fake()->numberBetween(0, 1000),
            'twitter' => fake()->url(),
            'instagram' => fake()->url(),
            'youtube' => fake()->url(),
            'tiktok' => fake()->url(),
            'dribbble' => fake()->url(),
            'behance' => fake()->url(),
            'medium' => fake()->url(),
            'availability_status' => fake()->randomElement(['available', 'open_to_opportunities', 'not_available']),
            'scheduling_url' => fake()->url(),
        ];
    }

    /**
     * Indicate that the profile is private.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }

    /**
     * Indicate that the profile is public.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }
}