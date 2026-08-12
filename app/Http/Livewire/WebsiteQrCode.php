<?php

namespace App\Http\Livewire;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Livewire\Component;

class WebsiteQrCode extends Component
{
    public $qrPath = null;

    public function mount()
    {
        $path = 'qrcodes/website_robus.png';
        if (file_exists(public_path($path))) {
            $this->qrPath = $path;
        }
    }

    public function generate()
    {
        $url      = env('WEBSITE_URL', 'https://robus.africa');
        $logoPath = public_path('dist/images/logo_orange.png');
        $qrPath   = 'qrcodes/website_robus.png';

        try {
            $result = Builder::create()
                ->data($url)
                ->encoding(new Encoding('UTF-8'))
                ->size(400)
                ->margin(12)
                ->logoPath($logoPath)
                ->logoResizeToWidth(90)
                ->logoResizeToHeight(90)
                ->build();

            $result->saveToFile(public_path($qrPath));
            $this->qrPath = $qrPath;
            $this->emit('success', 'QR Code généré avec succès !');
        } catch (\Throwable $th) {
            $this->emit('error', 'Erreur lors de la génération du QR code.');
        }
    }

    public function render()
    {
        return view('livewire.website-qr-code');
    }
}
