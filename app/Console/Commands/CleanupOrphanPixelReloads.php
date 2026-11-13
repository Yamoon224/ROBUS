<?php

namespace App\Console\Commands;

use App\Http\Services\PixelCancelService;
use Illuminate\Console\Command;

class CleanupOrphanPixelReloads extends Command
{
    protected $signature = 'pixel:cleanup-orphans {--minutes=15 : Ancienneté minimale (minutes)} {--dry-run : Lister sans modifier}';

    protected $description = 'Passe en ERROR les opérations Pixel PENDING sans référence (jamais transmises à Pixel Innov)';

    public function handle(PixelCancelService $service): int
    {
        $result = $service->autoCancelOrphans((int) $this->option('minutes'), (bool) $this->option('dry-run'));

        $this->info(($this->option('dry-run') ? '[simulation] ' : '') . count($result['cancelled']) . ' opération(s) annulée(s) : ' . implode(',', $result['cancelled']));

        if ($result['needs_review']) {
            $this->warn(count($result['needs_review']) . ' opération(s) avec wallet débité à vérifier manuellement : ' . implode(',', $result['needs_review']));
        }

        return self::SUCCESS;
    }
}
