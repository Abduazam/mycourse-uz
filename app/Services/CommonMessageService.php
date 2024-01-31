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
use App\Models\UserMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommonMessageService
{
    public function sendStartMessage(Telegram $telegram, mixed $chat_id, string $message, bool $is_admin = false): void
    {
        $content = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'html',
        ];

        if (!$is_admin) {
            $keyboard = json_encode([
                'inline_keyboard' => [
                    [['text' => config('telegram.keyboards.courses'), 'callback_data' => config('telegram.keyboards.courses')], ['text' => config('telegram.keyboards.my-courses'), 'callback_data' => config('telegram.keyboards.my-courses')]],
                    [['text' => config('telegram.keyboards.my-tasks'), 'callback_data' => config('telegram.keyboards.my-tasks')]],
                    [['text' => config('telegram.keyboards.contact-teacher'), 'callback_data' => config('telegram.keyboards.contact-teacher')]],
                ],
            ]);

            $content['reply_markup'] = $keyboard;
        }

        $telegram->sendMessage($content);
    }

    public function sendAppeal(Telegram $telegram, mixed $chat_id): void
    {
        $content = [
            'chat_id' => $chat_id,
            'text' => "Ustozga ushbu @hilalarabic_chat_bot bot orqali murojaat qilishingiz mumkin!",
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]],
                ],
            ])
        ];

        $telegram->sendMessage($content);
    }

    public function sendCoursesMessage(Telegram $telegram, mixed $chat_id): void
    {
        $courses = Course::query()->where('active', 1)->get();

        if (count($courses) > 0) {
            $ready = $this->makeDataKeyboard($courses, "📃 Kurslar ro'yxati");

            $content = [
                'chat_id' => $chat_id,
                'text' => $ready['message'],
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $ready['keyboard'],
                ])
            ];
        } else {
            $content = [
                'chat_id' => $chat_id,
                'text' => "Kurslar ro'yxati bo'sh 🗑",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]],
                    ],
                ])
            ];
        }

        $telegram->sendMessage($content);
    }

    public function sendOneCourseMessage(Telegram $telegram, mixed $chat_id, int $course_id): void
    {
        $course = Course::query()->where('id', $course_id)->where('active', 1)->first();
        $lessons = Lesson::query()->where('course_id', $course->id)->where('active', 1)->get();
        $user = BotUser::query()->where('chat_id', $chat_id)->first();
        $is_student = UserCourse::query()->where('user_id', $user->id)->where('course_id', $course->id)->where('status', 1)->first();

        $keyboard_array = [];
        if (count($lessons) > 0) {
            $keyboard_array[] = [['text' => config('telegram.keyboards.lessons-list'), 'callback_data' => config('telegram.keyboards.lessons-list') . '@' . $course->id]];
        }
        if (!$is_student) {
            $keyboard_array[] = [['text' => config('telegram.keyboards.apply-course'), 'callback_data' => config('telegram.keyboards.apply-course') . '@' . $course->id]];
        }
        $keyboard_array[] = [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]];

        $keyboard = json_encode([
            'inline_keyboard' => $keyboard_array,
        ]);

        if ($course) {
            if (isset($course->file)) {
                $files = explode('.', $course->file);
                if (in_array(end($files), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $content = [
                        'chat_id' => $chat_id,
                        'photo' => $this->makeFilePath($course->file),
                        'caption' => $course->telegramDescription(1024),
                        'parse_mode' => 'html',
                        'protect_content' => true,
                        'reply_markup' => $keyboard,
                    ];

                    $telegram->sendPhoto($content);
                } else {
                    $content = [
                        'chat_id' => $chat_id,
                        'video' => $this->makeFilePath($course->file),
                        'caption' => $course->telegramDescription(1024),
                        'parse_mode' => 'html',
                        'protect_content' => true,
                        'reply_markup' => $keyboard,
                    ];

                    $telegram->sendVideo($content);
                }
            } else {
                $content = [
                    'chat_id' => $chat_id,
                    'text' => $course->telegramDescription(),
                    'parse_mode' => 'html',
                    'reply_markup' => $keyboard,
                ];
            }
        } else {
            $content = [
                'chat_id' => $chat_id,
                'text' => "Kurs topilmadi ❗️",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]],
                    ],
                ]),
            ];
        }

        if (!isset($course->file)) {
            $telegram->sendMessage($content);
        }
    }

    public function sendCourseLessonsMessage(Telegram $telegram, mixed $chat_id, int $course_id): void
    {
        $course = Course::query()->where('id', $course_id)->where('active', 1)->first();
        $lessons = Lesson::query()->where('course_id', $course_id)->where('active', 1)->get();

        $message = "📃 " . $course->title . " kursining darslar ro'yxati: \n\n";

        foreach ($lessons as $lesson) {
            $message .= "<b><i>" . $lesson->title . "</i></b>\n";
        }

        $keyboard = json_encode([
            'inline_keyboard' => [
                [['text' => config('telegram.keyboards.apply-course'), 'callback_data' => $course_id]],
                [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back') . "@" . $course_id]],
            ],
        ]);

        $content = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'html',
            'reply_markup' => $keyboard,
        ];

        $telegram->sendMessage($content);
    }

    public function sendAppliedCourseMessage(Telegram $telegram, mixed $chat_id, int $course_id): void {
        $course = Course::query()->where('id', $course_id)->where('active', 1)->first();

        if ($course) {
            $user = BotUser::query()->where('chat_id', $chat_id)->first();

            $user_course = UserCourse::query()->where('user_id', $user->id)->where('course_id', $course->id)->first();
            if ($user_course) {
                if ($user_course->status == 1) {
                    $content = [
                        'chat_id' => $chat_id,
                        'text' => "Kursda o'qiyapsiz!\n<b>" . config('telegram.keyboards.my-courses') . "</b> bo'limidan ushbu kursni ko'rishingiz mumkin.",
                        'parse_mode' => 'html',
                    ];
                } else {
                    $content = [
                        'chat_id' => $chat_id,
                        'text' => "🤷🏻‍♂️ Kursga yozilib bo'lgansiz!\nUstozni javoblarini kuting",
                        'parse_mode' => 'html',
                    ];
                }

            } else {
                UserCourse::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'status' => 0
                ]);

                $content = [
                    'chat_id' => $chat_id,
                    'text' => "🎉 Kursga yozildingiz!\nUstozni javoblarini kuting",
                    'parse_mode' => 'html',
                ];
            }
        } else {
            $content = [
                'chat_id' => $chat_id,
                'text' => "❗️ Xatolik yuz berdi. Kursga yozilmadingiz!\nQaytadan urinib ko'ring",
                'parse_mode' => 'html',
            ];
        }

        $telegram->sendMessage($content);
    }

    public function sendMyCoursesMessage(Telegram $telegram, mixed $chat_id): void
    {
        $user = BotUser::query()->where('chat_id', $chat_id)->first();

        $courses = Course::query()->where('active', 1)
            ->whereIn('id', function ($query) use ($user) {
                $query->select('course_id')
                    ->from('user_courses')
                    ->where('user_id', '=', $user->id)
                    ->where('status', '=', 1);
            })->get();

        if (count($courses) > 0) {
            $ready = $this->makeDataKeyboard($courses, "📖 Kurslarim ro'yxati");

            $content = [
                'chat_id' => $chat_id,
                'text' => $ready['message'],
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $ready['keyboard'],
                ])
            ];
        } else {
            $content = [
                'chat_id' => $chat_id,
                'text' => "Kurslarim ro'yxati bo'sh 🗑",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]],
                    ],
                ])
            ];
        }

        $telegram->sendMessage($content);
    }

    public function sendMyOneCourseMessage(Telegram $telegram, mixed $chat_id, int $course_id): void
    {
        $user = BotUser::query()->where('chat_id', $chat_id)->first();

        $course = Course::query()->where('id', $course_id)
            ->where('active', 1)
            ->whereIn('id', function ($query) use ($user, $course_id) {
                $query->select('course_id')
                    ->from('user_courses')
                    ->where('user_id', '=', $user->id)
                    ->where('course_id', '=', $course_id)
                    ->where('status', '=', 1);
            })
            ->first();

        if ($course) {
            $keyboard = json_encode([
                'inline_keyboard' => [
                    [['text' => config('telegram.keyboards.lessons-list'), 'callback_data' => config('telegram.keyboards.lessons-list') . '@' . $course->id]],
                    [['text' => config('telegram.keyboards.tasks'), 'callback_data' => config('telegram.keyboards.tasks') . '@' . $course->id], ['text' => config('telegram.keyboards.done-tasks'), 'callback_data' => config('telegram.keyboards.done-tasks') . '@' . $course->id]],
                    [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]],
                ],
            ]);

            if (isset($course->file)) {
                $files = explode('.', $course->file);
                if (in_array(end($files), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $content = [
                        'chat_id' => $chat_id,
                        'photo' => $this->makeFilePath($course->file),
                        'caption' => $course->telegramDescription(1024),
                        'parse_mode' => 'html',
                        'protect_content' => true,
                        'reply_markup' => $keyboard,
                    ];

                    $telegram->sendPhoto($content);
                } else {
                    $content = [
                        'chat_id' => $chat_id,
                        'video' => $this->makeFilePath($course->file),
                        'caption' => $course->telegramDescription(1024),
                        'parse_mode' => 'html',
                        'protect_content' => true,
                        'reply_markup' => $keyboard,
                    ];

                    $telegram->sendVideo($content);
                }
            } else {
                $content = [
                    'chat_id' => $chat_id,
                    'text' => $course->telegramDescription(),
                    'parse_mode' => 'html',
                    'reply_markup' => $keyboard,
                ];
            }
        } else {
            $content = [
                'chat_id' => $chat_id,
                'text' => "Kurs topilmadi ❗️",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]],
                    ],
                ]),
            ];
        }

        if (!isset($course->file)) {
            $telegram->sendMessage($content);
        }
    }

    public function sendMyCourseLessonsMessage(Telegram $telegram, mixed $chat_id, int $course_id): void
    {
        $user = BotUser::query()->where('chat_id', $chat_id)->first();
        $course = Course::query()->where('id', $course_id)->where('active', 1)->first();
        $lessons = Lesson::query()->where('course_id', $course_id)->where('active', 1)->get();
        $my_lessons = Lesson::query()->where('course_id', $course_id)->where('active', 1)
            ->where('id', '<=', function ($query) use ($user, $course_id) {
                $query->select('lesson_id')
                    ->from('user_courses')
                    ->where('user_id', $user->id)
                    ->where('course_id', $course_id);
            })->get();
        $lastLesson = $my_lessons->last();

        $ready = $this->makeDataKeyboard($my_lessons);

        $message = "📃 " . $course->title . " kursining darslar ro'yxati: \n\n";

        $i = 1;
        foreach ($lessons as $lesson) {
            $message .= $i . ". <b><i>" . $lesson->title . "</i></b>\n";
            $i++;
        }

        $keyboard = $ready['keyboard'];
        $keyboard[] = [['text' => config('telegram.keyboards.current-lesson'), 'callback_data' => config('telegram.keyboards.current-lesson') . "@" . $lastLesson->id]];
        $keyboard[] = [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back') . "@" . $course_id]];
        $keyboard = json_encode([
            'inline_keyboard' => $keyboard
        ]);

        $content = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ];

        $telegram->sendMessage($content);
    }

    public function sendMyLesson(Telegram $telegram, mixed $chat_id, int $lesson_id): void
    {
        $user = BotUser::query()->where('chat_id', $chat_id)->first();
        $lesson = Lesson::select('lessons.*')
            ->join('user_courses', function ($join) use ($user) {
                $join->on('lessons.course_id', '=', 'user_courses.course_id')
                    ->where('user_courses.user_id', '=', $user->id)
                    ->where('user_courses.status', '=', 1);
            })
            ->where('lessons.id', '<=', DB::raw('user_courses.lesson_id'))
            ->where('lessons.id', $lesson_id)
            ->where('lessons.active', 1)
            ->first();

        if ($lesson) {
            if (isset($lesson->file)) {
                $files = explode('.', $lesson->file);
                $isImage = in_array(end($files), ['jpg', 'jpeg', 'png', 'gif']);

                if ($isImage) {
                    $content = [
                        'chat_id' => $chat_id,
                        'photo' => $this->makeFilePath($lesson->file),
                        'parse_mode' => 'html',
                        'protect_content' => true,
                    ];

                    if (Str::length($lesson->description) > 1024) {
                        $content2 = [
                            'chat_id' => $chat_id,
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
                        'chat_id' => $chat_id,
                        'video' => $this->makeFilePath($lesson->file),
                        'parse_mode' => 'html',
                        'protect_content' => true,
                    ];

                    if (Str::length($lesson->description) > 1024) {
                        $content2 = [
                            'chat_id' => $chat_id,
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
                    'chat_id' => $chat_id,
                    'text' => $lesson->telegramDescription(),
                    'parse_mode' => 'html',
                    'protect_content' => true,
                ];
                $telegram->sendMessage($content);
            }

            $lesson_files = LessonFiles::query()->where('lesson_id', $lesson_id)->where('active', 1)->get();
            if (count($lesson_files) > 0) {
                foreach ($lesson_files as $file) {
                    $media = explode('.', $file->file);
                    $extension = end($media);

                    $content = [
                        'chat_id' => $chat_id,
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

            $task = Task::query()->where('lesson_id', $lesson_id)->where('active', 1)->first();
            if ($task) {
                if (isset($task->file)) {
                    $files = explode('.', $task->file);
                    $isImage = in_array(end($files), ['jpg', 'jpeg', 'png', 'gif']);

                    if ($isImage) {
                        $content = [
                            'chat_id' => $chat_id,
                            'photo' => $this->makeFilePath($task->file),
                            'caption' => $task->telegramDescription(),
                            'parse_mode' => 'html',
                            'protect_content' => true,
                        ];

                        $telegram->sendPhoto($content);
                    } else {
                        $content = [
                            'chat_id' => $chat_id,
                            'video' => $this->makeFilePath($task->file),
                            'caption' => $task->telegramDescription(),
                            'parse_mode' => 'html',
                            'protect_content' => true,
                        ];

                        $telegram->sendVideo($content);
                    }
                }

                $task_files = TaskFiles::query()->where('task_id', $task->id)->where('active', 1)->get();
                if (count($task_files) > 0) {
                    foreach ($task_files as $file) {
                        $media = explode('.', $file->file);
                        $extension = end($media);

                        $content = [
                            'chat_id' => $chat_id,
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

            $user_task = false;
            if ($task) {
                $user_task = $this->checkUserTaskStatus($user->id, $lesson->id, $task->id);
            }

            $keyboard = [];
            $message = "<b>" . $lesson->title . "</b> darsining barcha fayllari qabul qildingiz.";
            if ($user_task) {
                $usertask = UserTask::query()->where([['user_id', $user->id], ['lesson_id', $lesson->id], ['task_id', $task->id]])->first();
                $keyboard[] = [['text' => config('telegram.keyboards.submit-task'), 'callback_data' => config('telegram.keyboards.submit-task') . "@" . $usertask->id]];
                $message .= " Ushbu darsning vazifasini bajarib, ustozga yuboring!";
            }
            $keyboard[] = [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back') . "@" . $lesson->course_id]];

            $final = [
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard
                ]),
            ];

            $telegram->sendMessage($final);
        } else {
            $content = [
                'chat_id' => $chat_id,
                'text' => "Darslik topilmadi ❗️",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]],
                    ],
                ])
            ];
            $telegram->sendMessage($content);
        }
    }

    public function sendMyTasksMessage(Telegram $telegram, mixed $chat_id, int $course_id = null): void
    {
        $user = BotUser::query()->where('chat_id', $chat_id)->first();

        $tasks = UserTask::query()
            ->select('user_tasks.*', 'lessons.title')
            ->join('lessons', 'lessons.id', '=', 'user_tasks.lesson_id')
            ->when(!is_null($course_id), function ($query) use ($course_id) {
                return $query->where('user_tasks.course_id', $course_id);
            })
            ->where('user_id', $user->id)
            ->where('status', '=', 0)
            ->get();

        if (count($tasks) > 0) {
            $ready = $this->makeDataKeyboard($tasks, "📝 Vazifalarim ro'yxati", $course_id);

            $content = [
                'chat_id' => $chat_id,
                'text' => $ready['message'],
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $ready['keyboard'],
                ])
            ];
        } else {
            if ($course_id != null) {
                $callback_data = config('telegram.keyboards.back') . "@" . $course_id;
            } else {
                $callback_data = config('telegram.keyboards.back');
            }

            $content = [
                'chat_id' => $chat_id,
                'text' => "Vazifalarim ro'yxati bo'sh 🗑",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => $callback_data]]
                    ],
                ])
            ];
        }

        $telegram->sendMessage($content);
    }

    public function sendOneTaskMessage(Telegram $telegram, mixed $chat_id, int $task_id): void
    {
        $user = BotUser::query()->where('chat_id', $chat_id)->first();
        $task = UserTask::query()
            ->select('tasks.*')
            ->join('tasks', 'tasks.id', '=', 'user_tasks.task_id')
            ->where([['user_tasks.user_id', $user->id], ['user_tasks.id', $task_id], ['user_tasks.status', 0]])
            ->first();
        $lesson = Lesson::query()->where('id', $task->lesson_id)->where('active', 1)->first();

        $keyboard = json_encode([
            'inline_keyboard' => [
                [['text' => config('telegram.keyboards.submit-task'), 'callback_data' => config('telegram.keyboards.submit-task') . '@' . $task_id]],
                [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]],
            ],
        ]);

        if ($task) {
            if (isset($task->file)) {
                $files = explode('.', $task->file);
                if (in_array(end($files), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $content = [
                        'chat_id' => $chat_id,
                        'photo' => $this->makeFilePath($task->file),
                        'caption' => $task->telegramDescription(1024),
                        'parse_mode' => 'html',
                        'protect_content' => true,
                    ];

                    $telegram->sendPhoto($content);
                } else {
                    $content = [
                        'chat_id' => $chat_id,
                        'video' => $this->makeFilePath($task->file),
                        'caption' => $task->telegramDescription(1024),
                        'parse_mode' => 'html',
                        'protect_content' => true,
                    ];

                    $telegram->sendVideo($content);
                }
            } else {
                $content = [
                    'chat_id' => $chat_id,
                    'text' => $task->telegramDescription(),
                    'parse_mode' => 'html',
                    'reply_markup' => $keyboard,
                ];

                $telegram->sendMessage($content);
            }

            $task_files = TaskFiles::query()
                ->where('task_id', $task->id)->where('active', 1)
                ->get();
            if (count($task_files) > 0) {
                foreach ($task_files as $file) {
                    $media = explode('.', $file->file);
                    $extension = end($media);

                    $content = [
                        'chat_id' => $chat_id,
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

            $final = [
                'chat_id' => $chat_id,
                'text' => "<b>" . $lesson->title . "</b> darsi bo'yicha vazifangiz yuborildi. Bajarib ustozga vazifani topshiring!",
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ];

            $telegram->sendMessage($final);
        } else {
            $content = [
                'chat_id' => $chat_id,
                'text' => "Vazifa topilmadi ❗️",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]],
                    ],
                ]),
            ];

            $telegram->sendMessage($content);
        }
    }

    public function sendYourTaskMessage(Telegram $telegram, mixed $chat_id, int $task_id): void
    {
        $user = BotUser::query()->where('chat_id', $chat_id)->first();
        $task = UserTask::query()
            ->where([['id', $task_id], ['user_id', $user->id], ['status', 0]])
            ->first();
        $lesson = Lesson::query()->where('id', $task->lesson_id)->where('active', 1)->first();

        if ($task) {
            $message = "<b>" . $lesson->title . "</b> darsining vazifasini yuboring!";
            $ontask = new TaskService();
            $ontask->saveTaskToLog($chat_id, $task->id);
        } else {
            $message = "<b>" . $lesson->title . "</b> darsining vazifasini topshirishda xatolik yuz berdi!";
        }

        $content = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back') . "@" . $task_id]],
                ],
            ]),
        ];

        $telegram->sendMessage($content);
    }

    public function sendFinishSubmitMessage(Telegram $telegram, mixed $chat_id): void
    {
        $replyMarkup = array(
            'keyboard' => array(
                array(config('telegram.keyboards.stop-submit'))
            ),
            'resize_keyboard' => true
        );
        $keyboard = json_encode($replyMarkup);

        $content = [
            'chat_id' => $chat_id,
            'text' => "Vazifa yuborildi. Yana bo'lsa yuklayvering.\n\nVazifa yuborishni to'xtatmoqchi bo'lsangiz <b>" . config('telegram.keyboards.stop-submit') . "</b> tugmani bosing!",
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ];

        $telegram->sendMessage($content);
    }

    public function sendSubmitFinishedMessage(Telegram $telegram, mixed $chat_id): void
    {
        $ontask = new TaskService();
        $ontask->updateUserTask($chat_id, 1);

        $replyMarkup = array('remove_keyboard' => true);
        $keyboard = json_encode($replyMarkup);

        $content = [
            'chat_id' => $chat_id,
            'text' => "Vazifalaringiz ustozga yuborildi. Ustozni javoblarini kuting",
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ];

        $telegram->sendMessage($content);
    }

    public function sendMyDoneTasksMessage(Telegram $telegram, mixed $chat_id, int $course_id): void
    {
        $user = BotUser::query()->where('chat_id', $chat_id)->first();

        $tasks = UserTask::query()
            ->select('user_tasks.*', 'lessons.title')
            ->join('lessons', 'lessons.id', '=', 'user_tasks.lesson_id')
            ->where('user_tasks.course_id', $course_id)
            ->where('user_tasks.user_id', $user->id)
            ->where('user_tasks.status', '!=', 0)
            ->get();

        if (count($tasks) > 0) {
            $ready = $this->makeDataKeyboard($tasks, "📝 Topshirgan vazifalarim ro'yxati", $course_id);

            $content = [
                'chat_id' => $chat_id,
                'text' => $ready['message'],
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $ready['keyboard'],
                ])
            ];
        } else {
            if ($course_id != null) {
                $callback_data = config('telegram.keyboards.back') . "@" . $course_id;
            } else {
                $callback_data = config('telegram.keyboards.back');
            }

            $content = [
                'chat_id' => $chat_id,
                'text' => "Topshirgan vazifalarim ro'yxati bo'sh 🗑",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => $callback_data]]
                    ],
                ])
            ];
        }

        $telegram->sendMessage($content);
    }

    public function sendMyDoneTask(Telegram $telegram, mixed $chat_id, int $task_id): void
    {
        $user = BotUser::query()->where('chat_id', $chat_id)->first();
        $user_task = UserTask::query()
            ->select('tasks.*')
            ->join('tasks', 'user_tasks.task_id', '=', 'tasks.id')
            ->where([['user_tasks.id', $task_id], ['user_tasks.user_id', $user->id], ['user_tasks.status', '!=', 0]])
            ->first();

        if ($user_task) {
            $task_files = UserTaskFiles::query()
                ->where('task_id', $task_id)->where('status', 1)
                ->get();

            $lesson = Lesson::query()->where([['id', $user_task->lesson_id], ['active', 1]])->first();

            if (count($task_files) > 0) {
                $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $lesson->title . " dars bo'yicha yuborgan vazifalaringiz ⬇️", 'parse_mode' => 'html']);
                foreach ($task_files as $file) {
                    switch ($file->task_type) {
                        case 1:
                            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $file->text, 'parse_mode' => 'html']);
                            break;

                        case 2:
                            $telegram->sendPhoto(['chat_id' => $chat_id, 'photo' => $file->file_id, 'caption' => $file->text, 'parse_mode' => 'html']);
                            break;

                        case 3:
                            $telegram->sendVoice(['chat_id' => $chat_id, 'voice' => $file->file_id, 'caption' => $file->text, 'parse_mode' => 'html']);
                            break;

                        case 4:
                            $telegram->sendAudio(['chat_id' => $chat_id, 'audio' => $file->file_id, 'caption' => $file->text, 'parse_mode' => 'html']);
                            break;
                    }
                }
            }

            $task_response = TaskResponse::query()
                ->where('task_id', $task_id)->where('status', 1)
                ->get();

            if (count($task_response) > 0) {
                $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Ustozning yuborgan vazifangiz bo'yicha javoblari ⬇️", 'parse_mode' => 'html']);
                foreach ($task_response as $response) {
                    if (isset($response->file_id)) {
                        $files = explode('.', $response->file_id);
                        $extension = end($files);

                        $content = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'html',
                            'protect_content' => true,
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

            $content = [
                'chat_id' => $chat_id,
                'text' => "Barcha fayllaringiz yuborildi!",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back') . "@" . $lesson->course_id]]
                    ],
                ])
            ];
        } else {
            $content = [
                'chat_id' => $chat_id,
                'text' => "Vazifalarim ro'yxati bo'sh 🗑",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]]
                    ],
                ])
            ];
        }

        $telegram->sendMessage($content);
    }

    private function makeDataKeyboard($data, string $title = null, int $id = null): array
    {
        $length = count($data);

        $i = 1;
        $keyboard = [];
        $array = [];
        $message = $title . ":\n\n";

        foreach ($data as $item) {
            $array[] = ['text' => $i, 'callback_data' => $item->id];
            $message .= $i . ". " . $item->title . "\n";

            if ($length % 2 == 0) {
                if ($length % 5 == 0) {
                    if ($i % 5 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 3 == 0) {
                    if ($i % 3 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 4 == 0) {
                    if ($i % 4 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 7 == 0) {
                    if ($i % 7 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 6 == 0) {
                    if ($i % 6 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 10 == 0) {
                    if ($i % 10 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 8 == 0) {
                    if ($i % 8 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 9 == 0) {
                    if ($i % 9 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 2 == 0) {
                    if ($i % 2 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                }
            } else {
                if ($length % 3 == 0) {
                    if ($i % 3 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 5 == 0) {
                    if ($i % 5 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 7 == 0) {
                    if ($i % 7 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else if ($length % 9 == 0) {
                    if ($i % 9 == 0) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                } else {
                    if ($i % 2 == 1) {
                        $keyboard[] = $array;
                        $array = [];
                    }
                }
            }

            $i++;
        }

        if ($title != null) {
            if ($id != null) {
                $keyboard[] = [['text'=> config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back') . "@" . $id]];
            } else {
                $keyboard[] = [['text'=> config('telegram.keyboards.back'), 'callback_data' => config('telegram.keyboards.back')]];
            }
        }

        return [
            'message' => $message,
            'keyboard' => $keyboard,
        ];
    }

    private function makeFilePath($file): \CURLFile
    {
        return curl_file_create(config('telegram.urls.main') . '/storage/' . $file);
    }

    private function checkUserTaskStatus($user_id, $lesson_id, $task_id): bool
    {
        $user_task = UserTask::query()
            ->where([
                ['user_id', $user_id], ['lesson_id', $lesson_id], ['task_id', $task_id]
            ])->first();

        if ($user_task) {
            if ($user_task->status == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function sendTestMedia(Telegram $telegram, mixed $chat_id): void
    {
        $lesson = Lesson::query()->where('id', 4)->first();
        $lesson_files = LessonFiles::query()->where('lesson_id', $lesson->id)->get();

        $files = [];
        if ($lesson) {
            $file = new \CURLFile(config('telegram.urls.main') . '/storage/' . $lesson->file);
            $media = explode('.', $lesson->file);
            if (in_array(end($media), ['jpg', 'jpeg', 'png', 'gif'])) {
                $files[] = ['type' => 'photo', 'media' => $file];
            } else {
                $files[] = ['type' => 'video', 'media' => $file];
            }

            $path_file = config('telegram.urls.main') . '/storage/' . $lesson->file;

            $content = [
                'chat_id' => $chat_id,
                'video' => $path_file
            ];
            $telegram->sendVideo($content);

            $content = [
                'chat_id' => $chat_id,
                'text' => $path_file
            ];
            $telegram->sendMessage($content);
        }

        $content = [
            'chat_id' => $chat_id,
            'media' => json_encode($files),
        ];
        $telegram->sendMediaGroup($content);

//        if (count($lesson_files) > 0) {
//            foreach ($lesson_files as $lfile) {
//                $file = new \CURLFile(config('telegram.urls.main') . '/storage/' . $lfile->file);
//                $media = explode('.', $lfile->file);
//                if (in_array(end($media), ['jpg', 'jpeg', 'png', 'gif'])) {
//                    $files[] = ['type' => 'photo', 'media' => $file];
//                } else {
//                    $files[] = ['type' => 'video', 'media' => $file];
//                }
//            }
//        }
    }
}
