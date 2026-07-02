<?php

declare(strict_types=1);

use App\Livewire\Pages;
use Illuminate\Support\Facades\Route;

Route::get('/', Pages\Home::class)->name('home');

Route::get('/flowers', Pages\Flowers\Index::class)->name('flowers.index');
Route::get('/flowers/{flower:slug}', Pages\Flowers\Show::class)->name('flowers.show');

Route::get('/families/{family:slug}', Pages\Families\Show::class)->name('families.show');
Route::get('/climates/{climate:slug}', Pages\Climates\Show::class)->name('climates.show');
Route::get('/seasons/{floweringSeason:slug}', Pages\FloweringSeasons\Show::class)->name('flowering-seasons.show');
