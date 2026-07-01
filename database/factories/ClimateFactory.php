<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Climate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Climate>
 */
final class ClimateFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->word()),
            'slug' => fn (array $attributes) => Str::slug($attributes['name']),
        ];
    }
}
