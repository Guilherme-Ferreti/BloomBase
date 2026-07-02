<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Family;
use Illuminate\View\View;

final class FamilyController extends Controller
{
    public function __invoke(Family $family): View
    {
        return view('families.show', ['family' => $family]);
    }
}
