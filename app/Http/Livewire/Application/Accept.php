<?php

namespace App\Http\Livewire\Application;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Telegram;
use App\Models\UserAction;
use App\Models\UserCourse;
use App\Services\CommonMessageService;
use App\Services\TaskService;
use Illuminate\View\View;
use Livewire\Component;

class Accept extends Component
{
    public $user;
    public $course;
    public $lesson_id;
    public ?string $message = null;

    public function mount()
    {
        $this->course = Course::where('id', $this->course)->first();
    }

    public function accept(): void
    {
        $user_course = UserCourse::query()
            ->where('user_id', $this->user->id)
            ->where('course_id', $this->course->id)
            ->where('status', 0)
            ->first();

        $lessonQuery = Lesson::where('course_id', $this->course->id)->where('active', 1);
        $lessonId = $this->lesson_id ?? $lessonQuery->orderBy('id', 'asc')->value('id');

        $user_course->update([
            'lesson_id' => $lessonId,
            'status' => 1
        ]);

        $this->sendMessageUser();

        $taskService = new TaskService();
        $taskService->saveTaskToUser($this->user->id, $lessonId);

        $this->dispatchBrowserEvent('applicationAccepted', [
            'title' => ucfirst($this->user->first_name) . ' accepted to ' . $this->course->title,
            'icon' => 'success',
            'iconColor' => 'green',
        ]);

        $this->emitUp('saved');

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.application.accept');
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
            'text' => "🎉 Ustoz " . $this->course->title . " kursiga arizangizni qabul qildilar!\n\nKurslarim bo'limidan kirib ko'rishingiz mumkin.",
            'parse_mode' => 'html',
        ];
        $telegram->sendMessage($content);
    }
}
