<?php

namespace App\Console\Commands;

use App\Http\Controllers\PixelPaymentController;
use App\Models\PixelMobileZoneTransfer;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

/**
 * Réconcilie les transferts Mobile Zone bloqués : expéditeur débité (Cash Out SUCCESS)
 * mais destinataire non crédité. Réutilise checkMobileZoneTransferStatus, qui interroge
 * Pixel Innov puis relance le Cash In ou rembourse l'expéditeur selon l'état réel.
 *
 * Par défaut : simulation (liste seulement). Ajouter --execute pour agir.
 */
class PixelReconcileTransfers extends Command
{
    protected $signature = 'pixel:reconcile-transfers {--execute : Lancer réellement les remboursements / relances} {--id=* : Limiter à ces ID de transfert}';

    protected $description = 'Rembourse ou relance les transferts Mobile Zone dont l\'expéditeur a été débité sans que le destinataire soit crédité';

    public function handle(): int
    {
        $query = PixelMobileZoneTransfer::where('sender_status', 'SUCCESS')
            ->where(function ($q) {
                $q->whereNull('recipient_status')->orWhere('recipient_status', '!=', 'SUCCESS');
            })
            ->where(function ($q) {
                $q->whereNull('refund_status')->orWhere('refund_status', 'FAILED');
            })
            ->when($this->option('id'), fn ($q, $ids) => $q->whereIn('id', $ids))
            ->orderBy('id');

        $transfers = $query->get();
        $this->info($transfers->count() . ' transfert(s) à examiner.');

        foreach ($transfers as $t) {
            $this->line(sprintf(
                '#%d  expéditeur %s  -> %s  montant %s  cashout=%s cashin=%s refund=%s (%s)',
                $t->id, $t->sender_phone, $t->recipient_phone, $t->amount,
                $t->sender_transaction_id, $t->recipient_transaction_id ?: '-', $t->refund_status ?: '-', $t->status
            ));

            if (!$this->option('execute')) {
                continue;
            }

            $response = app(PixelPaymentController::class)
                ->checkMobileZoneTransferStatus(new Request(['transfer_id' => $t->id]))
                ->getData(true);

            $this->line('   => statut ' . ($response['local_status'] ?? '?')
                . ' / destinataire ' . ($response['recipient_status'] ?? '?')
                . (!empty($response['recovered']) ? ' (Cash In relancé)' : ''));
        }

        if (!$this->option('execute')) {
            $this->warn('Simulation : relancez avec --execute pour agir.');
        }

        return self::SUCCESS;
    }
}
