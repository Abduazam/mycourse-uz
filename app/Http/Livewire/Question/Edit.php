<?php

namespace App\Http\Livewire\Question;

use App\Models\Keyboard;
use App\Models\Question;
use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    public $question;

    protected array $rules = [
        'question.question' => ['required', 'unique:questions,question', 'min:5', 'max:255', 'string'],
        'question.keyboard_id' => ['nullable', 'numeric'],
    ];

    public function update()
    {
        $this->validate($this->rules);

        $this->question->update([
            'title' => $this->question->question,
            'keyboard_id' => $this->question->keyboard_id,
        ]);

        $this->dispatchBrowserEvent('questionUpdated', [
            'title' => 'Question updated',
            'icon' => 'success',
            'iconColor' => 'green',
        ]);

        $this->reset();

        $this->emitUp('saved');
    }

    public function render(): View
    {
        $keyboards = Keyboard::query()->get();
        return view('livewire.question.edit', compact('keyboards'));
    }
}
