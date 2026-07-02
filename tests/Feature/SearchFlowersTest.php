<?php

declare(strict_types=1);

use App\Models\Climate;
use App\Models\Family;
use App\Models\FloweringSeason;
use Database\Seeders\FlowerSeeder;
use Livewire\Livewire;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(FlowerSeeder::class);
});

it('filters flowers by common name', function () {
    Livewire::test('search-flowers')
        ->set('search', 'rose')
        ->assertSee('Rose')
        ->assertDontSee('Sunflower');
});

it('filters flowers by scientific name', function () {
    Livewire::test('search-flowers')
        ->set('search', 'Helianthus')
        ->assertSee('Sunflower')
        ->assertDontSee('Rose');
});

it('supports partial matches', function () {
    Livewire::test('search-flowers')
        ->set('search', 'lav')
        ->assertSee('Lavender')
        ->assertDontSee('Rose');
});

it('shows all flowers when search is empty', function () {
    Livewire::test('search-flowers')
        ->assertSee('Rose')
        ->assertSee('Sunflower')
        ->assertSee('Lavender')
        ->assertSee('Tulip')
        ->assertSee('Daisy')
        ->assertSee('Orchid')
        ->assertSee('Cherry Blossom')
        ->assertSee('Hydrangea');
});

it('shows empty state when no results', function () {
    Livewire::test('search-flowers')
        ->set('search', 'zzzznotfound')
        ->assertSee('No flowers found matching your search.');
});

it('scopes by family', function () {
    $rosaceae = Family::where('name', 'Rosaceae')->first();

    Livewire::test('search-flowers', ['familyId' => $rosaceae->id])
        ->assertSee('Rose')
        ->assertSee('Cherry Blossom')
        ->assertDontSee('Sunflower');
});

it('scopes by climate', function () {
    $temperate = Climate::where('name', 'Temperate')->first();

    Livewire::test('search-flowers', ['climateId' => $temperate->id])
        ->assertSee('Rose')
        ->assertDontSee('Orchid');
});

it('scopes by season', function () {
    $spring = FloweringSeason::where('name', 'Spring')->first();

    Livewire::test('search-flowers', ['seasonId' => $spring->id])
        ->assertSee('Rose')
        ->assertSee('Tulip')
        ->assertDontSee('Lavender');
});

it('syncs search with query string', function () {
    get(route('flowers.index', ['q' => 'rose']))
        ->assertOk()
        ->assertSee('Rose');
});
