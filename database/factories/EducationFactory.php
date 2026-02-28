<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startYear = fake()->numberBetween(1980, 2022);
        $endYear = fake()->numberBetween($startYear, 2025);

        return [
            'user_id' => User::factory(),
            'institution' => fake()->company() . ' University',
            'degree' => fake()->randomElement(['Bachelor of Science', 'Master of Science', 'PhD', 'Bachelor of Arts']),
            'field_of_study' => fake()->randomElement(['Computer Science', 'Engineering', 'Business', 'Mathematics', 'Physics']),
            'start_year' => $startYear,
            'end_year' => $endYear,
            'is_current' => false,
            'description' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the education is current.
     */
    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_current' => true,
            'end_year' => null,
        ]);
    }
}