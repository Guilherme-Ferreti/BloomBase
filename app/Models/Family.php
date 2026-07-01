<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FamilyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'slug', 'description'])]
final class Family extends Model
{
    /** @use HasFactory<FamilyFactory> */
    use HasFactory, HasUuids;

    /**
     * @return HasMany<Flower, $this>
     */
    public function flowers(): HasMany
    {
        return $this->hasMany(Flower::class);
    }
}
