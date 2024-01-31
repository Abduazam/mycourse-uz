<?php

namespace App\Http\Livewire\Message;

use App\Models\BotUser;
use App\Models\Message;
use App\Models\Telegram;
use App\Services\SenderService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public $file = null;
    public ?string $text = null;

    public function store(): void
    {
        try {
            $file = $this?->file?->store('messages', 'public');

            Message::create([
                'file' => $file,
                'text' => $this->text,
            ]);

            $this->dispatchBrowserEvent('messageCreated', [
                'title' => 'Message created',
                'icon' => 'success',
                'iconColor' => 'green',
            ]);

            $this->reset();
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public function render(): View
    {
        return view('livewire.message.index');
    }
}
