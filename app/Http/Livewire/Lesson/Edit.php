<?php

namespace App\Http\Livewire\Lesson;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonFiles;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Lesson $lesson;
    public int $additional_files = 0;
    public $file = null;
    public $files = [];

    protected array $rules = [
        'lesson.course_id' => ['required', 'numeric'],
        'lesson.title' => ['required', 'unique:lessons,title', 'min:5', 'max:255', 'string'],
        'file' => ['nullable', 'mimes:jpg,jpeg,png,mp4,mp3'],
        'files' => ['nullable', 'array'],
        'files.*' => ['nullable', 'mimes:jpg,jpeg,png,mp4,mp3'],
        'lesson.description' => ['required', 'min:5', 'string'],
        'lesson.active' => ['required', 'bool']
    ];

    public function mount(): void
    {
        $this->additional_files = count($this->lesson->files);
    }

    /**
     * @throws ValidationException
     */
    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $file = $this->lesson->file;
        if (isset($this->file)) {
            if (isset($file) and storage_path('app/public/' . $file)) {
                unlink(storage_path('app/public/' . $file));
            }
            $file = $this->file->store('lessons', 'public');
        }

        $this->lesson->update([
            'course_id' => $this->lesson->course_id,
            'title' => $this->lesson->title,
            'slug' => Str::slug($this->lesson->title),
            'file' => $file,
            'description' => $this->lesson->description,
            'active' => $this->lesson->active,
        ]);

        if (!empty($this->files) and count($this->files) > 0) {
            foreach ($this->lesson->files as $media) {
                if (storage_path('app/public/' . $media->file)) {
                    unlink(storage_path('app/public/' . $media->file));
                }
                $media->delete();
            }

            foreach ($this->files as $file) {
                $media = $file->store('lessons', 'public');

                LessonFiles::create([
                    'lesson_id' => $this->lesson->id,
                    'file' => $media,
                ]);
            }
        }

        session()->flash('success', 'Lesson updated');

        return redirect()->to('lessons/' . $this->lesson->id);
    }

    public function addFile(): void
    {
        $this->additional_files++;
    }

    public function render(): View
    {
        $courses = Course::query()->where('active', '=', 1)->get();
        return view('livewire.lesson.edit', compact('courses'));
    }
}
