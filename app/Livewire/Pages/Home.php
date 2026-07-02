<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Climate;
use App\Models\Family;
use App\Models\Flower;
use App\Models\FloweringSeason;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home')]
final class Home extends Component
{
    public function render(): Factory|View
    {
        return view('livewire.pages.home');
    }

    /**
     * @return Collection<int, Flower>
     */
    #[Computed]
    public function featuredFlowers(): Collection
    {
        return Flower::query()
            ->with('family')
            ->inRandomOrder()
            ->take(4)
            ->get();
    }

    /**
     * @return Collection<int, Family>
     */
    #[Computed(cache: true, key: 'home.families')]
    public function families(): Collection
    {
        return Family::query()
            ->orderBy('name')
            ->get();
    }

    /**
     * @return Collection<int, Climate>
     */
    #[Computed(cache: true, key: 'home.climates')]
    public function climates(): Collection
    {
        return Climate::query()
            ->orderBy('name')
            ->get();
    }

    /**
     * @return Collection<int, FloweringSeason>
     */
    #[Computed(cache: true, key: 'home.flowering-seasons')]
    public function floweringSeasons(): Collection
    {
        return FloweringSeason::query()
            ->orderBy('name')
            ->get();
    }
}
