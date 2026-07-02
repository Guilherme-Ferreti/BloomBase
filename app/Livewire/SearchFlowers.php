<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Flower;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

final class SearchFlowers extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $familyId = null;

    public ?string $climateId = null;

    public ?string $seasonId = null;

    /**
     * @var array<string, array<string, string>>
     */
    protected array $queryString = ['search' => ['except' => '', 'as' => 'q']];

    /**
     * @return LengthAwarePaginator<int, Flower>
     */
    public function flowers(): LengthAwarePaginator
    {
        return Flower::query()
            ->with('family')
            ->when($this->familyId, fn ($q) => $q->where('family_id', $this->familyId))
            ->when($this->climateId, fn ($q) => $q->whereRelation('climates', 'id', $this->climateId))
            ->when($this->seasonId, fn ($q) => $q->whereRelation('floweringSeasons', 'id', $this->seasonId))
            ->where(function ($q) {
                $q->where('common_name', 'like', '%' . $this->search . '%')
                    ->orWhere('scientific_name', 'like', '%' . $this->search . '%');
            })
            ->paginate(12);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.search-flowers', [
            'flowers' => $this->flowers(),
        ]);
    }
}
