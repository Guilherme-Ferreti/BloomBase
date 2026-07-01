<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FloweringSeasonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'slug'])]
final class FloweringSeason extends Model
{
    /** @use HasFactory<FloweringSeasonFactory> */
    use HasFactory, HasUuids;

    /**
     * @return BelongsToMany<Flower, $this>
     */
    public function flowers(): BelongsToMany
    {
        return $this->belongsToMany(Flower::class);
    }
}
