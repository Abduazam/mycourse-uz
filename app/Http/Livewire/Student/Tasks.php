<?php

namespace App\Http\Livewire\Student;

use App\Models\BotUser;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\UserTask;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Tasks extends Component
{
    public ?BotUser $student = null;
    public ?int $course_id = null;

    use WithPagination;

    public function paginationView(): string
    {
        return 'vendor.livewire.bootstrap';
    }

    public int $perPage = 10;
    public string $orderBy = 'id';
    public string $orderDirection = 'asc';

    protected $listeners = ['saved' => '$refresh'];

    public function sortBy($columnName): void
    {
        $this->orderDirection = $this->swapSortDirection();
        $this->orderBy = $columnName;
    }

    public function swapSortDirection(): string
    {
        return $this->orderDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render(): View
    {
        $courses = Course::query()
            ->select('courses.*')
            ->join('user_courses', 'courses.id', '=', 'user_courses.course_id')
            ->where('user_courses.user_id', $this->student->id)
            ->where('active', '=', 1)
            ->get();

        $tasks = UserTask::query()
            ->select('user_tasks.*', DB::raw('(SELECT COUNT(*) FROM user_task_files WHERE task_id = user_tasks.id) AS file_count'), 'courses.title AS course_title', 'lessons.title AS lesson_title')
            ->join('courses', 'user_tasks.course_id', '=', 'courses.id')
            ->join('lessons', 'user_tasks.lesson_id', '=', 'lessons.id')
            ->where('user_tasks.user_id', $this->student->id)
            ->when($this->course_id, function ($query, $course_id) {
                return $query->where('user_tasks.course_id', $course_id);
            })
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.student.tasks', compact('tasks', 'courses'));
    }
}
