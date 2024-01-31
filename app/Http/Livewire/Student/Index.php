<?php

namespace App\Http\Livewire\Student;

use App\Models\BotUser;
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
        $students = BotUser::search($this->search)
            ->select('bot_users.*', DB::raw('COUNT(user_courses.id) AS course_count'))
            ->join('user_courses', 'bot_users.id', '=', 'user_courses.user_id')
            ->groupBy('bot_users.id')
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.student.index', compact('students'));
    }
}
