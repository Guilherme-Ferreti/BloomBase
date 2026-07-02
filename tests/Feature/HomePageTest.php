<?php

declare(strict_types=1);

use Database\Seeders\FlowerSeeder;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(FlowerSeeder::class);
});

it('loads the homepage', function () {
    get(route('home'))
        ->assertOk();
});

it('shows the site name in the nav', function () {
    get(route('home'))
        ->assertSee('BloomBase');
});

it('has a skip-to-content link', function () {
    get(route('home'))
        ->assertSee('Skip to content');
});

it('has navigation links', function () {
    get(route('home'))
        ->assertSee('Browse Flowers');
});

it('displays featured flowers section', function () {
    get(route('home'))
        ->assertSee('Featured Flowers');
});

it('displays flower images in featured section', function () {
    get(route('home'))
        ->assertSee('img');
});

it('displays category links', function () {
    get(route('home'))
        ->assertSee('Rosaceae')
        ->assertSee('Temperate')
        ->assertSee('Spring');
});

it('has a working search bar', function () {
    get(route('home'))
        ->assertSee('Search')
        ->assertSee('search');
});
