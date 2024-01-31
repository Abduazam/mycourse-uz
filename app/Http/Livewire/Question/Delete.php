<?php

namespace App\Http\Livewire\Question;

use App\Models\Question;
use Illuminate\View\View;
use Livewire\Component;

class Delete extends Component
{
    public ?Question $question = null;

    public function delete(): void
    {
        $this->question->delete();

        $this->dispatchBrowserEvent('questionDeleted', [
            'title' => 'Question deleted',
            'icon' => 'warning',
            'iconColor' => 'red',
        ]);

        $this->emitUp('saved');

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.question.delete');
    }
}
