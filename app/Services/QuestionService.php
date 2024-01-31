<?php

namespace App\Services;

use App\Models\Keyboard;
use App\Models\Question;
use App\Models\Telegram;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionService
{
    public function sendQuestions(mixed $chat_id, Telegram $telegram): void
    {
        $reply_markup = array(
            'remove_keyboard' => true
        );
        $keyboard = json_encode($reply_markup);

        $question = Question::query()
            ->whereNotIn('id', function ($query) use ($chat_id) {
                $query->select('question_id')
                    ->from('user_answers')
                    ->where('chat_id', '=', $chat_id)
                    ->whereNotNull('answer');
            })->first();
        if (isset($question) and !empty($question)) {
            if ($question->keyboard_id != null) {
                $keyboard = $this->makeKeyboard($question->keyboard_id);
            }

            $content = [
                'chat_id' => $chat_id,
                'text' => $question->question,
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ];

            $this->checkUserQuestion($chat_id, $question->id);
        } else {
            $content = [
                'chat_id' => $chat_id,
                'text' => "🎉 Ro'yxatdan muvaffaqiyatli o'tdingiz!\n\nUstozni javoblarini kuting!",
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ];

            $action = new ActionService();
            $action->updateUserAction($chat_id, 0, 1);
        }

        $telegram->sendMessage($content);
    }

    private function makeKeyboard(mixed $keyboard_id): bool|string
    {
        $keyboard = Keyboard::query()->where('id', $keyboard_id)->first();
        $keyboard_type = null;
        switch ($keyboard->slug) {
            case ("location"):
                $keyboard_type = [
                    'text' => "📍 Manzil jo'natish",
                    'request_location' => true
                ];
                break;
            case ("phone-number"):
                $keyboard_type = [
                    'text' => "📞 Raqam jo'natish",
                    'request_contact' => true
                ];
                break;
        }

        return json_encode([
            'keyboard' => [
                [
                    $keyboard_type
                ],
            ],
            'resize_keyboard' => true
        ]);
    }

    public function setAnswer(mixed $chat_id, mixed $text): void
    {
        $answer = UserAnswer::where('chat_id', $chat_id)
            ->whereNull('answer')
            ->latest('id')
            ->first();
        if ($answer) {
            $answer->update(['answer' => $text]);
        }
    }

    private function checkUserQuestion(mixed $chat_id, mixed $question_id): void
    {
        $answer = UserAnswer::query()
            ->where([['chat_id', $chat_id], ['question_id', $question_id]])
            ->first();
        if (empty($answer) and !isset($answer)) {
            UserAnswer::create([
                'chat_id' => $chat_id,
                'question_id' => $question_id,
            ]);
        }
    }
}
