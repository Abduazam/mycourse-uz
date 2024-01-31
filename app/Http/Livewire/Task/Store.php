<?php

namespace App\Http\Livewire\Task;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Task;
use App\Models\TaskFiles;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Store extends Component
{
    use WithFileUploads;

    public int $additional_files = 0;
    public ?int $course_id;
    public $lessons;
    public ?int $lesson_id = null;
    public $file = null;
    public $files = [];
    public ?string $description = null;
    public ?bool $active = true;

    protected array $rules = [
        'lesson_id' => ['required', 'numeric'],
        'file' => ['nullable'],
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
        $file = $this?->file?->store('tasks', 'public');

        $task = Task::create([
            'lesson_id' => $this->lesson_id,
            'file' => $file,
            'description' => $this->description,
            'active' => $this->active,
        ]);

        foreach ($this->files as $file) {
            $media = $file->store('tasks', 'public');

            TaskFiles::create([
                'task_id' => $task->id,
                'file' => $media,
            ]);
        }

        session()->flash('success', 'Task created');

        return redirect()->to('tasks');
    }

    public function addFile(): void
    {
        $this->additional_files++;
    }

    public function updatedCourseId($course_id): void
    {
        $this->lessons = Lesson::query()
            ->where('course_id', $course_id)
            ->where('active', 1)->get();
    }

    public function render(): View
    {
        $courses = Course::query()->where('active', '=', 1)->get();
        return view('livewire.task.store', compact('courses'));
    }
}
