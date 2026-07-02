<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\FloweringSeason;
use Illuminate\View\View;

final class FloweringSeasonController extends Controller
{
    public function __invoke(FloweringSeason $floweringSeason): View
    {
        return view('seasons.show', ['floweringSeason' => $floweringSeason]);
    }
}
