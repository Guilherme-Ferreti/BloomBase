<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FlowerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property string $id
 * @property string $common_name
 * @property string $scientific_name
 * @property string $slug
 * @property string $description
 * @property string|null $interesting_facts
 * @property string|null $care_instructions
 * @property string|null $lifespan
 * @property string $hero_image
 * @property array{url: string, alt: string, attribution: string}[]|null $images
 * @property string $family_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['common_name', 'scientific_name', 'slug', 'description', 'interesting_facts', 'care_instructions', 'lifespan', 'hero_image', 'images', 'family_id'])]
final class Flower extends Model
{
    /** @use HasFactory<FlowerFactory> */
    use HasFactory, HasUuids;

    /**
     * @return BelongsTo<Family, $this>
     */
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * @return BelongsToMany<Climate, $this>
     */
    public function climates(): BelongsToMany
    {
        return $this->belongsToMany(Climate::class);
    }

    /**
     * @return BelongsToMany<FloweringSeason, $this>
     */
    public function floweringSeasons(): BelongsToMany
    {
        return $this->belongsToMany(FloweringSeason::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }
}
