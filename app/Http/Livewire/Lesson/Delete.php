<?php

namespace App\Http\Livewire\Lesson;

use App\Models\Lesson;
use Illuminate\View\View;
use Livewire\Component;

class Delete extends Component
{
    public ?Lesson $lesson = null;

    public function delete(): void
    {
        if (isset($this->lesson->file) and storage_path('app/public/' . $this->lesson->file)) {
            unlink(storage_path('app/public/' . $this->lesson->file));
        }

        foreach ($this->lesson->files as $media) {
            if (storage_path('app/public/' . $media->file)) {
                unlink(storage_path('app/public/' . $media->file));
            }
            $media->delete();
        }

        $this->lesson->delete();

        $this->dispatchBrowserEvent('lessonDeleted', [
            'title' => 'Lesson deleted',
            'icon' => 'warning',
            'iconColor' => 'red',
        ]);

        $this->emitUp('saved');

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.lesson.delete');
    }
}
