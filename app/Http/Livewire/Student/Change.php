<?php

namespace App\Http\Livewire\Student;

use App\Models\BotUser;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Telegram;
use App\Models\UserCourse;
use App\Services\TaskService;
use Illuminate\View\View;
use Livewire\Component;

class Change extends Component
{
    public $course;

    public $course_id;
    public $user;
    public ?int $lesson_id = null;
    public ?string $message = null;

    public function mount(): void
    {
        $this->lesson_id = $this->course->lesson_id;
        $this->course_id = Course::where('id', $this->course->course_id)->first();
        $this->user = BotUser::where('id', $this->course->user_id)->first();
    }

    public function change(): void
    {
        if ($this->lesson_id !== null) {
            $this->course->update([
                'lesson_id' => $this->lesson_id
            ]);
        }

        $this->sendMessageUser();

        $taskService = new TaskService();
        $taskService->saveTaskToUser($this->user->id, $this->lesson_id);

        $this->dispatchBrowserEvent('userChanged', [
            'title' => ucfirst($this->user->first_name) . "'s' lesson changed",
            'icon' => 'success',
            'iconColor' => 'green',
        ]);

        $this->emitUp('saved');

//        $this->reset();
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

        $lesson = Lesson::where('id', $this->lesson_id)->first();

        $content = [
            'chat_id' => $this?->user?->chat_id,
            'text' => "<b>" . $this->course_id->title . "</b> kursi bo'yicha darsingiz <i><u>" . $lesson->title . "</u></i> ga o'zgardi.",
            'parse_mode' => 'html',
        ];
        $telegram->sendMessage($content);
    }

    public function render(): View
    {
        $lessons = Lesson::query()
            ->where('course_id', $this->course->course_id)
            ->where('active', 1)
            ->get();

        return view('livewire.student.change', compact('lessons'));
    }
}
