<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FloweringSeason;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<FloweringSeason>
 */
final class FloweringSeasonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->word()),
            'slug' => fn (array $attributes) => Str::slug($attributes['name']) . '-' . fake()->unique()->randomNumber(5),
        ];
    }
}
