<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Climate;
use App\Models\Family;
use App\Models\Flower;
use App\Models\FloweringSeason;
use Illuminate\Database\Seeder;
use RuntimeException;

final class FlowerSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedFamilies();
        $this->seedClimates();
        $this->seedFloweringSeasons();
        $this->seedFlowers();
    }

    private function seedFamilies(): void
    {
        Family::factory()->createMany(
            $this->json('families')
        );
    }

    private function seedClimates(): void
    {
        Climate::factory()->createMany(
            $this->json('climates')
        );
    }

    private function seedFloweringSeasons(): void
    {
        FloweringSeason::factory()->createMany(
            $this->json('flowering_seasons')
        );
    }

    private function seedFlowers(): void
    {
        $families = Family::all();
        $climates = Climate::all();
        $seasons  = FloweringSeason::all();

        $flowers = $this->json('flowers');

        foreach ($flowers as $data) {
            $flower = Flower::create([
                'common_name'       => $data['common_name'],
                'scientific_name'   => $data['scientific_name'],
                'slug'              => $data['slug'],
                'description'       => $data['description'],
                'interesting_facts' => $data['interesting_facts'],
                'care_instructions' => $data['care_instructions'],
                'lifespan'          => $data['lifespan'],
                'hero_image'        => $data['hero_image'],
                'images'            => $data['images'],
                'family_id'         => $families->firstWhere('slug', $data['family'])->id,
            ]);

            $flower->climates()->attach(
                $climates->whereIn('slug', $data['climates'])->pluck('id')
            );

            $flower->floweringSeasons()->attach(
                $seasons->whereIn('slug', $data['flowering_seasons'])->pluck('id')
            );
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function json(string $name): array
    {
        $path     = database_path(sprintf('data/%s.json', $name));
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException('Unable to read seed data file: ' . $path);
        }

        return json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
    }
}
