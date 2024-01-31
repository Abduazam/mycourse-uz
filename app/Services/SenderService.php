<?php

namespace App\Services;

use App\Models\BotUser;
use App\Models\Message;
use App\Models\Reminder;
use App\Models\Telegram;

class SenderService
{
    public function sendMessageToAll(): void
    {
        $telegram = new Telegram(config('telegram.tokens.main'));
        $message = Message::query()->where('active', 0)->first();
        if ($message) {
            $content = [
                'parse_mode' => 'html',
                'protect_content' => true,
            ];

            $method = "sendMessage";

            if (isset($message->file)) {
                $media = explode('.', $message->file);
                $extension = end($media);

                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                        $content['photo'] = $this->makeFilePath($message->file);
                        $method = "sendPhoto";
                        break;
                    case 'mp4':
                    case 'mov':
                        $content['video'] = $this->makeFilePath($message->file);
                        $method = "sendVideo";
                        break;
                    default:
                        $content['audio'] = $this->makeFilePath($message->file);
                        $method = "sendAudio";
                        break;
                }

                $content['caption'] = $message->telegramText(1024);
            } else {
                $content['text'] = $message->telegramText();
            }

            $users = BotUser::query()->where('is_view', 0)->limit(30)->get();
            if (count($users) > 0) {
                foreach ($users as $user) {
                    try {
                        $content['chat_id'] = $user->chat_id;
                        $telegram->{$method}($content);
                        $user->update(['is_view' => 1]);
                    } catch (\Exception $e) {
                        info("Failed to send message to user with chat ID: $user->chat_id. Error: " . $e->getMessage());
                    }
                }
            } else {
                $message->update(['active' => 1]);
                BotUser::where('is_view', 1)->update(['is_view' => 0]);
            }
        }
    }

    public function sendReminderToAll(): void
    {
        $telegram = new Telegram(config('telegram.tokens.main'));
        $message = Reminder::query()->where('active', 0)->first();
        if ($message) {
            $content = [
                'parse_mode' => 'html',
                'protect_content' => true,
            ];

            $method = "sendMessage";

            if (isset($message->file)) {
                $media = explode('.', $message->file);
                $extension = end($media);

                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                        $content['photo'] = $this->makeFilePath($message->file);
                        $method = "sendPhoto";
                        break;
                    case 'mp4':
                    case 'mov':
                        $content['video'] = $this->makeFilePath($message->file);
                        $method = "sendVideo";
                        break;
                    default:
                        $content['audio'] = $this->makeFilePath($message->file);
                        $method = "sendAudio";
                        break;
                }

                $content['caption'] = $message->telegramText(1024);
            } else {
                $content['text'] = $message->telegramText();
            }

            $users = BotUser::query()->where('is_view', 0)->limit(30)->get();
            if (count($users) > 0) {
                foreach ($users as $user) {
                    try {
                        $content['chat_id'] = $user->chat_id;
                        $telegram->{$method}($content);
                        $user->update(['is_view' => 1]);
                    } catch (\Exception $e) {
                        info("Failed to send message to user with chat ID: $user->chat_id. Error: " . $e->getMessage());
                    }
                }
            } else {
                $message->update(['active' => 1]);
                BotUser::where('is_view', 1)->update(['is_view' => 0]);
            }
        }
    }

    private function makeFilePath($file): \CURLFile
    {
        return curl_file_create(config('telegram.urls.main') . '/storage/' . $file);
    }
}
