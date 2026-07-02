<?php

declare(strict_types=1);

use App\Models\Climate;
use App\Models\Family;
use App\Models\FloweringSeason;
use Database\Seeders\FlowerSeeder;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(FlowerSeeder::class);
});

it('loads the family page and shows filtered flowers', function () {
    $family = Family::where('slug', 'rosaceae')->firstOrFail();

    get(route('families.show', $family))
        ->assertOk()
        ->assertSee('Rose')
        ->assertSee('Cherry Blossom')
        ->assertDontSee('Sunflower');
});

it('shows the family name in the header', function () {
    $family = Family::where('slug', 'rosaceae')->firstOrFail();

    get(route('families.show', $family))
        ->assertSee('Rosaceae');
});

it('loads the climate page and shows filtered flowers', function () {
    $climate = Climate::where('slug', 'temperate')->firstOrFail();

    get(route('climates.show', $climate))
        ->assertOk()
        ->assertSee('Daisy')
        ->assertDontSee('Orchid');
});

it('shows the climate name in the header', function () {
    $climate = Climate::where('slug', 'temperate')->firstOrFail();

    get(route('climates.show', $climate))
        ->assertSee('Temperate');
});

it('loads the season page and shows filtered flowers', function () {
    $season = FloweringSeason::where('slug', 'spring')->firstOrFail();

    get(route('flowering-seasons.show', $season))
        ->assertOk()
        ->assertSee('Daisy')
        ->assertDontSee('Lavender');
});

it('shows the season name in the header', function () {
    $season = FloweringSeason::where('slug', 'spring')->firstOrFail();

    get(route('flowering-seasons.show', $season))
        ->assertSee('Spring');
});

it('further filters via search within category', function () {
    $family = Family::where('slug', 'asteraceae')->firstOrFail();

    get(route('families.show', ['family' => $family, 'q' => 'sun']))
        ->assertOk()
        ->assertSee('Sunflower')
        ->assertDontSee('Daisy');
});

it('shows empty state when category has no flowers', function () {
    $family = Family::factory()->create([
        'name' => 'NewFamily',
        'slug' => 'newfamily',
    ]);

    get(route('families.show', $family))
        ->assertOk()
        ->assertSee('NewFamily')
        ->assertSee('No flowers found matching your search.');
});
