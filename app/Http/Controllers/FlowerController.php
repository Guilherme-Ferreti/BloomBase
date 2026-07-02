<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Flower;
use Illuminate\View\View;

final class FlowerController extends Controller
{
    public function index(): View
    {
        return view('flowers.index');
    }

    public function show(Flower $flower): View
    {
        $flower->load('family', 'climates', 'floweringSeasons');

        return view('flowers.show', ['flower' => $flower]);
    }
}
