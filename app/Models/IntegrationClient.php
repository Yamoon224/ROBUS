<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Sid\SidUser;

class IntegrationClient extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'integration_clients';

    protected $fillable = [
        'company_name',
        'api_key',
        'api_secret',
        'webhook_url',
        'document',
        'piece_document',
        'return_url',
        'cancel_url',
        'is_active',
        'allowed_ips',
        'name_firm',
        'site_url',
        'sector_of_activity',
        'sid_user_id',
        'transaction_limit',
        'terms_accepted',
        'terms_accepted_at',
        'legal_form',
    ];

    protected $casts = [
        'allowed_ips' => 'array',
        'is_active' => 'boolean',
        'terms_accepted' => 'boolean',
        'terms_accepted_at' => 'datetime',
    ];

    public static function generateCredentials()
    {
        return [
            'api_key' => 'pk_' . Str::random(32),
            'api_secret' => 'sk_' . Str::random(64)
        ];
    }

    public function canMakeTransaction($amount)
    {
        return $this->is_active && ($this->transaction_limit == 0 || $amount <= $this->transaction_limit);
    }

    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }
}
