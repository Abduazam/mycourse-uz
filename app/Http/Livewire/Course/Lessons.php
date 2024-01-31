<?php

namespace App\Http\Livewire\Course;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Lessons extends Component
{
    public ?Course $course = null;

    use WithPagination;

    public function paginationView(): string
    {
        return 'vendor.livewire.bootstrap';
    }

    public int $perPage = 5;
    public string $search = '';
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
        $lessons = Lesson::search($this->search)
            ->select('lessons.*', DB::raw('count(tasks.id) AS task_count'), DB::raw('(SELECT count(*) FROM user_courses WHERE lesson_id = lessons.id AND status = 1) AS student_count'))
            ->leftJoin('tasks', 'tasks.lesson_id', '=', 'lessons.id')
            ->where('course_id', $this->course->id)
            ->groupBy('lessons.id')
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.course.lessons', compact('lessons'));
    }
}
