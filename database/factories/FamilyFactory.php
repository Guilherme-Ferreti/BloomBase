<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Family;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Family>
 */
final class FamilyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->word(),
            'slug'        => fn (array $attributes) => Str::slug($attributes['name']) . '-' . fake()->unique()->randomNumber(5),
            'description' => fake()->sentence(),
        ];
    }
}
