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
        // Transferts non terminés des 7 derniers jours, vieux d'au moins 3 min (laisse le temps à l'IPN) :
        //  - expéditeur débité (SUCCESS) ou débit encore en attente (PENDING)
        //  - ni réussi, ni remboursement confirmé
        $query = PixelMobileZoneTransfer::whereIn('sender_status', ['PENDING', 'SUCCESS'])
            ->where('status', '!=', 'SUCCESS')
            ->where(function ($q) {
                $q->whereNull('refund_status')->orWhereIn('refund_status', ['PENDING', 'FAILED']);
            })
            ->whereNotNull('sender_transaction_id')
            ->where('created_at', '<=', now()->subMinutes(3))
            ->where('created_at', '>=', now()->subDays(7))
            ->when($this->option('id'), fn ($q, $ids) => $q->whereIn('id', $ids))
            ->orderBy('id');

        // Remboursements abandonnés après 3 échecs : traitement manuel, on ne relance plus
        $transfers = $query->get()->reject(fn ($t) => $t->refundExhausted());
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
