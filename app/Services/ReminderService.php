<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Reminder;
use Exception;
use Illuminate\Support\Facades\DB;

class ReminderService
{
    /**
     * @throws Exception
     */
    public function create(array $data, mixed $file): void
    {
        try {
            DB::beginTransaction();

            $media = $file?->store('reminders', 'public');

            Reminder::create([
                'file' => $media,
                'text' => $data['text'],
                'per_day' => $data['perDay'],
                'next_day' => $this->getNextDay(null, $data['perDay'])
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function edit(array $data, mixed $file, Reminder $reminder): void
    {
        try {
            DB::beginTransaction();

            $media = $this->checkReminderFile($reminder, $file);

            $reminder->update([
                'file' => $media,
                'text' => $data['reminder']['text'],
                'per_day' => $data['reminder']['per_day'],
                'next_day' => $this->getNextDay(null, $data['reminder']['per_day'])
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getNextDay(?Reminder $reminder = null, int $day): bool|Carbon
    {
        if ($reminder !== null) {
            $date = Carbon::createFromFormat('Y.m.d', $reminder->next_day);
            return $date->addDays($day);
        }

        $date = Carbon::now();
        return $date->addDays($day);
    }

    private function checkReminderFile(Reminder $reminder, mixed $newFile)
    {
        $file = $reminder->file;
        if (isset($newFile)) {
            if (isset($file) and storage_path('app/public/' . $file)) {
                unlink(storage_path('app/public/' . $file));
            }

            return $newFile->store('reminders', 'public');
        }
    }
}
