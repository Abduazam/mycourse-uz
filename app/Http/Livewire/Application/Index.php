<?php

namespace App\Http\Livewire\Application;

use App\Models\BotUser;
use App\Models\Course;
use Livewire\Component;

class Index extends Component
{
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

    public function render()
    {
        $users = BotUser::search($this->search)
            ->select('bot_users.id', 'bot_users.first_name', 'bot_users.username', 'user_courses.course_id', 'user_courses.status', 'user_courses.created_at')
            ->join('user_courses', 'bot_users.id', '=', 'user_courses.user_id')
            ->where('status', 0)
            ->whereNotNull('course_id')
            ->when(isset($this->course_id) and $this->course_id > 0, fn($query) => $query->where('user_courses.course_id', $this->course_id))
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        $courses = Course::query()
            ->where('active', '=', 1)
            ->get();

        return view('livewire.application.index', compact('users', 'courses'));
    }
}
