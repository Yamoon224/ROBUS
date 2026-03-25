<?php
// app/Console/Commands/DeleteDefaultJobs.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteDefaultJobs extends Command
{
    protected $signature = 'queue:delete-default 
                            {--force : Supprimer sans confirmation}
                            {--show : Afficher les jobs avant suppression}';
    
    protected $description = 'Supprimer tous les jobs dans la queue default';

    public function handle()
    {
        $count = DB::table('jobs')->where('queue', 'default')->count();
        
        if ($count === 0) {
            $this->info('✅ Aucun job dans la queue default');
            return 0;
        }
        
        $this->warn("⚠️  {$count} job(s) trouvé(s) dans la queue default");
        
        // Afficher les jobs si demandé
        if ($this->option('show')) {
            $jobs = DB::table('jobs')
                ->where('queue', 'default')
                ->limit(10)
                ->get();
            
            $this->info("\nJobs dans default:");
            foreach ($jobs as $job) {
                $payload = json_decode($job->payload, true);
                $className = $payload['displayName'] ?? 'Unknown';
                $this->line("   - ID: {$job->id} | {$className} | Créé: " . date('Y-m-d H:i:s', $job->created_at));
            }
            
            if ($count > 10) {
                $this->line("   ... et " . ($count - 10) . " autres");
            }
        }
        
        // Confirmation
        if ($this->option('force') || $this->confirm("Voulez-vous supprimer ces {$count} jobs?")) {
            $deleted = DB::table('jobs')->where('queue', 'default')->delete();
            $this->info("✓ {$deleted} job(s) supprimés");
            
            // Afficher le résumé
            $this->showSummary();
        } else {
            $this->info('Suppression annulée');
        }
        
        return 0;
    }
    
    protected function showSummary()
    {
        $queues = DB::table('jobs')
            ->select('queue', DB::raw('count(*) as total'))
            ->groupBy('queue')
            ->get();
        
        $this->info("\n📊 État actuel des queues:");
        if ($queues->isEmpty()) {
            $this->info("   ✅ Toutes les queues sont vides");
        } else {
            foreach ($queues as $queue) {
                $this->line("   - {$queue->queue}: {$queue->total} job(s)");
            }
        }
    }
}