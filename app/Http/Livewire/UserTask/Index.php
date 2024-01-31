<?php

namespace App\Http\Livewire\UserTask;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\UserTask;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'vendor.livewire.bootstrap';
    }

    public int $perPage = 10;
    public string $search = '';
    public string $orderBy = 'status';
    public string $orderDirection = 'desc';
    public ?int $course_id = null;
    public ?int $lesson_id = null;
    public $lessons;

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

    public function updatedCourseId($course_id): void
    {
        $this->lessons = Lesson::query()
            ->where('course_id', $course_id)
            ->where('active', 1)->get();
        $this->lesson_id = 0;
    }

    public function render(): View
    {
        $courses = Course::query()
            ->where('active', '=', 1)
            ->get();

        $tasks = UserTask::search($this->search)
            ->select('user_tasks.*', DB::raw('(SELECT COUNT(*) FROM user_task_files WHERE task_id = user_tasks.id) AS file_count'), 'bot_users.first_name', 'courses.title AS course_title', 'lessons.title AS lesson_title')
            ->join('bot_users', 'user_tasks.user_id', '=', 'bot_users.id')
            ->join('courses', 'user_tasks.course_id', '=', 'courses.id')
            ->join('lessons', 'user_tasks.lesson_id', '=', 'lessons.id')
            ->when($this->course_id, function ($query, $course_id) {
                return $query->where('user_tasks.course_id', $course_id);
            })
            ->when($this->lesson_id, function ($query, $lesson_id) {
                return $query->where('user_tasks.lesson_id', $lesson_id);
            })
            ->where('user_tasks.status', '!=', 2)
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.user-task.index', compact('courses', 'tasks'));
    }
}
