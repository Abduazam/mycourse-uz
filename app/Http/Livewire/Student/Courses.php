<?php

namespace App\Http\Livewire\Student;

use App\Models\BotUser;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\UserCourse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Courses extends Component
{
    public ?BotUser $student = null;

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
        $courses = UserCourse::query()
            ->select('user_courses.*', 'courses.title as course_title', 'lessons.title as lesson_title')
            ->join('courses', 'user_courses.course_id', '=', 'courses.id')
            ->join('lessons', 'user_courses.lesson_id', '=', 'lessons.id')
            ->where('user_id', $this->student->id)
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.student.courses', compact('courses'));
    }
}
