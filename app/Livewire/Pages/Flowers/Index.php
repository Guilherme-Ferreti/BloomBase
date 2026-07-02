<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Flowers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('All Flowers')]
final class Index extends Component
{
    public function render(): Factory|View
    {
        return view('livewire.pages.flowers.index');
    }
}
