<?php

namespace App\Console;

use App\Jobs\CheckAppCompanyStatus;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Tinker\Console\TinkerCommand;

class Kernel extends ConsoleKernel
{
    // protected $commands = [
    //     //
    //     \Laravel\Tinker\Console\TinkerCommand::class,
    // ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:close-status')->everyMinute();
        // $schedule->command('queue:work --stop-when-empty')->everyMinute();
        $schedule->job(new CheckAppCompanyStatus)
        ->everyMinute()
        ->withoutOverlapping();  
    
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
