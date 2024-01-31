<?php

namespace App\Http\Livewire\Lesson;

use App\Models\Course;
use App\Models\Lesson;
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
    public $course_id;

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
        $lessons =  Lesson::search($this->search)
            ->select('lessons.*', DB::raw('count(tasks.lesson_id) as task_count'))
            ->selectSub(function ($query) {
                $query->from('user_courses')
                    ->whereColumn('lesson_id', 'lessons.id')
                    ->where('status', 1)
                    ->selectRaw('count(*)');
            }, 'student_count')
            ->leftJoin('tasks', 'tasks.lesson_id', '=', 'lessons.id')
            ->when(isset($this->course_id) and $this->course_id > 0, fn($query) => $query->where('course_id', $this->course_id))
            ->groupBy('lessons.id')
            ->orderBy(in_array($this->orderBy, ['task_count', 'student_count']) ? $this->orderBy : 'lessons.' . $this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.lesson.index', [
            'lessons' => $lessons,
            'courses' => Course::query()
                ->where('active', '=', 1)
                ->get(),
        ]);
    }
}
