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

        $users_all = SidUser::get();

        foreach ($users_all  as $user) {

            $notifications = DB::table('notifications')->where('read_at', null)->where('notifiable_id', $user->id)->get();

            if ($notifications->count() >= 70) {
                $user->unreadNotifications->markAsRead();
            } 
    
        }
    }
}