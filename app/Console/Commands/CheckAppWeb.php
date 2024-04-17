<?php

namespace App\Console\Commands;

use App\Models\AppStatus;
use App\Models\AppWebStatus;
use App\Models\CloseAppWeb;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CheckAppWeb extends  Command implements ShouldQueue
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-web-status';

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
        $days_open_robus = CloseAppWeb::whereStatus(true)->get();
        
        $currentDayOfWeek = Carbon::now()->dayOfWeek;

        foreach ($days_open_robus as $robus_app) {

            if ($currentDayOfWeek == $robus_app->id) {


                $robus_hour_begin = $robus_app->time_start;
                $robus_hour_end = $robus_app->time_end;
                $currentHour = Carbon::now()->format('H:i:s');
                $robus_hour_description = $robus_app->description;
                $robus_day = $robus_app->id;

     

                if ($currentHour >= $robus_hour_begin && $currentHour <= $robus_hour_end) {
                    // Utilisation de la classe DB
                    DB::table('app_web_statuses')->truncate();
                    // Ou utilisation de la classe DB avec une requête SQL brute
                    DB::statement('TRUNCATE TABLE app_web_statuses');

                } else {
                    // L'application est fermée, enregistrez cet état dans la base de données

                    AppWebStatus::create([
                        'status_app' => 'closed',
                        'description' => $robus_hour_description,
                        'time_start' => $robus_hour_begin,
                        'time_end' => $robus_hour_end,
                        'day_week' => $robus_day,
                    ]);
                }
            }
        }
    }
}
