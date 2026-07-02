<?php

declare(strict_types=1);

use App\Http\Controllers\ClimateController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FlowerController;
use App\Http\Controllers\FloweringSeasonController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/flowers', [FlowerController::class, 'index'])->name('flowers.index');
Route::get('/flowers/{flower:slug}', [FlowerController::class, 'show'])->name('flowers.show');
Route::get('/families/{family:slug}', FamilyController::class)->name('families.show');
Route::get('/climates/{climate:slug}', ClimateController::class)->name('climates.show');
Route::get('/seasons/{floweringSeason:slug}', FloweringSeasonController::class)->name('seasons.show');
