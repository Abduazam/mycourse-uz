<?php

namespace App\Http\Livewire\Reminder;

use App\Services\ReminderService;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $file = null;
    public ?string $text = null;
    public int $perDay = 1;

    protected array $rules = [
        'file' => ['nullable', 'mimes:jpg,jpeg,png,mp4'],
        'text' => ['nullable', 'min:5', 'string'],
        'perDay' => ['required', 'numeric']
    ];

    /**
     * @throws ValidationException
     */
    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function store(ReminderService $service)
    {
        $validatedData = $this->validate();
        $service->create($validatedData, $this->file);

        session()->flash('success', 'Reminder created');
        return redirect()->to('reminders');
    }

    public function render(): View
    {
        return view('livewire.reminder.create');
    }
}
