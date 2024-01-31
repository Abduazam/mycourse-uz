<?php

namespace App\Http\Livewire\Course;

use App\Models\BotUser;
use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class Students extends Component
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

    public function render()
    {
        $students = BotUser::search($this->search)
            ->select('bot_users.*', 'lessons.id AS lesson_id', 'lessons.title')
            ->join('user_courses', 'user_courses.user_id', '=', 'bot_users.id')
            ->join('lessons', 'user_courses.lesson_id', '=', 'lessons.id')
            ->where('user_courses.course_id', $this->course->id)
            ->where('user_courses.status', 1)
            ->groupBy('bot_users.id')
            ->paginate($this->perPage);
        return view('livewire.course.students', compact('students'));
    }
}
