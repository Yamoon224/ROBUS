<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Sid\SidUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateReadNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:readNotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mettre a jour les notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $unreadNotifications = Notification::whereNull('read_at')->get();

        $notificationsByUser = $unreadNotifications->groupBy('notifiable_id');

        foreach ($notificationsByUser as $userId => $notifications) {
            if ($notifications->count() >= 70) {
                Notification::where('notifiable_id', $userId)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }
        }

    }
}