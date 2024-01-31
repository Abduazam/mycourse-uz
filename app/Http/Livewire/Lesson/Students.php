<?php

namespace App\Http\Livewire\Lesson;

use App\Models\BotUser;
use App\Models\Lesson;
use Livewire\Component;
use Livewire\WithPagination;

class Students extends Component
{
    public ?Lesson $lesson = null;

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
            ->select('bot_users.*')
            ->join('user_courses', 'user_courses.user_id', '=', 'bot_users.id')
            ->where('user_courses.lesson_id', $this->lesson->id)
            ->where('user_courses.status', 1)
            ->groupBy('bot_users.id')
            ->paginate($this->perPage);
        return view('livewire.lesson.students', compact('students'));
    }
}
