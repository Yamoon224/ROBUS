<?php

namespace App\Console\Commands;

use App\Models\AppStatus;
use App\Models\CloseApp;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CheckAppStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var stringreadNotifications
     */
    protected $signature = 'app:close-status';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Check if the application is open or closed';

    /**
     * Execute the console command.
     *
     * @return int
     */


    public function handle()
    {

        $currentDayOfWeek = Carbon::now()->dayOfWeek;
        $currentHour = Carbon::now()->format('H:i:s');
        $currentDayOfWeek = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek;

        $robus_app = CloseApp::whereStatus(true)->where('id', $currentDayOfWeek)->first();

        DB::table('app_statuses')->truncate();

        if ($robus_app) {


            $robus_hour_begin = $robus_app->time_start;
            $robus_hour_end = $robus_app->time_end;
            $currentHour = Carbon::now()->format('H:i:s');
            $robus_hour_description = $robus_app->description;
            $robus_day = $robus_app->day_of_week;

            if ($currentHour >= $robus_hour_begin && $currentHour <= $robus_hour_end) {
                DB::table('app_statuses')->truncate();
            } else {
                AppStatus::create([
                    'status_app' => 'closed',
                    'description' => $robus_hour_description,
                    'time_start' => $robus_hour_begin,
                    'time_end' => $robus_hour_end,
                    'day_week' => $robus_day,
                ]);
            }
        }

        Cache::forget('app_status_mobile');
        Cache::put('app_status_last_check', now(), now()->addMinutes(5));
        $this->info('Cache nettoyé et vérification terminée');
    }
}
