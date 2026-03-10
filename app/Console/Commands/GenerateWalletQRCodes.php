<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Wallets\Wallet;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateWalletQRCodes extends Command
{
    protected $signature = 'wallet:generate-qrcodes 
                            {--chunk=50 : Nombre de wallets à traiter par lot}
                            {--sleep=1 : Temps de pause entre les lots (secondes)}
                            {--from-id= : ID de départ pour reprendre}
                            {--force : Forcer la régénération même si le QR existe}';
    
    protected $description = 'Générer les QR codes pour tous les wallets existants avec gestion mémoire optimisée';

    private $totalProcessed = 0;
    private $totalErrors = 0;
    private $startTime;

    public function handle()
    {
        $this->startTime = microtime(true);
        
        // Désactiver les limites
        $this->optimizeEnvironment();

        // Récupérer les paramètres
        $chunkSize = (int) $this->option('chunk');
        $sleepTime = (int) $this->option('sleep');
        $fromId = $this->option('from-id');
        $force = $this->option('force');

        // Compter le total à traiter
        $totalWallets = $this->getTotalWallets($fromId);
        
        $this->info("🚀 Démarrage de la génération des QR codes");
        $this->warn("📊 Total wallets à traiter: {$totalWallets}");
        $this->line("⚡ Lots de {$chunkSize} avec pause de {$sleepTime}s");
        $this->newLine();

        // Créer la barre de progression
        $bar = $this->output->createProgressBar($totalWallets);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
        $bar->start();

        // Traiter par lots avec requête optimisée
        $query = Wallet::with('sidUser')
            ->select('wallets.*', 'sid_users.is_custumers')
            ->leftJoin('sid_users', 'wallets.sid_user_id', '=', 'sid_users.id')
            ->whereNotNull('wallets.wallet_id'); // Seulement les wallets avec wallet_id

        if ($fromId) {
            $query->where('wallets.id', '>=', $fromId);
        }

        $query->orderBy('wallets.id')
            ->chunk($chunkSize, function ($wallets) use ($bar, $sleepTime, $force) {
                foreach ($wallets as $wallet) {
                    if ($this->processWallet($wallet, $force)) {
                        $this->totalProcessed++;
                    }
                    $bar->advance();
                    
                    // Libérer la mémoire après chaque wallet
                    gc_collect_cycles();
                }

                // Pause entre les lots pour laisser le système respirer
                if ($sleepTime > 0) {
                    sleep($sleepTime);
                }

                // Nettoyer la connexion DB
                DB::connection()->getPdo()->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
            });

        $bar->finish();
        $this->newLine(2);

        // Afficher le résumé
        $this->showSummary();
    }

    /**
     * Optimiser l'environnement d'exécution
     */
    private function optimizeEnvironment(): void
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M'); // Limite raisonnable mais pas illimitée
        ini_set('max_execution_time', '0');
        ini_set('display_errors', '1');
        ini_set('log_errors', '1');
        
        // Optimiser MySQL pour les gros traitements
        DB::statement('SET SESSION wait_timeout = 28800');
        DB::statement('SET SESSION interactive_timeout = 28800');
    }

    /**
     * Obtenir le nombre total de wallets à traiter
     */
    private function getTotalWallets($fromId = null): int
    {
        $query = Wallet::whereNotNull('wallet_id');
        
        if ($fromId) {
            $query->where('id', '>=', $fromId);
        }
        
        return $query->count();
    }

    /**
     * Traiter un wallet individuel
     */
    private function processWallet($wallet, $force = false): bool
    {
        try {
            // Vérifier si le QR code existe déjà
            $existingQrPath = public_path("qrcodes/wallet_{$wallet->id}.png");
            if (!$force && file_exists($existingQrPath) && $wallet->qr_code) {
                return true; // Déjà généré, on passe
            }

            // Déterminer le logo selon le type d'utilisateur
            $logoPath = $this->getLogoPath($wallet);

            // Vérifier si le logo existe
            if (!file_exists($logoPath)) {
                $this->logError("Logo non trouvé pour wallet ID {$wallet->id}", $wallet->id);
                return false;
            }

            // Préparer les données du QR code
            $qrData = json_encode([
                'wallet_id' => $wallet->wallet_id,
                'generated_at' => now()->toDateTimeString()
            ]);

            // Générer le QR code avec gestion d'erreur
            $qrPath = $this->generateQRCode($wallet->id, $qrData, $logoPath);

            if (!$qrPath) {
                return false;
            }

            // Mettre à jour le wallet en une seule requête
            $wallet->timestamps = false; // Éviter de modifier updated_at
            $wallet->update(['qr_code' => $qrPath]);

            // Logging en mode verbose
            if ($this->output->isVerbose()) {
                $this->line("✓ QR généré: wallet {$wallet->id}");
            }

            return true;

        } catch (\Exception $e) {
            $this->logError("Erreur wallet ID {$wallet->id}: " . $e->getMessage(), $wallet->id);
            return false;
        }
    }

    /**
     * Obtenir le chemin du logo approprié
     */
    private function getLogoPath($wallet): string
    {
        $isCustomer = $wallet->sidUser?->is_custumers ?? false;
        
        return $isCustomer 
            ? public_path('dist/images/logo_orange.png')
            : public_path('dist/images/logo_violet.png');
    }

    /**
     * Générer le QR code et retourner le chemin
     */
    private function generateQRCode(int $walletId, string $data, string $logoPath): ?string
    {
        try {
            $result = Builder::create()
                ->data($data)
                ->encoding(new Encoding('UTF-8'))
                ->size(300)
                ->margin(10)
                ->logoPath($logoPath)
                ->logoResizeToWidth(70)
                ->logoResizeToHeight(70)
                ->build();

            $qrPath = "qrcodes/wallet_{$walletId}.png";
            $fullPath = public_path($qrPath);

            // Créer le dossier si nécessaire
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $result->saveToFile($fullPath);
            
            // Libérer la mémoire
            unset($result);
            
            return $qrPath;

        } catch (\Exception $e) {
            $this->logError("Échec génération QR wallet {$walletId}: " . $e->getMessage(), $walletId);
            return null;
        }
    }

    /**
     * Logger les erreurs
     */
    private function logError(string $message, ?int $walletId = null): void
    {
        $this->totalErrors++;
        
        if ($walletId) {
            Log::channel('daily')->error("QR Code Generation - Wallet {$walletId}: {$message}");
        } else {
            Log::channel('daily')->error("QR Code Generation: {$message}");
        }
        
        if ($this->output->isDebug()) {
            $this->error($message);
        }
    }

    /**
     * Afficher le résumé de l'exécution
     */
    private function showSummary(): void
    {
        $executionTime = round(microtime(true) - $this->startTime, 2);
        $memoryUsage = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
        
        $this->info("✅ Génération terminée !");
        $this->table(
            ['Métrique', 'Valeur'],
            [
                ['Wallets traités', $this->totalProcessed],
                ['Erreurs', $this->totalErrors],
                ['Temps d\'exécution', "{$executionTime} secondes"],
                ['Pic mémoire', "{$memoryUsage} MB"],
                ['Date', now()->format('d/m/Y H:i:s')]
            ]
        );
    }
}