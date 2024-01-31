<?php

namespace App\Http\Livewire\Course;

use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Course $course;
    public $file;

    protected array $rules = [
        'course.title' => ['required', 'unique:courses,title', 'min:5', 'max:255', 'string'],
        'course.file' => ['nullable', 'mimes:jpg,jpeg,png,mp4'],
        'course.description' => ['required', 'min:5', 'string'],
        'course.active' => ['required', 'bool']
    ];

    /**
     * @throws ValidationException
     */
    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $file = $this->course->file;
        if (isset($this->file)) {
            if (isset($file) and storage_path('app/public/' . $file)) {
                unlink(storage_path('app/public/' . $file));
            }
            $file = $this->file->store('courses', 'public');
        }

        $this->course->update([
            'title' => $this->course->title,
            'slug' => Str::slug($this->course->title),
            'file' => $file,
            'description' => $this->course->description,
            'active' => $this->course->active,
        ]);

        session()->flash('success', 'Course updated');

        return redirect()->to('courses/' . $this->course->id);
    }

    public function render(): View
    {
        return view('livewire.course.edit');
    }
}
