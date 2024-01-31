<?php

namespace App\Http\Livewire\Task;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Task;
use App\Models\TaskFiles;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public ?int $course_id;
    public Task $task;
    public $lessons;
    public int $additional_files = 0;
    public $file;
    public $files = [];

    protected array $rules = [
        'task.lesson_id' => ['required', 'numeric'],
        'task.file' => ['nullable'],
        'task.description' => ['required', 'min:5', 'string'],
        'task.active' => ['required', 'bool']
    ];

    public function mount(): void
    {
        $lesson = Lesson::where('id', $this->task->lesson_id)->first();
        $this->course_id = $lesson->course_id;
        $this->lessons = Lesson::query()
            ->where('course_id', $this->course_id)
            ->where('active', 1)->get();
        $this->additional_files = count($this->task->files);
    }

    /**
     * @throws ValidationException
     */
    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function addFile(): void
    {
        $this->additional_files++;
    }

    public function updatedCourseId($course_id): void
    {
        $this->lessons = Lesson::query()->where('course_id', $course_id)->where('active', 1)->get();
    }

    public function update()
    {
        $file = $this->task->file;
        if (isset($this->file)) {
            if (isset($file) and storage_path('app/public/' . $file)) {
                unlink(storage_path('app/public/' . $file));
            }
            $file = $this->file->store('tasks', 'public');
        }

        $this->task->update([
            'lesson_id' => $this->task->lesson_id,
            'file' => $file,
            'description' => $this->task->description,
            'active' => $this->task->active,
        ]);

        if (!empty($this->files) and count($this->files) > 0) {
            foreach ($this->task->files as $media) {
                if (storage_path('app/public/' . $media->file)) {
                    unlink(storage_path('app/public/' . $media->file));
                }
                $media->delete();
            }

            foreach ($this->files as $file) {
                $media = $file->store('tasks', 'public');

                TaskFiles::create([
                    'task_id' => $this->task->id,
                    'file' => $media,
                ]);
            }
        }

        session()->flash('success', 'Task updated');

        return redirect()->to('tasks/' . $this->task->id);
    }

    public function render(): View
    {
        $courses = Course::query()->where('active', '=', 1)->get();
        return view('livewire.task.edit', compact('courses'));
    }
}
