<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Wallets\Wallet;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;

class GenerateWalletQRCodes extends Command
{
    protected $signature = 'wallet:generate-qrcodes';
    protected $description = 'Générer les QR codes pour tous les wallets existants';

    public function handle()
    {
        // Désactiver toutes les limites
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        // Utiliser chunk pour traiter par lots
        Wallet::with('sidUser')
            ->chunk(100, function ($wallets) {
                foreach ($wallets as $wallet) {
                    $this->processWallet($wallet);
                }
            });

        $this->info('Tous les QR codes ont été générés !');
    }

    private function processWallet($wallet)
    {
        try {
            $originalLogoPath = $wallet->sidUser?->is_custumers
                ? public_path('dist/images/logo_orange.png')
                : public_path('dist/images/logo_violet.png');

            // Vérifier si le logo existe
            if (!file_exists($originalLogoPath)) {
                $this->error("Logo non trouvé pour wallet ID {$wallet->id}");
                return;
            }

            $qrData = json_encode([
                'wallet_id' => $wallet->wallet_id,
            ]);

            $result = Builder::create()
                ->data($qrData)
                ->encoding(new Encoding('UTF-8'))
                ->size(300)
                ->margin(10)
                ->logoPath($originalLogoPath)
                ->logoResizeToWidth(70)
                ->logoResizeToHeight(70)
                ->build();

            $qrPath = 'qrcodes/wallet_' . $wallet->id . '.png';
            $fullPath = public_path($qrPath);

            // Créer le dossier si nécessaire
            if (!is_dir(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            $result->saveToFile($fullPath);
            $wallet->update(['qr_code' => $qrPath]);

            $this->info("QR code généré pour wallet ID {$wallet->id}");

            // Libérer la mémoire
            unset($result);
        } catch (\Exception $e) {
            $this->error("Erreur pour wallet ID {$wallet->id}: " . $e->getMessage());
        }
    }
}
