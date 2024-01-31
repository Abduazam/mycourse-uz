<?php

namespace App\Http\Livewire\Course;

use App\Models\Course;
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
        $courses = Course::search($this->search)
            ->select('courses.*')
            ->selectSub(function ($query) {
                $query->from('lessons')
                    ->whereColumn('course_id', 'courses.id')
                    ->where('active', 1)
                    ->selectRaw('count(*)');
            }, 'lesson_count')
            ->selectSub(function ($query) {
                $query->from('user_courses')
                    ->whereColumn('course_id', 'courses.id')
                    ->where('status', 1)
                    ->selectRaw('count(*)');
            }, 'student_count')
            ->selectSub(function ($query) {
                $query->from('user_courses')
                    ->whereColumn('course_id', 'courses.id')
                    ->where('status', 0)
                    ->selectRaw('count(*)');
            }, 'application_count')
            ->groupBy('courses.id')
            ->orderBy(in_array($this->orderBy, ['lesson_count', 'student_count', 'application_count']) ? $this->orderBy : 'courses.' . $this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.course.index', compact('courses'));
    }
}
