<?php

namespace App\Http\Livewire\UserTask;

use App\Models\TaskResponse;
use App\Models\Telegram;
use App\Models\UserTask;
use App\Services\TaskService;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Accept extends Component
{
    use WithFileUploads;

    public ?UserTask $user_task = null;
    public $files = [];
    public ?string $message = null;
    public int $additional_files = 0;

    protected array $rules = [
        'files' => ['nullable', 'array'],
        'files.*' => ['nullable', 'mimes:jpg,jpeg,png,mp4,mp3'],
        'message' => ['nullable', 'min:5', 'string'],
    ];

    public function addFile(): void
    {
        $this->additional_files++;
    }

    public function accept()
    {
        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                $media = $file->store('task-response', 'public');

                TaskResponse::create([
                    'task_id' => $this->user_task->id,
                    'file_id' => $media,
                ]);
            }
        }

        if (isset($this->message)) {
            TaskResponse::create([
                'task_id' => $this->user_task->id,
                'text' => $this->message,
            ]);
        }

        $telegram = new Telegram(config('telegram.tokens.main'));
        $taskService = new TaskService();
        $taskService->sendTaskResponse($this->user_task->id, $telegram);

        $this->dispatchBrowserEvent('taskAccepted', [
            'title' => 'Task accepted',
            'icon' => 'success',
            'iconColor' => 'green',
        ]);

        $this->emitUp('saved');

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.user-task.accept');
    }
}
