<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Flowers;

use App\Models\Flower;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

final class Show extends Component
{
    public Flower $flower;

    public function render(): Factory|View
    {
        return view('livewire.pages.flowers.show')->title($this->flower->common_name);
    }

    public function mount(Flower $flower): void
    {
        $this->flower = $flower;
    }
}
