<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearLog extends Command
{
    protected $signature = 'log:clear';
    protected $description = 'Vider le fichier laravel.log';

    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
            $this->info('✅ Fichier laravel.log vidé avec succès !');
        } else {
            $this->error('❌ Fichier laravel.log introuvable');
        }
    }
}