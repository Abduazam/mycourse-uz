<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Services\ReminderService;
use Illuminate\Console\Command;

class CheckDailyReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-daily-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(ReminderService $service): void
    {
        $reminders = Reminder::query()->get();
        foreach ($reminders as $reminder) {
            if ($reminder->next_day == date('Y-m-d') && $reminder->active == 1) {
                $reminder->update([
                    'next_day' => $service->getNextDay($reminder, $reminder->per_day),
                    'active' => 0
                ]);

                info($reminder);
            }
        }
    }
}
