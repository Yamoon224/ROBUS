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
        $wallets = Wallet::all();
        $this->info('Nombre de wallets : ' . $wallets->count());

        $originalLogoPath = public_path('dist/images/logo_robus.png');


        foreach ($wallets as $wallet) {
            // Si le wallet a déjà un QR code, on peut sauter ou régénérer
            // if ($wallet->qr_code) {
            //     continue;
            // }

            $qrData = json_encode([
                'wallet_id' => $wallet->wallet_id,
                // 'user' => $wallet->sidUser->firstname . ' '.  $wallet->sidUser->lastname,
                // 'countrie' => $wallet->sidUser->country->name . ' '.  $wallet->sidUser->citie->name,
                'currencie' => $wallet->country->currencie->name,
            ]);

            $result = Builder::create()
                ->data($qrData)
                ->encoding(new Encoding('UTF-8'))
                // ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(300)
                ->margin(10)
                // ->roundBlockSizeMode(new RoundBlockSizeModeMargin())

                // 🎨 Couleur du QR code
                // ->foregroundColor(new Color(0, 51, 102))
                // ->backgroundColor(new Color(255, 255, 255)) 

                // 🖼️ Logo au centre
                ->logoPath($originalLogoPath)
                ->logoResizeToWidth(70)
                ->logoResizeToHeight(70)

                // 🏷️ Optionnel : un texte sous le QR code
                // ->labelText('Mon Portefeuille')
                // ->labelFont(new NotoSans(14))
                // ->labelAlignment(new LabelAlignmentCenter())

                ->build();


            $qrPath = 'qrcodes/wallet_' . $wallet->id . '.png';
            $result->saveToFile(public_path($qrPath));

            $wallet->update(['qr_code' => $qrPath]);

            $this->info("QR code généré pour wallet ID {$wallet->id}");
        }

        $this->info('Tous les QR codes ont été générés !');
    }
}
