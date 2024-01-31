<?php

namespace App\Http\Livewire\User;

use App\Models\BotUser;
use App\Models\Telegram;
use App\Models\UserAction;
use Illuminate\View\View;
use Livewire\Component;

class Block extends Component
{
    public ?BotUser $user = null;
    public ?string $message = null;

    public function block(): void
    {
        UserAction::where('chat_id', $this?->user?->chat_id)->update([
            'step_1' => -1,
            'step_2' => 0,
        ]);

        if ($this->message != null) {
            $telegram = new Telegram(config('telegram.tokens.main'));
            $content = [
                'chat_id' => $this->user->chat_id,
                'text' => $this->message,
                'parse_mode' => 'html',
            ];
            $telegram->sendMessage($content);
        }

        $this->dispatchBrowserEvent('userBlocked', [
            'title' => ucfirst($this?->user?->first_name) . ' blocked',
            'icon' => 'warning',
            'iconColor' => 'red',
        ]);

        $this->emitUp('saved');

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.user.block');
    }
}
