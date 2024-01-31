<?php

namespace App\Services;

use App\Models\BotUser;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonFiles;
use App\Models\Task;
use App\Models\TaskFiles;
use App\Models\TaskResponse;
use App\Models\Telegram;
use App\Models\UserCourse;
use App\Models\UserTask;
use App\Models\UserTaskFiles;
use App\Models\UserTaskLog;
use Illuminate\Support\Str;

class TaskService
{
    public function saveTaskToUser($user_id, $lesson_id, $telegram = null, $chat_id = null): void
    {
        $lesson = Lesson::where('id', $lesson_id)->where('active', 1)->first();
        $course = Course::where('id', $lesson->course_id)->where('active', 1)->first();
        $task = Task::where('lesson_id', $lesson_id)->where('active', 1)->first();
        $user_task = UserTask::where([
            ['user_id', '=', $user_id],
            ['course_id', '=', $course->id],
            ['lesson_id', '=', $lesson->id],
            ['task_id', '=', $task->id],
        ])->first();

        if (!$user_task) {
            UserTask::create([
                'user_id' => $user_id,
                'course_id' => $course->id,
                'lesson_id' => $lesson->id,
                'task_id' => $task->id,
            ]);

            if ($telegram != null and $chat_id != null) {
                $content = [
                    'chat_id' => $chat_id,
                    'text' => "Darsga yozildingiz:\n\n<b>Kurs:</b> " . $course->title . "\n<b>Dars:</b> " . $lesson->title,
                    'parse_mode' => 'html'
                ];

                $telegram->sendMessage($content);
            }
        }
    }

    public function saveTaskToLog(int $chat_id, int $id): void
    {
        $log = UserTaskLog::query()
            ->where('chat_id', $chat_id)->first();

        if ($log) {
            $log->update(['last_id' => $id]);
        } else {
            UserTaskLog::create([
                'chat_id' => $chat_id,
                'last_id' => $id
            ]);
        }
    }

    public function getUserTaskId(int $chat_id)
    {
        $log = UserTaskLog::query()
            ->where('chat_id', $chat_id)->first();

        if ($log) {
            return $log->last_id;
        }

        return 0;
    }

    public function saveUserTask(int $chat_id, string $text = null, string $file_id = null, int $type): void
    {
        $user = BotUser::where('chat_id', $chat_id)->first();
        $task_id = $this->getUserTaskId($chat_id);
        $user_task = UserTask::where([['user_id', $user->id], ['id', $task_id], ['status', 0]])->first();

        if ($user_task) {
            UserTaskFiles::create([
                'task_id' => $user_task->id,
                'file_id' => $file_id,
                'text' => $text,
                'task_type' => $type
            ]);
        }
    }

    public function updateUserTask(int $chat_id, int $status): void
    {
        $user = BotUser::where('chat_id', $chat_id)->first();
        $task_id = $this->getUserTaskId($chat_id);
        $user_task = UserTask::where([['user_id', $user->id], ['id', $task_id], ['status', 0]])->first();

        if ($user_task) {
            $user_task->update(['status' => $status]);
        }
    }

    public function sendTaskResponse(int $id, $telegram): void
    {
        $user_task = UserTask::where('id', $id)->where('status', 1)->first();
        $user = BotUser::where('id', $user_task->user_id)->first();
        $course = Course::where('id', $user_task->course_id)->where('active', 1)->first();
        $lesson = Lesson::where('id', $user_task->lesson_id)->where('active', 1)->first();

        if ($user_task) {
            $task_response = TaskResponse::where('task_id', $id)->get();
            if (count($task_response) > 0) {
                $content = [
                    'chat_id' => $user->chat_id,
                    'text' => "<b>" . $course->title . "</b> kursining <b>" . $lesson->title . "</b> darsining topshirgan vazifangiz bo'yicha ustozning javoblari ⬇️",
                    'parse_mode' => 'html'
                ];

                $telegram->sendMessage($content);

                foreach ($task_response as $response) {
                    if (isset($response->file_id)) {
                        $files = explode('.', $response->file_id);
                        $extension = end($files);

                        $content = [
                            'chat_id' => $user->chat_id,
                            'parse_mode' => 'html',
                            'protected_content' => true,
                        ];

                        switch ($extension) {
                            case 'jpg':
                            case 'jpeg':
                            case 'png':
                            case 'gif':
                                $content['photo'] = $this->makeFilePath($response->file_id);
                                $telegram->sendPhoto($content);
                                break;
                            case 'mp4':
                            case 'm4p':
                            case 'ogg':
                            case 'oga':
                            case 'mp3':
                                $content['audio'] = $this->makeFilePath($response->file_id);
                                $telegram->sendAudio($content);
                                break;
                            default:
                                $content['video'] = $this->makeFilePath($response->file_id);
                                $telegram->sendVideo($content);
                                break;
                        }
                    }

                    if (isset($response->text)) {
                        $content = [
                            'chat_id' => $user->chat_id,
                            'text' => $response->text,
                            'parse_mode' => 'html'
                        ];

                        $telegram->sendMessage($content);
                    }
                }
            }

            $user_task->update([
                'status' => 2
            ]);

            $this->updateUserLesson($user->id, $course->id, $lesson->id);

            $this->sendNewUserTask($user, $lesson, $telegram);
        }
    }

    public function updateUserLesson($user_id, $course_id, $lesson_id): void
    {
        $user_course = UserCourse::query()
            ->where([['user_id', $user_id], ['course_id', $course_id], ['lesson_id', $lesson_id], ['status', 1]])
            ->first();
        $user = BotUser::query()->where('id', $user_id)->first();

        if ($user_course) {
            $lesson = Lesson::query()
                ->where([['course_id', $course_id], ['id', '>', $lesson_id], ['active', 1]])
                ->first();
            if ($lesson) {
                $user_course->update([
                    'lesson_id' => $lesson->id
                ]);

                $telegram = new Telegram(config('telegram.tokens.main'));

                $content = [
                    'chat_id' => $user->chat_id,
                    'text' => "Navbatdagi <b>" . $lesson->title . "</b> darsiga o'tdingiz",
                    'parse_mode' => 'html'
                ];

                $telegram->sendMessage($content);

                if (isset($lesson->file)) {
                    $files = explode('.', $lesson->file);
                    $isImage = in_array(end($files), ['jpg', 'jpeg', 'png', 'gif']);

                    if ($isImage) {
                        $content = [
                            'chat_id' => $user->chat_id,
                            'photo' => $this->makeFilePath($lesson->file),
                            'parse_mode' => 'html',
                            'protect_content' => true,
                        ];

                        if (Str::length($lesson->description) > 1024) {
                            $content2 = [
                                'chat_id' => $user->chat_id,
                                'text' => $lesson->telegramDescription(),
                                'parse_mode' => 'html',
                                'protect_content' => true,
                            ];

                            $telegram->sendPhoto($content);
                            $telegram->sendMessage($content2);
                        } else {
                            $content['caption'] = $lesson->telegramDescription();
                            $telegram->sendPhoto($content);
                        }
                    } else {
                        $content = [
                            'chat_id' => $user->chat_id,
                            'video' => $this->makeFilePath($lesson->file),
                            'parse_mode' => 'html',
                            'protect_content' => true,
                        ];

                        if (Str::length($lesson->description) > 1024) {
                            $content2 = [
                                'chat_id' => $user->chat_id,
                                'text' => $lesson->telegramDescription(),
                                'parse_mode' => 'html',
                                'protect_content' => true,
                            ];

                            $telegram->sendVideo($content);
                            $telegram->sendMessage($content2);
                        } else {
                            $content['caption'] = $lesson->telegramDescription();
                            $telegram->sendVideo($content);
                        }
                    }
                } else {
                    $content = [
                        'chat_id' => $user->chat_id,
                        'text' => $lesson->telegramDescription(),
                        'parse_mode' => 'html',
                        'protect_content' => true,
                    ];
                    $telegram->sendMessage($content);
                }

                $lesson_files = LessonFiles::query()->where('lesson_id', $lesson->id)->where('active', 1)->get();
                foreach ($lesson_files as $file) {
                    $media = explode('.', $file->file);
                    $extension = end($media);

                    $content = [
                        'chat_id' => $user->chat_id,
                        'protect_content' => true,
                    ];

                    switch ($extension) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            $content['photo'] = $this->makeFilePath($file->file);
                            $telegram->sendPhoto($content);
                            break;
                        case 'mp4':
                        case 'mov':
                            $content['video'] = $this->makeFilePath($file->file);
                            $telegram->sendVideo($content);
                            break;
                        default:
                            $content['audio'] = $this->makeFilePath($file->file);
                            $telegram->sendAudio($content);
                            break;
                    }
                }
            }
        }
    }

    public function sendNewUserTask($user, $lesson, $telegram): void
    {
        $user_course = UserCourse::query()->where([['user_id', $user->id], ['course_id', $lesson->course_id], ['status', 1]])->first();
        if ($user_course) {
            $task = Task::query()->where([['lesson_id', $user_course->lesson_id], ['active', 1]])->first();
            if ($task) {
                $user_task = UserTask::query()->where([['user_id', $user->id], ['course_id', $user_course->course_id], ['lesson_id', $user_course->lesson_id], ['task_id', $task->id]])->first();
                if (!$user_task) {
                    $user_task = UserTask::create([
                        'user_id' => $user->id,
                        'course_id' => $user_course->course_id,
                        'lesson_id' => $user_course->lesson_id,
                        'task_id' => $task->id,
                    ]);
                }

                if (isset($task->file)) {
                    $files = explode('.', $task->file);
                    $isImage = in_array(end($files), ['jpg', 'jpeg', 'png', 'gif']);

                    if ($isImage) {
                        $content = [
                            'chat_id' => $user->chat_id,
                            'photo' => $this->makeFilePath($task->file),
                            'caption' => $task->telegramDescription(),
                            'parse_mode' => 'html',
                            'protect_content' => true,
                        ];

                        $telegram->sendPhoto($content);
                    } else {
                        $content = [
                            'chat_id' => $user->chat_id,
                            'video' => $this->makeFilePath($task->file),
                            'caption' => $task->telegramDescription(),
                            'parse_mode' => 'html',
                            'protect_content' => true,
                        ];

                        $telegram->sendVideo($content);
                    }
                }

                $task_files = TaskFiles::query()->where('task_id', $task->id)->where('active', 1)->get();
                foreach ($task_files as $file) {
                    $media = explode('.', $file->file);
                    $extension = end($media);

                    $content = [
                        'chat_id' => $user->chat_id,
                        'protect_content' => true,
                    ];

                    switch ($extension) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            $content['photo'] = $this->makeFilePath($file->file);
                            $telegram->sendPhoto($content);
                            break;
                        case 'mp4':
                        case 'mov':
                            $content['video'] = $this->makeFilePath($file->file);
                            $telegram->sendVideo($content);
                            break;
                        default:
                            $content['audio'] = $this->makeFilePath($file->file);
                            $telegram->sendAudio($content);
                            break;
                    }
                }

                $newLesson = Lesson::query()->where([['id', $user_course->lesson_id], ['active', 1]])->first();

                $action = new ActionService();
                $action->updateUserAction($user->chat_id, 4, 1);

                $final = [
                    'chat_id' => $user->chat_id,
                    'text' => "<b>" . $newLesson->title . "</b> darsi bo'yicha vazifangiz yuborildi. Bajarib ustozga vazifani topshiring!",
                    'parse_mode' => 'html',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            [['text' => config('telegram.keyboards.submit-task'), 'callback_data' => config('telegram.keyboards.submit-task') . '@' . $user_task->id]],
                        ],
                    ])
                ];

                $telegram->sendMessage($final);
            }
        }
    }

    private function makeFilePath($file): \CURLFile
    {
        return curl_file_create(config('telegram.urls.main') . '/storage/' . $file);
    }
}
