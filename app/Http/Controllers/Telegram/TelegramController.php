<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Telegram;
use App\Services\CommonMessageService;
use App\Services\QuestionService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Services\ActionService;

class TelegramController extends Controller
{
    public Telegram $telegram;
    public ActionService $action;
    public CommonMessageService $common;
    public int $step_1;
    public int $step_2;
    public int $chat_id;
    public ?string $text = null;
    public $type;
    public $data;
    public int $message_id;

    public function __construct(ActionService $action, CommonMessageService $common)
    {
        $this->telegram = new Telegram(config('telegram.tokens.main'));
        $this->type = $this->telegram->getUpdateType();
        $this->chat_id = $this->telegram->ChatID();
        $this->message_id = $this->telegram->MessageID();
        $this->text = $this->telegram->Text();
        $this->data = $this->telegram->getData();

        $this->common = $common;

        $this->action = $action;
        $action->saveUser($this->chat_id, $this->telegram->FirstName(), $this->telegram->Username());
        $this->step_1 = $action->checkUserAction($this->chat_id)['step_1'];
        $this->step_2 = $action->checkUserAction($this->chat_id)['step_2'];
    }

    public function index(): void
    {
        /**
         * Only check if text is equal to /start
         */
        if ($this->text == "/start") {
            if ($this->step_1 == 0 and $this->step_2 == 1) {
                $content = [
                    'chat_id' => $this->chat_id,
                    'text' => "❗️ Ro'yxatdan o'tib bo'lgansiz!\n\nUstozni javoblarini kuting!",
                    'parse_mode' => 'html',
                ];
                $this->telegram->sendMessage($content);
            }

            if ($this->step_1 > 0) {
                $this->common->sendStartMessage($this->telegram, $this->chat_id, 'Bosh sahifa!');
                $this->action->updateUserAction($this->chat_id, 1);
            }
        }

        /**
         * Back from step to - step
         */
        $backButton = config('telegram.keyboards.back');
        if ($this->text == $backButton || str_starts_with($this->text, "{$backButton}@")) {
            $data = explode('@', $this->text);
            $id = end($data);

            if (($this->step_1 == 2 || $this->step_1 == 3 || $this->step_1 == 4 || $this->step_1 == 5) && $this->step_2 == 0) {
                $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                $this->common->sendStartMessage($this->telegram, $this->chat_id, 'Bosh sahifa!');
                $this->action->updateUserAction($this->chat_id, 1);
            }

            if ($this->step_1 == 2) {
                if ($this->step_2 == 1) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendCoursesMessage($this->telegram, $this->chat_id);
                    $this->action->updateUserAction($this->chat_id, 2);
                }

                if ($this->step_2 == 2) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendOneCourseMessage($this->telegram, $this->chat_id, $id);
                    $this->action->updateUserAction($this->chat_id, 2, 1);
                }
            }

            if ($this->step_1 == 3) {
                if ($this->step_2 == 1) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendMyCoursesMessage($this->telegram, $this->chat_id);
                    $this->action->updateUserAction($this->chat_id, 3);
                }

                if ($this->step_2 == 2 || $this->step_2 == 4 || $this->step_2 == 5) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendMyOneCourseMessage($this->telegram, $this->chat_id, $id);
                    $this->action->updateUserAction($this->chat_id, 3, 1);
                }

                if ($this->step_2 == 3) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendMyCourseLessonsMessage($this->telegram, $this->chat_id, $id);
                    $this->action->updateUserAction($this->chat_id, 3, 2);
                }

                if ($this->step_2 == 6) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    if (isset($id)) {
                        $this->common->sendMyDoneTasksMessage($this->telegram, $this->chat_id, $id);
                        $this->action->updateUserAction($this->chat_id, 3, 5);
                    } else {
                        $this->common->sendMyCoursesMessage($this->telegram, $this->chat_id);
                        $this->action->updateUserAction($this->chat_id, 3);
                    }
                }
            }

            if ($this->step_1 == 4) {
                if ($this->step_2 == 1) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendMyTasksMessage($this->telegram, $this->chat_id);
                    $this->action->updateUserAction($this->chat_id, 4);
                }

                if ($this->step_2 == 2) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendOneTaskMessage($this->telegram, $this->chat_id, $id);
                    $this->action->updateUserAction($this->chat_id, 4, 1);
                }
            }

            return;
        }

        /**
         * Answer to question till questions end.
         */
        if ($this->step_1 == 0 and $this->step_2 == 0) {
            $questions = new QuestionService();

            if (isset($this->text) and $this->text != '/start') {
                $questions->setAnswer($this->chat_id, $this->text);
            }

            if ($this->telegram->getContactPhoneNumber() != '') {
                $phone_number = $this->telegram->getContactPhoneNumber();
                $questions->setAnswer($this->chat_id, $phone_number);
            }

            $questions->sendQuestions($this->chat_id, $this->telegram);
        }

        /**
         * If user step_1 less than 0
         */
        if ($this->step_1 < 0) {
            $content = [
                'chat_id' => $this->chat_id,
                'text' => "Siz botdan foydalana olamaysiz. Ustozni sizni bloklaganlar. Ustozga bilan bog'laning!",
                'parse_mode' => 'html',
            ];
            $this->telegram->sendMessage($content);
        }

        /**
         * User in main menu
         */
        if ($this->step_1 == 1 and $this->step_2 == 0) {
            $actions = [
                config('telegram.keyboards.courses') => ['method' => 'sendCoursesMessage', 'action' => 2],
                config('telegram.keyboards.my-courses') => ['method' => 'sendMyCoursesMessage', 'action' => 3],
                config('telegram.keyboards.my-tasks') => ['method' => 'sendMyTasksMessage', 'action' => 4],
                config('telegram.keyboards.contact-teacher') => ['method' => 'sendAppeal', 'action' => 5],
            ];

            if (isset($actions[$this->text])) {
                $action = $actions[$this->text];
                $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                $this->common->{$action['method']}($this->telegram, $this->chat_id);
                $this->action->updateUserAction($this->chat_id, $action['action']);
            }
        }

        /**
         * Courses section actions
         */
        if ($this->step_1 == 2) {
            if ($this->step_2 == 0 && is_numeric($this->text)) {
                $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                $this->common->sendOneCourseMessage($this->telegram, $this->chat_id, $this->text);
                $this->action->updateUserAction($this->chat_id, 2, 1);
            }

            if ($this->step_2 == 1) {
                $data = explode('@', $this->text);
                $text = $data[0];
                $course_id = end($data);

                if ($text == config('telegram.keyboards.lessons-list')) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendCourseLessonsMessage($this->telegram, $this->chat_id, $course_id);
                    $this->action->updateUserAction($this->chat_id, 2, 2);
                } else if ($text == config('telegram.keyboards.apply-course')) {
                    $this->common->sendAppliedCourseMessage($this->telegram, $this->chat_id, $course_id);
                }
            }

            if ($this->step_2 == 2 && is_numeric($this->text)) {
                $this->common->sendAppliedCourseMessage($this->telegram, $this->chat_id, $this->text);
            }
        }

        /**
         * My courses section actions
         */
        if ($this->step_1 == 3) {
            if ($this->step_2 == 0 && is_numeric($this->text)) {
                $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                $this->common->sendMyOneCourseMessage($this->telegram, $this->chat_id, $this->text);
                $this->action->updateUserAction($this->chat_id, 3, 1);
            }

            if ($this->step_2 == 1) {
                $data = explode('@', $this->text);
                $text = $data[0];
                $course_id = end($data);

                if ($text == config('telegram.keyboards.lessons-list')) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendMyCourseLessonsMessage($this->telegram, $this->chat_id, $course_id);
                    $this->action->updateUserAction($this->chat_id, 3, 2);
                }

                if ($text == config('telegram.keyboards.tasks')) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendMyTasksMessage($this->telegram, $this->chat_id, $course_id);
                    $this->action->updateUserAction($this->chat_id, 3, 4);
                }

                if ($text == config('telegram.keyboards.done-tasks')) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendMyDoneTasksMessage($this->telegram, $this->chat_id, $course_id);
                    $this->action->updateUserAction($this->chat_id, 3, 5);
                }
            }

            if ($this->step_2 == 2) {
                $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                if ($this->type == $this->telegram::CALLBACK_QUERY) {
                    if (is_numeric($this->text)) {
                        $this->common->sendMyLesson($this->telegram, $this->chat_id, $this->text);
                    } else {
                        $data = explode('@', $this->text);
                        $lesson_id = end($data);
                        $this->common->sendMyLesson($this->telegram, $this->chat_id, $lesson_id);
                    }
                    $this->action->updateUserAction($this->chat_id, 3, 3);
                }
            }

            if ($this->step_2 == 3) {
                $data = explode('@', $this->text);
                $text = $data[0];
                $task_id = end($data);

                if ($text == config('telegram.keyboards.submit-task')) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendYourTaskMessage($this->telegram, $this->chat_id, $task_id);
                    $this->action->updateUserAction($this->chat_id, 4, 2);
                }
            }

            if ($this->step_2 == 4 && is_numeric($this->text)) {
                $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                if ($this->type == $this->telegram::CALLBACK_QUERY) {
                    $this->common->sendOneTaskMessage($this->telegram, $this->chat_id, $this->text);
                    $this->action->updateUserAction($this->chat_id, 4, 1);
                }
            }

            if ($this->step_2 == 5) {
                $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                if ($this->type == $this->telegram::CALLBACK_QUERY) {
                    if (is_numeric($this->text)) {
                        $this->common->sendMyDoneTask($this->telegram, $this->chat_id, $this->text);
                    }
                    $this->action->updateUserAction($this->chat_id, 3, 6);
                }
            }
        }

        /**
         * My tasks section actions
         */
        if ($this->step_1 == 4) {
            if ($this->step_2 == 0 && is_numeric($this->text)) {
                $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                if ($this->type == $this->telegram::CALLBACK_QUERY) {
                    $this->common->sendOneTaskMessage($this->telegram, $this->chat_id, $this->text);
                    $this->action->updateUserAction($this->chat_id, 4, 1);
                }
            }

            if ($this->step_2 == 1) {
                $data = explode('@', $this->text);
                $text = $data[0];
                $task_id = end($data);

                if ($text == config('telegram.keyboards.submit-task')) {
                    $this->telegram->deleteMessage(['chat_id' => $this->chat_id, 'message_id' => $this->message_id]);
                    $this->common->sendYourTaskMessage($this->telegram, $this->chat_id, $task_id);
                    $this->action->updateUserAction($this->chat_id, 4, 2);
                }
            }

            if ($this->step_2 == 2) {
                $ontask = new TaskService();
                $task_id = $ontask->getUserTaskId($this->chat_id);

                if ($task_id > 0) {
                    $finish = true;
                    $file_id = null;
                    $caption = $this->data['message']['caption'] ?? null;
                    if (isset($this->text) && $this->text != "/start" && $this->text != config('telegram.keyboards.stop-submit')) {
                        $ontask->saveUserTask($this->chat_id, $this->text, null, 1);
                    } else if (isset($this->data['message']['photo'])) {
                        $photo = $this->data['message']['photo'];

                        if (isset($photo[2]['file_id'])) {
                            $file_id = $photo[2]['file_id'];
                        } elseif (isset($photo[1]['file_id'])) {
                            $file_id = $photo[1]['file_id'];
                        } else {
                            $file_id = $photo[0]['file_id'];
                        }

                        if ($file_id != null) {
                            $ontask->saveUserTask($this->chat_id, $caption, $file_id, 2);
                        }
                    } else if (isset($this->data['message']['voice'])) {
                        if (isset($this->data['message']['voice']['file_id'])) {
                            $file_id = $this->data['message']['voice']['file_id'];
                            $ontask->saveUserTask($this->chat_id, $caption, $file_id, 3);
                        }
                    } else if (isset($this->data['message']['audio'])) {
                        if (isset($this->data['message']['audio']['file_id'])) {
                            $file_id = $this->data['message']['audio']['file_id'];
                            $ontask->saveUserTask($this->chat_id, $caption, $file_id, 4);
                        }
                    } else {
                        $finish = false;
                    }

                    if ($finish) {
                        $this->common->sendFinishSubmitMessage($this->telegram, $this->chat_id);
                    } else {
                        if ($this->text == config('telegram.keyboards.stop-submit')) {
                            $this->common->sendSubmitFinishedMessage($this->telegram, $this->chat_id);
                            $this->common->sendMyTasksMessage($this->telegram, $this->chat_id);
                            $this->action->updateUserAction($this->chat_id, 4);
                        } else {
                            $this->telegram->sendMessage(['chat_id' => $this->chat_id, 'text' => "Vazifa yuborishda xatolik yuz berdi!"]);
                        }
                    }
                } else {
                    $this->telegram->sendMessage(['chat_id' => $this->chat_id, 'text' => "Vazifa yuborishda xatolik yuz berdi!"]);
                }
            }
        }
    }
}
