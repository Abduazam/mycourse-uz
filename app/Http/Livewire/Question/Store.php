<?php

namespace App\Http\Livewire\Question;

use App\Models\Keyboard;
use App\Models\Question;
use Illuminate\View\View;
use Livewire\Component;

class Store extends Component
{
    public $question;
    public $position;
    public $keyboard_id;

    protected array $rules = [
        'question' => ['required', 'unique:questions,question', 'min:5', 'max:255', 'string'],
        'keyboard_id' => ['nullable', 'numeric'],
    ];

    public function store()
    {
        $this->validate($this->rules);

        $this->getPosition();

        Question::create([
            'question' => $this->question,
            'position' => $this->position,
            'keyboard_id' => $this->keyboard_id,
        ]);

        $this->dispatchBrowserEvent('questionCreated', [
            'title' => 'Question created',
            'icon' => 'success',
            'iconColor' => 'green',
        ]);

        $this->reset();

        $this->emitUp('saved');
    }

    public function getPosition(): void
    {
        $lastQuestion = Question::query()->orderBy('position', 'desc')->first();
        $position = $lastQuestion ? $lastQuestion->position + 1 : 1;

        $this->position = $position;
    }

    public function render(): View
    {
        $keyboards = Keyboard::query()->get();
        return view('livewire.question.store', compact('keyboards'));
    }
}
