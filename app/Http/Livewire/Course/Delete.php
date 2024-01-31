<?php

namespace App\Http\Livewire\Course;

use App\Models\Course;
use Illuminate\View\View;
use Livewire\Component;

class Delete extends Component
{
    public ?Course $course = null;

    public function delete(): void
    {
        if (isset($this->course->file) and storage_path('app/public/' . $this->course->file)) {
            unlink(storage_path('app/public/' . $this->course->file));
        }
        $this->course->delete();

        $this->dispatchBrowserEvent('courseDeleted', [
            'title' => 'Course deleted',
            'icon' => 'warning',
            'iconColor' => 'red',
        ]);

        $this->emitUp('saved');

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.course.delete');
    }
}
