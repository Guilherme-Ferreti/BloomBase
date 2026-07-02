<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Families;

use App\Models\Family;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

final class Show extends Component
{
    public Family $family;

    public function render(): Factory|View
    {
        return view('livewire.pages.families.show')->title($this->family->name);
    }

    public function mount(Family $family): void
    {
        $this->family = $family;
    }
}
