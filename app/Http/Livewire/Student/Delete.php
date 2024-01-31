<?php

namespace App\Http\Livewire\Student;

use App\Models\BotUser;
use App\Models\Course;
use App\Models\Task;
use App\Models\TaskResponse;
use App\Models\Telegram;
use App\Models\UserCourse;
use App\Models\UserTask;
use Illuminate\View\View;
use Livewire\Component;

class Delete extends Component
{
    public ?UserCourse $course = null;
    public $user;
    public $course_id;
    public ?string $message = null;

    public function mount(): void
    {
        $this->user = BotUser::where('id', $this->course->user_id)->first();
        $this->course_id = Course::where('id', $this->course->course_id)->first();
    }

    public function delete(): void
    {
        $tasks = UserTask::where([['user_id', $this->user->id], ['course_id', $this->course->course_id]])->get();
        foreach ($tasks as $task) {
            $responses = TaskResponse::query()->where([['task_id', $task->id]])->get();
            foreach ($responses as $response) {
                if (isset($response->file) and storage_path('app/public/' . $response->file)) {
                    unlink(storage_path('app/public/' . $response->file));
                }
            }

            $task->delete();
        }

        $this->course->delete();

        $this->dispatchBrowserEvent('studentDeleted', [
            'title' => 'User course deleted',
            'icon' => 'warning',
            'iconColor' => 'red',
        ]);

        $this->sendMessageUser();

        $this->emitUp('saved');

        $this->reset();
    }

    private function sendMessageUser(): void
    {
        $telegram = new Telegram(config('telegram.tokens.main'));

        if ($this->message) {
            $content = [
                'chat_id' => $this?->user?->chat_id,
                'text' => $this->message,
                'parse_mode' => 'html',
            ];
            $telegram->sendMessage($content);
        }

        $content = [
            'chat_id' => $this?->user?->chat_id,
            'text' => "<b>" . $this->course_id->title . "</b> kursidan chetlatildingiz.",
            'parse_mode' => 'html',
        ];
        $telegram->sendMessage($content);
    }

    public function render(): View
    {
        return view('livewire.student.delete');
    }
}
