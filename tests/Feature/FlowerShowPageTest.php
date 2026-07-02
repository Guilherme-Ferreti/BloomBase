<?php

declare(strict_types=1);

use App\Models\Flower;
use Database\Seeders\FlowerSeeder;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(FlowerSeeder::class);
});

it('loads a flower detail page', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertOk();
});

it('shows flower common name', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee('Rose');
});

it('shows flower scientific name', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee('Rosa');
});

it('shows hero image', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee($flower->hero_image);
});

it('shows description', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee('woody perennial');
});

it('shows interesting facts', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee('5,000 years');
});

it('shows care instructions', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee('full sun');
});

it('shows family name', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee($flower->family->name);
});

it('shows gallery images', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    $testResponse = get(route('flowers.show', $flower));

    foreach ($flower->images as $image) {
        $testResponse->assertSee($image['url']);
    }
});

it('shows climate tags', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee('Temperate');
});

it('shows season tags', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee('Spring')
        ->assertSee('Summer');
});

it('shows lifespan', function () {
    $flower = Flower::where('slug', 'rose')->firstOrFail();

    get(route('flowers.show', $flower))
        ->assertSee('5');
});

it('returns 404 for unknown flower', function () {
    get('/flowers/nonexistent')
        ->assertNotFound();
});
