<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LedgerEntry extends Model
{
    use HasFactory;

    public const CATEGORY_ADMIN = 'admin';

    public const CATEGORY_COMMISSION = 'commission';

    public const CATEGORY_UV_COMMISSION = 'uv_commission';

    public const CATEGORY_UV_PURCHASE = 'uv_purchase';

    public const CATEGORY_STANDARD = 'standard';

    protected $table = 'ledger_entries';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'debit' => 'float',
        'credit' => 'float',
        'is_admin' => 'boolean',
        'is_comission' => 'boolean',
        'is_uv' => 'boolean',
        'is_wallet' => 'boolean',
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

    protected static function booted(): void
    {
        static::creating(function (LedgerEntry $entry) {
            if (! $entry->transaction_id) {
                $entry->transaction_id = (string) Str::uuid();
            }
        });
    }

    public function getCategoryAttribute(): string
    {
        if ($this->is_comission) {
            return self::CATEGORY_COMMISSION;
        }

        if ($this->is_admin) {
            return self::CATEGORY_ADMIN;
        }

        if ($this->is_uv) {
            return self::CATEGORY_UV_COMMISSION;
        }

        if ($this->is_wallet) {
            return self::CATEGORY_UV_PURCHASE;
        }

        return self::CATEGORY_STANDARD;
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            self::CATEGORY_COMMISSION => 'Commission banque',
            self::CATEGORY_ADMIN => 'Commission admin',
            self::CATEGORY_UV_COMMISSION => 'Commission achat UV',
            self::CATEGORY_UV_PURCHASE => 'Achat UV',
            default => 'Mouvement standard',
        };
    }

    public function getAmountAttribute(): float
    {
        return max((float) $this->debit, (float) $this->credit);
    }

    public function adminCom()
    {
        return $this->belongsTo(AdminsCom::class, 'admin_com_id', 'id')->withTrashed();
    }
}
