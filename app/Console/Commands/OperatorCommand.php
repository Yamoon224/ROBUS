<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OperatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:operatorUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assignation des operateurs assigners';

    /**
     * Execute the console command.
     *
     * @return int
     */

     public function __construct()
     {
         parent::__construct();
     }
 


    public function handle()
    {
        return Command::SUCCESS;
    }
}
