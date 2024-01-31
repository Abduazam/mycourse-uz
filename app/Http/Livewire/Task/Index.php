<?php

namespace App\Http\Livewire\Task;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Task;
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
    public string $orderBy = 'id';
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

    public function updatedCourseId($course_id): void
    {
        $this->lessons = Lesson::query()
            ->where('course_id', $course_id)
            ->where('active', 1)->get();
        $this->lesson_id = 0;
    }

    public function swapSortDirection(): string
    {
        return $this->orderDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render(): View
    {
        $tasks = Task::search($this->search)
            ->select('tasks.*', DB::raw('IFNULL(COUNT(task_files.task_id), 0) + IF(tasks.file IS NOT NULL, 1, 0) AS file_count'))
            ->leftJoin('task_files', 'tasks.id', '=', 'task_files.task_id')
            ->when($this->course_id, function ($query, $course_id) {
                return $query->join('lessons', 'tasks.lesson_id', '=', 'lessons.id')
                    ->where('lessons.course_id', $course_id);
            })
            ->when($this->lesson_id, function ($query, $lesson_id) {
                return $query->where('lessons.id', $lesson_id);
            })
            ->groupBy('tasks.id')
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.task.index', [
            'tasks' => $tasks,
            'courses' => Course::query()
                ->where('active', '=', 1)
                ->get(),
        ]);
    }
}
