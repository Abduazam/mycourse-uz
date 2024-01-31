<?php

namespace App\Http\Livewire\Reminder;

use App\Models\Reminder;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $reminders = Reminder::query()->get();

        return view('livewire.reminder.index', compact('reminders'));
    }
}
