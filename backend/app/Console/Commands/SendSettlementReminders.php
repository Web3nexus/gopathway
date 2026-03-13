<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettlementNotificationService;

class SendSettlementReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settlement:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for pending mandatory settlement steps';

    /**
     * Execute the console command.
     */
    public function handle(SettlementNotificationService $service)
    {
        $this->info('Sending settlement reminders...');
        $service->sendReminders();
        $this->info('Reminders sent successfully!');
    }
}