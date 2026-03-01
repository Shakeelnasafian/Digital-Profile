<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfileViewEvent>
 */
class ProfileViewEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profile_id' => Profile::factory(),
            'device_type' => fake()->randomElement(['mobile', 'tablet', 'desktop']),
            'referrer' => fake()->optional()->url(),
            'is_qr_scan' => fake()->boolean(20), // 20% chance of being a QR scan
            'viewed_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Indicate that the view was from a QR code scan.
     */
    public function qrScan(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_qr_scan' => true,
            'referrer' => null,
        ]);
    }

    /**
     * Indicate that the view was from a mobile device.
     */
    public function mobile(): static
    {
        return $this->state(fn (array $attributes) => [
            'device_type' => 'mobile',
        ]);
    }
}