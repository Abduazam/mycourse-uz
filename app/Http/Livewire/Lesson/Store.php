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

class Store extends Component
{
    use WithFileUploads;

    public int $additional_files = 0;
    public ?int $course_id = null;
    public ?string $title = null;
    public $file = null;
    public $files = [];
    public ?string $description = null;
    public ?bool $active = true;

    protected array $rules = [
        'course_id' => ['required', 'numeric'],
        'title' => ['required', 'unique:lessons,title', 'min:5', 'max:255', 'string'],
        'file' => ['nullable', 'mimes:jpg,jpeg,png,mp4,mp3'],
        'files' => ['nullable', 'array'],
        'files.*' => ['nullable', 'mimes:jpg,jpeg,png,mp4,mp3'],
        'description' => ['required', 'min:5', 'string'],
        'active' => ['required', 'bool']
    ];

    /**
     * @throws ValidationException
     */
    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function store()
    {
        $file = $this?->file?->store('lessons', 'public');

        $lesson = Lesson::create([
            'course_id' => $this->course_id,
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'file' => $file,
            'description' => $this->description,
            'active' => $this->active,
        ]);

        foreach ($this->files as $file) {
            $media = $file->store('lessons', 'public');

            LessonFiles::create([
                'lesson_id' => $lesson->id,
                'file' => $media,
            ]);
        }

        session()->flash('success', 'Lesson created');

        return redirect()->to('lessons');
    }

    public function addFile(): void
    {
        $this->additional_files++;
    }

    public function render(): View
    {
        $courses = Course::query()->where('active', '=', 1)->get();
        return view('livewire.lesson.store', compact('courses'));
    }
}
