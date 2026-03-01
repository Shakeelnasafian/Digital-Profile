<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certification>
 */
class CertificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $issueDate = fake()->dateTimeBetween('-5 years', 'now');
        $expiryDate = fake()->optional()->dateTimeBetween($issueDate, '+5 years');

        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'issuer' => fake()->company(),
            'issue_date' => $issueDate,
            'expiry_date' => $expiryDate,
            'credential_url' => fake()->url(),
            'credential_id' => fake()->uuid(),
        ];
    }

    /**
     * Indicate that the certification has no expiry date.
     */
    public function noExpiry(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => null,
        ]);
    }
}