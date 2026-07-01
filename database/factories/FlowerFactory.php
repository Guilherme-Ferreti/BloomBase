<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Family;
use App\Models\Flower;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Flower>
 */
final class FlowerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'common_name'       => ucfirst(fake()->unique()->word()),
            'scientific_name'   => ucwords(fake()->unique()->word() . ' ' . fake()->word()),
            'slug'              => fn (array $attributes) => Str::slug($attributes['common_name']) . '-' . fake()->unique()->randomNumber(5),
            'description'       => fake()->paragraph(),
            'interesting_facts' => fake()->paragraph(),
            'care_instructions' => fake()->paragraph(),
            'lifespan'          => fake()->randomElement(['Annual', 'Perennial', 'Biennial']),
            'hero_image'        => fake()->imageUrl(),
            'images'            => null,
            'family_id'         => Family::factory(),
        ];
    }

    public function withImages(): static
    {
        return $this->state(fn (array $attributes) => [
            'images' => [
                ['url' => fake()->imageUrl(), 'alt' => fake()->sentence(), 'attribution' => fake()->name()],
                ['url' => fake()->imageUrl(), 'alt' => fake()->sentence(), 'attribution' => fake()->name()],
            ],
        ]);
    }
}
