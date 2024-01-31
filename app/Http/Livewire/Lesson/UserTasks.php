<?php

namespace App\Http\Livewire\Lesson;

use App\Models\Lesson;
use App\Models\UserTask;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class UserTasks extends Component
{
    public ?Lesson $lesson = null;

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
        $tasks = UserTask::query()
            ->select('user_tasks.*', DB::raw('(SELECT COUNT(*) FROM user_task_files WHERE task_id = user_tasks.id) AS file_count'), 'bot_users.first_name')
            ->join('bot_users', 'user_tasks.user_id', '=', 'bot_users.id')
            ->where('user_tasks.lesson_id', $this->lesson->id)
            ->where('user_tasks.status', 1)
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.lesson.user-tasks', compact('tasks'));
    }
}
