<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class assignementUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assignement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assignement des utilisateurs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
