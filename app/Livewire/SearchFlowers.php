<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Flower;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class SearchFlowers extends Component
{
    use WithPagination;

    /**
     * @var array<mixed, mixed>
     */
    public array $flowers = [];

    #[Url(as: 'q')]
    public string $search = '';

    public int $page = 1;

    public bool $hasMorePages = false;

    public ?string $familyId = null;

    public ?string $climateId = null;

    public ?string $seasonId = null;

    public function render(): View
    {
        return view('livewire.search-flowers');
    }

    public function mount(): void
    {
        $this->loadFlowers();
    }

    public function updatedSearch(): void
    {
        $this->reset('flowers', 'page', 'hasMorePages');

        $this->loadFlowers();
    }

    public function loadFlowers(): void
    {
        $paginator = Flower::query()
            ->with('family')
            ->when($this->familyId, fn ($q) => $q->where('family_id', $this->familyId))
            ->when($this->climateId, fn ($q) => $q->whereRelation('climates', 'id', $this->climateId))
            ->when($this->seasonId, fn ($q) => $q->whereRelation('floweringSeasons', 'id', $this->seasonId))
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q->whereLike('common_name', '%' . $this->search . '%')
                ->orWhereLike('scientific_name', '%' . $this->search . '%')
            ))
            ->orderBy('common_name')
            ->simplePaginate(perPage: 12, page: $this->page);

        $this->flowers = array_merge($this->flowers, $paginator->toArray()['data']);

        $this->hasMorePages = $paginator->hasMorePages();
        $this->page++;
    }
}
