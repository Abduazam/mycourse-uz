<?php

namespace App\Http\Livewire\Question;

use App\Models\Question;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public int $perPage = 10;
    public string $search = '';
    public string $orderBy = 'position';
    public string $orderDirection = 'asc';
    public int $keyboard_id;

    protected $listeners = ['saved' => '$refresh'];

    public function paginationView(): string
    {
        return 'vendor.livewire.bootstrap';
    }

    public function sortBy($columnName): void
    {
        $this->orderDirection = $this->swapSortDirection();
        $this->orderBy = $columnName;
    }

    public function swapSortDirection(): string
    {
        return $this->orderDirection === 'asc' ? 'desc' : 'asc';
    }

    public function updateQuestionPosition($items): void
    {
        foreach ($items as $item) {
            Question::find($item['value'])->update([
                'position' => $item['order']
            ]);
        }

        $this->dispatchBrowserEvent('questionChanged', [
            'title' => 'Question changed',
            'icon' => 'info',
            'iconColor' => 'lightblue',
        ]);
    }

    public function render(): View
    {
        $questions = Question::search($this->search)
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);
        return view('livewire.question.index', compact('questions'));
    }
}
