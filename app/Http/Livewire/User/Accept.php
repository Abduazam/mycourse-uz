<?php

namespace App\Http\Livewire\User;

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

    public $course_id = null;
    public $lesson_id = null;
    public $lessons;
    public ?string $message = null;
    public ?string $new_name = null;

    public function updatedCourseId($course_id): void
    {
        $this->lessons = Lesson::query()
            ->where('course_id', $course_id)
            ->where('active', 1)->get();
    }

    public function accept(): void
    {
        if ($this->course_id !== null) {
            $userCourseAttributes = [
                'user_id' => $this->user->id,
                'course_id' => $this->course_id,
            ];

            $lessonQuery = Lesson::where('course_id', $this->course_id)->where('active', 1);
            $lessonId = $this->lesson_id ?? $lessonQuery->orderBy('id', 'asc')->value('id');

            $userCourse = UserCourse::where('user_id', $this->user->id)
                ->where('course_id', $this->course_id)
                ->first();

            if ($userCourse !== null) {
                $userCourse->update(['lesson_id' => $lessonId]);
            } else {
                UserCourse::create(array_merge($userCourseAttributes, ['lesson_id' => $lessonId]));
            }

            $telegram = new Telegram(config('telegram.tokens.main'));
            $taskService = new TaskService();
            $taskService->saveTaskToUser($this->user->id, $lessonId, $telegram, $this->user->chat_id);
        }

        UserAction::where('chat_id', $this->user->chat_id)->update([
            'step_1' => 1,
            'step_2' => 0,
        ]);

        $this->sendMessageUser();

        if ($this->new_name != null) {
            $this->user->update([
                'first_name' => $this->new_name,
            ]);
        }

        $this->dispatchBrowserEvent('userAccepted', [
            'title' => ucfirst($this->user->first_name) . ' accepted',
            'icon' => 'success',
            'iconColor' => 'green',
        ]);

        $this->emitUp('saved');

        $this->reset();
    }

    public function render(): View
    {
        $courses = Course::query()->where('active', '=', 1)->get();
        return view('livewire.user.accept', compact('courses'));
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

        $common = new CommonMessageService();
        $common->sendStartMessage($telegram, $this?->user?->chat_id, "🎉 Ustoz tomonidan tasdiqlandingiz!\n\nBotdan foydalanishingiz mumkin.");
    }
}
