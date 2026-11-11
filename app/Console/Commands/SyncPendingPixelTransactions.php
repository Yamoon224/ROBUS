<?php

namespace App\Console\Commands;

use App\Http\Controllers\PixelPaymentController;
use App\Models\Wallets\WalletReload;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SyncPendingPixelTransactions extends Command
{
    protected $signature   = 'pixel:sync-pending';
    protected $description = 'Re-synchronise les transactions Pixel Innov PENDING via l\'API Pixel Innov (IPN manqué ou timeout)';

    public function handle(): void
    {
        $pending = WalletReload::where('status', 'PENDING')
            ->where(function ($q) {
                // robus_wallet : retraits débités du wallet Robus — à rembourser si Pixel Innov répond FAILED
                $q->where('payment_method', 'LIKE', 'pixel_innov_%')
                    ->orWhere('payment_method', 'robus_wallet');
            })
            ->whereNotNull('reference')
            ->where('created_at', '<=', now()->subMinutes(3))   // au moins 3 min sans IPN
            ->where('created_at', '>=', now()->subHours(24))    // pas plus de 24h
            ->get();

        if ($pending->isEmpty()) {
            return;
        }

        Log::info('pixel:sync-pending — démarrage', ['count' => $pending->count()]);

        $controller = app(PixelPaymentController::class);

        foreach ($pending as $reload) {
            try {
                $request = Request::create('', 'POST', ['transaction_id' => $reload->reference]);
                $controller->checkStatus($request);
            } catch (\Throwable $e) {
                Log::error('pixel:sync-pending — erreur', [
                    'transaction_id' => $reload->reference,
                    'error'          => $e->getMessage(),
                ]);
            }
        }
    }
}
