<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\Hash;

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
        'juridict_form',
        'front_document',
        'status',
        'return_url',
        'cancel_url',
        'is_active',
        'allowed_ips',
        'name_firm',
        'rate_limit',
        'site_url',
        'sector_of_activity',
        'sid_user_id',
        'transaction_limit',
        'terms_accepted',
        'terms_accepted_at',
        'legal_form',
        'validated_by', 
        'validated_at', 
        'webhook_secret'
    ];

    protected $casts = [
        'allowed_ips' => 'array',
        'is_active' => 'boolean',
        'terms_accepted' => 'boolean',
        'terms_accepted_at' => 'datetime',
    ];

    protected $hidden = [
        'api_secret',
    ];

    public static function generateCredentials()
    {
        return [
            'api_key' => 'pk_' . Str::random(32),
            'api_secret' => 'sk_' . Str::random(64),
            'webhook_secret' => 'whs_' . Str::random(32)
        ];
    }

      /**
     * Hash le secret API avant sauvegarde
     */
    public function setApiSecretAttribute($value)
    {
        // Ne pas re-hasher si déjà hashé
        if (!empty($value) && !Hash::needsRehash($value)) {
            $this->attributes['api_secret'] = $value;
        } else {
            $this->attributes['api_secret'] = Hash::make($value);
        }
    }

    /**
     * Hash le webhook secret avant sauvegarde
     */
    public function setWebhookSecretAttribute($value)
    {
        if (!empty($value) && !Hash::needsRehash($value)) {
            $this->attributes['webhook_secret'] = $value;
        } else {
            $this->attributes['webhook_secret'] = Hash::make($value);
        }
    }

    /**
     * Vérifie si le secret fourni correspond
     */
    public function verifySecret($plainSecret)
    {
        return Hash::check($plainSecret, $this->api_secret);
    }

    /**
     * Vérifie si le webhook secret correspond
     */
    public function verifyWebhookSecret($plainSecret)
    {
        return Hash::check($plainSecret, $this->webhook_secret);
    }

    /**
     * Vérifie si la transaction est autorisée
     */
    public function canMakeTransaction($amount)
    {
        return $this->is_active 
            && ($this->transaction_limit == 0 || $amount <= $this->transaction_limit);
    }

    /**
     * Vérifie si l'IP est autorisée
     */
    public function isIpAllowed($ip)
    {
        if (empty($this->allowed_ips)) {
            return true;
        }

        return in_array($ip, $this->allowed_ips);
    }

    /**
     * Met à jour le dernier usage
     */
   public function markAsUsed()
{
    $this->update(['last_used_at' => now()]);
}

    /**
     * Révoke les credentials
     */
    public function revoke()
    {
        $this->update([
            'is_active' => false,
            'api_secret' => Hash::make(Str::random(64)), 
        ]);
    }

    /**
     * Regénère les credentials
     */
    public function regenerateCredentials()
    {
        $credentials = self::generateCredentials();
        
        $this->update([
            'api_key' => $credentials['api_key'],
            'api_secret' => $credentials['api_secret'],
            'webhook_secret' => $credentials['webhook_secret'],
            'last_used_at' => null,
        ]);

        return $credentials;
    }


    /**
     * Scope pour les clients actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les clients avec IP restriction
     */
    public function scopeWithIpRestriction($query)
    {
        return $query->whereNotNull('allowed_ips')->where('allowed_ips', '!=', '[]');
    }


    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }
}
