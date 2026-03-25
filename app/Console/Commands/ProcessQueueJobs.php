<?php
// app/Console/Commands/ProcessQueueJobs.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessQueueJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:process 
                            {--queue=emails : Queue à traiter}
                            {--limit=100 : Nombre maximum de jobs à traiter}
                            {--once : Traiter un seul job}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Traiter les jobs en attente dans la queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queue = $this->option('queue');
        $limit = $this->option('limit');
        $once = $this->option('once');
        
        $this->info("🚀 Traitement des jobs de la queue: {$queue}");
        
        // Compter les jobs avant
        $jobsBefore = DB::table('jobs')->where('queue', $queue)->count();
        $this->info("Jobs en attente: {$jobsBefore}");
        
        if ($once) {
            // Traiter un seul job
            $this->processOneJob($queue);
        } else {
            // Traiter plusieurs jobs
            $this->processMultipleJobs($queue, $limit);
        }
        
        // Afficher les résultats
        $jobsAfter = DB::table('jobs')->where('queue', $queue)->count();
        $processed = $jobsBefore - $jobsAfter;
        
        $this->info("\n✅ Résultat: {$processed} jobs traités");
        $this->info("Jobs restants: {$jobsAfter}");
        
        return 0;
    }
    
    protected function processOneJob($queue)
    {
        try {
            Artisan::call('queue:work', [
                '--queue' => $queue,
                '--once' => true,
                '--stop-when-empty' => true
            ]);
            
            $output = Artisan::output();
            $this->line($output);
            
            if (strpos($output, 'failed') !== false) {
                $this->error("❌ Le job a échoué");
            } else {
                $this->info("✅ Un job a été traité avec succès");
            }
            
        } catch (\Exception $e) {
            $this->error("Erreur: " . $e->getMessage());
        }
    }
    
    protected function processMultipleJobs($queue, $limit)
    {
        $processed = 0;
        $failed = 0;
        
        while ($processed < $limit && DB::table('jobs')->where('queue', $queue)->count() > 0) {
            try {
                Artisan::call('queue:work', [
                    '--queue' => $queue,
                    '--once' => true,
                    '--sleep' => 1
                ]);
                
                $output = Artisan::output();
                
                if (strpos($output, 'failed') !== false) {
                    $failed++;
                    $this->error("❌ Job échoué (#{$failed})");
                } else {
                    $processed++;
                    $this->info("✅ Job traité: {$processed}/{$limit}");
                }
                
            } catch (\Exception $e) {
                $failed++;
                $this->error("Erreur: " . $e->getMessage());
            }
        }
        
        $this->info("\n📊 Statistiques:");
        $this->info("✅ Traités avec succès: {$processed}");
        $this->info("❌ Échoués: {$failed}");
    }
}