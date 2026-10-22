<?php

namespace App\Models;

use App\Models\Wallets\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PixelMobileZoneTransfer extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;

    protected $table = 'pixel_mobile_zone_transfers';

    protected $guarded = [];

    protected $casts = [
        'amount'          => 'float',
        'sender_fee'      => 'float',
        'recipient_fee'   => 'float',
        'total_fee'       => 'float',
        'amount_to_send'  => 'float',
        'amount_received' => 'float',
        'raw_response_sender'    => 'array',
        'raw_response_recipient' => 'array',
        'metadata'        => 'array',
    ];

    /**
     * Remboursement automatique abandonné (3 échecs) : intervention manuelle requise.
     */
    public function refundExhausted(): bool
    {
        $meta = (array) $this->metadata;

        return !empty($meta['refund_exhausted']) || (int) ($meta['refund_attempts'] ?? 0) >= 3;
    }

    public function senderService()
    {
        return $this->belongsTo(PixelInnoveService::class, 'sender_pixel_service_id');
    }

    public function recipientService()
    {
        return $this->belongsTo(PixelInnoveService::class, 'recipient_pixel_service_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}
