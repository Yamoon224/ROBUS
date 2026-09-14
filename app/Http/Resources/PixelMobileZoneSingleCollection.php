<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PixelMobileZoneSingleCollection extends JsonResource
{
    public function toArray($request): array
    {
        $senderService   = optional($this->senderService);
        $recipientService = optional($this->recipientService);

        return [
            'id'                       => (int) $this->id,
            'type'                     => 'Envoi Mobile Zone',
            'description'              => 'Envoi de ' . ($this->sender_country_iso2 ?? '?')
                                          . ' vers ' . ($this->recipient_country_iso2 ?? '?'),
            'provider'                 => $senderService->provider->name ?? 'Pixel Innov',
            'reference'                => $this->sender_transaction_id ?? ('MZ-' . $this->id),

            // Montants
            'amount'                   => number_format((float) $this->amount, 2, '.', ''),
            'amount_received'          => number_format((float) $this->amount_received, 2, '.', ''),
            'sender_fee'               => number_format((float) $this->sender_fee, 2, '.', ''),
            'recipient_fee'            => number_format((float) $this->recipient_fee, 2, '.', ''),
            'total_fee'                => number_format((float) $this->total_fee, 2, '.', ''),
            'currency'                 => $this->currency ?? 'XOF',

            // Expéditeur
            'sender_phone'             => $this->sender_phone,
            'sender_country'           => $this->sender_country_iso2,
            'sender_operator'          => $senderService->provider->name ?? null,
            'sender_service_name'      => $senderService->name ?? null,

            // Destinataire
            'recipient_phone'          => $this->recipient_phone,
            'recipient_country'        => $this->recipient_country_iso2,
            'recipient_operator'       => $recipientService->provider->name ?? null,
            'recipient_service_name'   => $recipientService->name ?? null,

            // Statuts
            'status'                   => $this->status,
            'sender_status'            => $this->sender_status,
            'recipient_status'         => $this->recipient_status,

            // IDs Pixel Innov
            'sender_transaction_id'    => $this->sender_transaction_id,
            'recipient_transaction_id' => $this->recipient_transaction_id,

            // Soldes Pixel avant/après
            'pixel_wallet_before'      => $this->pixel_wallet_before !== null
                ? number_format((float) $this->pixel_wallet_before, 2, '.', '') . ' ' . ($this->currency ?? 'XOF')
                : null,
            'pixel_wallet_after'       => $this->pixel_wallet_after !== null
                ? number_format((float) $this->pixel_wallet_after, 2, '.', '') . ' ' . ($this->currency ?? 'XOF')
                : null,

            // Remboursement
            'refund_status'            => $this->refund_status,
            'refund_transaction_id'    => $this->refund_transaction_id,
            'refunded_at'              => $this->refunded_at,

            // Dates
            'date_created'             => $this->created_at,
            'date_treatment'           => $this->updated_at,
        ];
    }
}
