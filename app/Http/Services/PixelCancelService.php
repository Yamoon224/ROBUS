<?php

namespace App\Http\Services;

use App\Models\PixelInnoveService;
use App\Models\PixelMobileZoneTransfer;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Annulation manuelle (administrateur) d'une opération Pixel Innov restée PENDING
 * alors que ni l'expéditeur n'a été débité ni le destinataire crédité.
 *
 * Garde-fous : jamais d'annulation si Pixel indique SUCCESS (ou si l'état Pixel est
 * invérifiable), si le wallet Robus a été débité (remboursement = flux automatique)
 * ou si le Cash In destinataire a déjà été initié.
 *
 * Un SUCCESS tardif reçu de Pixel après annulation reste traité normalement (le statut
 * ERROR/FAILED n'est pas bloquant dans les callbacks), donc aucune perte d'argent possible.
 */
class PixelCancelService
{
    public static function userCanCancel(): bool
    {
        $user = Auth::guard('web')->user();

        return $user && method_exists($user, 'hasRole') && $user->hasRole('root');
    }

    /** @return array{ok:bool,message:string} */
    public function cancelReload(int $reloadId): array
    {
        if (!self::userCanCancel()) {
            return $this->ko('Action réservée à l\'administrateur.');
        }

        return DB::transaction(function () use ($reloadId) {
            $reload = WalletReload::lockForUpdate()->find($reloadId);

            if (!$reload || $reload->status !== 'PENDING') {
                return $this->ko('Cette opération n\'est plus en attente.');
            }

            $isPixel = str_starts_with((string) $reload->payment_method, 'pixel_innov_')
                || $reload->payment_method === 'robus_wallet';
            if (!$isPixel) {
                return $this->ko('Opération non Pixel Innov.');
            }

            $meta = $this->decode($reload->metadata);

            // Wallet Robus débité (Cash In) : le remboursement passe par le flux automatique
            // (callback / pixel:sync-pending), jamais par une annulation manuelle.
            if (($meta['operation_type'] ?? null) === 'Cash In' && (float) ($meta['total_debited'] ?? 0) > 0) {
                return $this->ko('Le wallet a été débité : le remboursement est automatique dès que Pixel confirme l\'échec.');
            }

            if (!empty($reload->reference)) {
                $state = $this->remoteState((string) $reload->reference, $meta['pixel_service_id'] ?? null);
                if ($state === null) {
                    return $this->ko('Impossible de vérifier le statut chez Pixel Innov. Réessayez dans un instant.');
                }
                if (in_array($state, ['SUCCESS', 'SUCCESSFUL'])) {
                    return $this->ko('Pixel Innov indique SUCCESS : annulation refusée (la synchronisation créditera l\'opération).');
                }
            }

            $meta['cancelled_manually'] = true;
            $meta['cancelled_by']       = optional(Auth::guard('web')->user())->id;
            $meta['cancelled_at']       = now()->toDateTimeString();

            $reload->update([
                'status'           => 'ERROR',
                'gateway_response' => 'Annulée manuellement par l\'administrateur',
                'metadata'         => json_encode($meta),
            ]);

            Log::info('Pixel Innov — opération annulée manuellement', [
                'reload_id' => $reload->id,
                'reference' => $reload->reference,
                'by'        => $meta['cancelled_by'],
            ]);

            return ['ok' => true, 'message' => 'Opération annulée.'];
        });
    }

    /** @return array{ok:bool,message:string} */
    public function cancelTransfer(int $transferId): array
    {
        if (!self::userCanCancel()) {
            return $this->ko('Action réservée à l\'administrateur.');
        }

        return DB::transaction(function () use ($transferId) {
            $t = PixelMobileZoneTransfer::lockForUpdate()->find($transferId);

            if (!$t || !in_array($t->status, ['PENDING', 'PROCESSING'])) {
                return $this->ko('Ce transfert n\'est plus en attente.');
            }

            // Expéditeur non débité, destinataire non crédité, aucun Cash In ni remboursement initié
            if ($t->sender_status !== 'PENDING'
                || $t->recipient_status !== 'PENDING'
                || !empty($t->recipient_transaction_id)
                || !empty($t->refund_transaction_id)
                || !empty($t->refund_status)) {
                return $this->ko('Annulation impossible : l\'expéditeur a été débité ou le destinataire est en cours de crédit.');
            }

            if (!empty($t->sender_transaction_id)) {
                $senderSvc = PixelInnoveService::find($t->sender_pixel_service_id);
                $state = $this->remoteStateWithService((string) $t->sender_transaction_id, $senderSvc);
                if ($state === null) {
                    return $this->ko('Impossible de vérifier le statut chez Pixel Innov. Réessayez dans un instant.');
                }
                if (in_array($state, ['SUCCESS', 'SUCCESSFUL'])) {
                    return $this->ko('Pixel Innov indique que l\'expéditeur a été débité : annulation refusée (utilisez la réconciliation).');
                }
            }

            $meta = (array) $t->metadata;
            $meta['cancelled_manually'] = true;
            $meta['cancelled_by']       = optional(Auth::guard('web')->user())->id;
            $meta['cancelled_at']       = now()->toDateTimeString();

            $t->update([
                'status'           => 'FAILED',
                'sender_status'    => 'FAILED',
                'recipient_status' => 'FAILED',
                'metadata'         => $meta,
            ]);

            Log::info('Mobile Zone Transfer — annulé manuellement', [
                'transfer_id' => $t->id,
                'by'          => $meta['cancelled_by'],
            ]);

            return ['ok' => true, 'message' => 'Transfert annulé.'];
        });
    }

    /** État chez Pixel : SUCCESS|FAILED|PENDING…, ou null si invérifiable. */
    private function remoteState(string $transactionId, $pixelServiceId): ?string
    {
        $svc = $pixelServiceId ? PixelInnoveService::find($pixelServiceId) : null;

        return $this->remoteStateWithService($transactionId, $svc);
    }

    private function remoteStateWithService(string $transactionId, ?PixelInnoveService $svc): ?string
    {
        $remote = app(PixelInnovService::class)
            ->setXaf($svc->is_xaf ?? false)
            ->checkTransactionStatus($transactionId);

        $state = strtoupper((string) ($remote['data']['state'] ?? ''));

        return $state === '' ? null : $state;
    }

    private function decode($metadata): array
    {
        $md = $metadata ?? [];
        for ($i = 0; $i < 2 && is_string($md); $i++) {
            $md = json_decode($md, true);
        }

        return is_array($md) ? $md : [];
    }

    private function ko(string $message): array
    {
        return ['ok' => false, 'message' => $message];
    }
}
