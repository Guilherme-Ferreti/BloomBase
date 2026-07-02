<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Climates;

use App\Models\Climate;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

final class Show extends Component
{
    public Climate $climate;

    public function render(): Factory|View
    {
        return view('livewire.pages.climates.show')->title($this->climate->name);
    }

    public function mount(Climate $climate): void
    {
        $this->climate = $climate;
    }
}
