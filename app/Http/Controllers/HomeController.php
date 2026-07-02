<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Climate;
use App\Models\Family;
use App\Models\Flower;
use App\Models\FloweringSeason;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredFlowers = Flower::query()
            ->with('family')
            ->inRandomOrder()
            ->take(4)
            ->get();

        $families = Family::query()
            ->orderBy('name')
            ->get();

        $climates = Climate::query()
            ->orderBy('name')
            ->get();

        $seasons = FloweringSeason::query()
            ->orderBy('name')
            ->get();

        return view('home', ['featuredFlowers' => $featuredFlowers, 'families' => $families, 'climates' => $climates, 'seasons' => $seasons]);
    }
}
