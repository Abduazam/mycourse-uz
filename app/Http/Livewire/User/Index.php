<?php

namespace App\Http\Livewire\User;

use App\Models\BotUser;
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
    public ?int $active = null;
    public ?int $authorized = null;
    public ?int $inactive = null;

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
        $users = BotUser::search($this->search)
            ->select('bot_users.*', 'user_actions.step_1', 'user_actions.step_2')
            ->join('user_actions', 'bot_users.chat_id', '=', 'user_actions.chat_id')
            ->when($this->active, function ($query) {
                return $query->orWhere('user_actions.step_1', '>', 0);
            })
            ->when($this->authorized, function ($query) {
                return $query->orWhere('user_actions.step_1', '=', 0);
            })
            ->when($this->inactive, function ($query) {
                return $query->orWhere('user_actions.step_1', '<', 0);
            })
            ->orderBy('bot_users.' . $this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);
        return view('livewire.user.index', compact('users'));
    }
}
