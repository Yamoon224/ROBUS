<?php

namespace App\Console\Commands;


use App\Models\AppCloseCompany;
use App\Models\AppCloseMaster;
use App\Models\AppCompanyStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CheckAppCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-company-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close application Compagnie';

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

        $robus_app = AppCloseCompany::whereStatus(true)->where('id', $currentDayOfWeek)->first();

        if ($robus_app) {

            $robus_hour_begin = Carbon::createFromFormat('H:i:s', $robus_app->time_start)->format('H:i:s');
            $robus_hour_end = Carbon::createFromFormat('H:i:s', $robus_app->time_end)->format('H:i:s');
            $currentHour = Carbon::now()->format('H:i:s');
            $robus_hour_description = $robus_app->description;
            $robus_day = $robus_app->day_of_week;

            if ($currentHour >= $robus_hour_begin && $currentHour <= $robus_hour_end) {
               // Utilisation de la classe DB
               DB::table('app_company_statuses')->truncate();
                DB::statement('TRUNCATE TABLE app_company_statuses');

            } else {
                AppCompanyStatus::create([
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
