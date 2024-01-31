<?php

namespace App\Http\Livewire\Lesson;

use App\Models\Lesson;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Tasks extends Component
{
    public ?Lesson $lesson = null;

    use WithPagination;

    public int $perPage = 5;
    public string $search = '';
    public string $orderBy = 'id';
    public string $orderDirection = 'desc';

    protected $listeners = ['saved' => '$refresh'];

    public function paginationView(): string
    {
        return 'vendor.livewire.bootstrap';
    }

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
        $tasks = Task::search($this->search)
            ->select('tasks.*', DB::raw('COUNT(task_files.id) + IF(tasks.file IS NOT NULL, 1, 0) AS file_count'))
            ->leftJoin('task_files', 'tasks.id', '=', 'task_files.task_id')
            ->where('lesson_id', $this->lesson->id)
            ->groupBy('tasks.id')
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.lesson.tasks', compact('tasks'));
    }
}
