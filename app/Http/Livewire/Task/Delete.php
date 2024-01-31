<?php

namespace App\Http\Livewire\Task;

use App\Models\Task;
use Illuminate\View\View;
use Livewire\Component;

class Delete extends Component
{
    public ?Task $task = null;

    public function delete(): void
    {
        if (isset($this->task->file) and storage_path('app/public/' . $this->task->file)) {
            unlink(storage_path('app/public/' . $this->task->file));
        }

        foreach ($this->task->files as $media) {
            if (storage_path('app/public/' . $media->file)) {
                unlink(storage_path('app/public/' . $media->file));
            }
            $media->delete();
        }

        $this->task->delete();

        $this->dispatchBrowserEvent('taskDeleted', [
            'title' => 'Task deleted',
            'icon' => 'warning',
            'iconColor' => 'red',
        ]);

        $this->emitUp('saved');

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.task.delete');
    }
}
