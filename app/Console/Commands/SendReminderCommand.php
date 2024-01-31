<?php

namespace App\Console\Commands;

use App\Services\SenderService;
use Illuminate\Console\Command;

class SendReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminder-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(SenderService $service): void
    {
        $service->sendReminderToAll();
    }
}
