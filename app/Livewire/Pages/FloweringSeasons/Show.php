<?php

declare(strict_types=1);

namespace App\Livewire\Pages\FloweringSeasons;

use App\Models\FloweringSeason;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

final class Show extends Component
{
    public FloweringSeason $floweringSeason;

    public function render(): Factory|View
    {
        return view('livewire.pages.flowering-seasons.show')->title($this->floweringSeason->name);
    }

    public function mount(FloweringSeason $floweringSeason): void
    {
        $this->floweringSeason = $floweringSeason;
    }
}
