<?php

namespace App\Http\Livewire\User;

use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    public $user;
    public ?string $new_name = null;

    public function edit(): void
    {
        if ($this->new_name != null) {
            $this->user->update([
                'first_name' => $this->new_name,
            ]);
        }

        $this->dispatchBrowserEvent('userEdited', [
            'title' => ucfirst($this->user->first_name) . ' edited',
            'icon' => 'info',
            'iconColor' => 'warning',
        ]);

        $this->emitUp('saved');

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.user.edit');
    }
}
