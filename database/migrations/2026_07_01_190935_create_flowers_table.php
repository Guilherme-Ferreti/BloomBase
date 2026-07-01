<?php

declare(strict_types=1);

use App\Models\Climate;
use App\Models\Family;
use App\Models\Flower;
use App\Models\FloweringSeason;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flowers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('common_name', 255);
            $table->string('scientific_name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description');
            $table->text('interesting_facts')->nullable();
            $table->text('care_instructions')->nullable();
            $table->string('lifespan', 255)->nullable();
            $table->string('hero_image', 255);
            $table->json('images')->nullable();
            $table->foreignUuidFor(Family::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index('common_name');
            $table->index('scientific_name');
        });

        Schema::create('climate_flower', function (Blueprint $table) {
            $table->foreignUuidFor(Flower::class)->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(Climate::class)->constrained()->cascadeOnDelete();
            $table->primary(['flower_id', 'climate_id']);
        });

        Schema::create('flower_flowering_season', function (Blueprint $table) {
            $table->foreignUuidFor(Flower::class)->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(FloweringSeason::class)->constrained()->cascadeOnDelete();
            $table->primary(['flower_id', 'flowering_season_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flower_flowering_season');
        Schema::dropIfExists('climate_flower');
        Schema::dropIfExists('flowers');
    }
};
