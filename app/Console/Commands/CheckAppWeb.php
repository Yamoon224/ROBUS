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
                $currentDayOfWeek = Carbon::now()->dayOfWeek;
                // Ajuster pour que Dimanche soit 7 au lieu de 0
                $currentDayOfWeek = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek;

                $robus_app = CloseAppWeb::whereStatus(true)->where('id', $currentDayOfWeek)->first();

                DB::table('app_statuses')->truncate();

                if ($robus_app) {
                        $robus_hour_begin = $robus_app->time_start;
                        $robus_hour_end = $robus_app->time_end;
                        $currentHour = Carbon::now()->format('H:i:s');
                        $robus_hour_description = $robus_app->description;
                        $robus_day = $robus_app->id;

                        if ($currentHour >= $robus_hour_begin && $currentHour <= $robus_hour_end) {
                                // Utilisation de la classe DB pour vider la table des statuts
                                DB::table('app_web_statuses')->truncate();
                                // DB::statement('TRUNCATE TABLE app_web_statuses');
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
