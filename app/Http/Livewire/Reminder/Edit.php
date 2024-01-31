<?php

namespace App\Http\Livewire\Reminder;

use App\Models\Reminder;
use App\Services\ReminderService;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Reminder $reminder;

    public $file = null;

    protected array $rules = [
        'file' => ['nullable', 'mimes:jpg,jpeg,png,mp4'],
        'reminder.text' => ['nullable', 'min:5', 'string'],
        'reminder.per_day' => ['required', 'numeric']
    ];

    /**
     * @throws ValidationException
     */
    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    /**
     * @throws Exception
     */
    public function update(ReminderService $service)
    {
        $validatedData = $this->validate();
        $service->edit($validatedData, $this->file, $this->reminder);
        return redirect()->to('reminders');
    }

    public function render(): View
    {
        return view('livewire.reminder.edit');
    }
}
