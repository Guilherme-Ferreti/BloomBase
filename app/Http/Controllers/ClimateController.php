<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Climate;
use Illuminate\View\View;

final class ClimateController extends Controller
{
    public function __invoke(Climate $climate): View
    {
        return view('climates.show', ['climate' => $climate]);
    }
}
