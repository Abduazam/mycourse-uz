<?php

namespace App\Services;

use App\Models\BotUser;
use App\Models\UserAction;

class ActionService
{
    public function checkUserAction(mixed $chat_id): array
    {
        $user = UserAction::query()->where('chat_id', $chat_id)->first();
        if (isset($user) and !empty($user)) {
            return [
                'step_1' => $user->step_1,
                'step_2' => $user->step_2,
            ];
        } else {
            $this->updateUserAction($chat_id, 0, 0);
        }

        return [
            'step_1' => 0,
            'step_2' => 0
        ];
    }

    public function updateUserAction(mixed $chat_id, int $step_1 = 0, int $step_2 = 0): void
    {
        $user = UserAction::query()->where('chat_id', $chat_id)->first();
        if (isset($user) and !empty($user)) {
            $user->update([
                'step_1' => $step_1,
                'step_2' => $step_2,
            ]);
        } else {
            UserAction::create([
                'chat_id' => $chat_id,
                'step_1' => 0,
                'step_2' => 0
            ]);
        }
    }

    public function saveUser(mixed $chat_id, mixed $first_name, mixed $username): void
    {
        $user = BotUser::where('chat_id', $chat_id)->first();
        if ($user) {
            $user->update([
                'chat_id' => $chat_id,
                'username' => $username,
            ]);
        } else {
            BotUser::create([
                'chat_id' => $chat_id,
                'first_name' => $first_name,
                'username' => $username,
            ]);
        }
    }
}
