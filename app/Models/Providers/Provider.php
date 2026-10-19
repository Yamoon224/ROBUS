<?php

namespace App\Models\Providers;

use App\Models\User;
use App\Models\Company;
use App\Models\Sid\SidUser;
use App\Models\ProviderType;
use App\Models\Banks\BankCard;
use App\Models\Costs\OrderCardCost;
use App\Models\CountryService;
use App\Models\CountryWeb;
use App\Models\Custumers\Country;
use App\Models\Wallets\WalletBank;
use Spatie\Activitylog\LogOptions;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Provider extends Model
{
    protected static function booted()
    {
        // Achat d'UV : l'activation de Paystack désactive Pixel Innov.
        static::saved(function (self $provider) {
            if ($provider->wasChanged('status')) {
                \App\Support\UvPaymentSwitch::enforce();
            }
        });
    }

    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'providers';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'company_id' => "integer",
        'status' => "boolean",
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    // protected static $recordEvents = ['deleted'];

    public function country_services()
    {
        return $this->hasMany(CountryService::class, 'provider_id', 'id', 'country_id');
    }

    public function country_service_web()
    {
        return $this->hasMany(CountryWeb::class, 'provider_id', 'id', 'country_id');
    }

    public function order_cost()
    {
        return $this->hasMany(OrderCardCost::class);
    }


    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_services', 'country_id', 'provider_id');
    }

    public function scopeAvailableInCountry(Builder $query, int $countryId): Builder
    {
        return $query->where('providers.status', true)
            ->whereHas('country_services', function (Builder $countryServices) use ($countryId) {
                $countryServices->where('country_id', $countryId)
                    ->where('status', true);
            });
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id')->withTrashed();
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function providerType()
    {
        return $this->belongsTo(ProviderType::class, 'provider_type_id', 'id')->withTrashed();
    }

    public function walletReloads()
    {
        return $this->hasMany(WalletReload::class);
    }

    public function cards()
    {
        return $this->hasMany(BankCard::class);
    }

    public function walletPaiements()
    {
        return $this->hasMany(WalletPayment::class);
    }

    public function walletPhoneCredits()
    {
        return $this->hasMany(WalletPhoneCredit::class);
    }
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
    public function walletInters()
    {
        return $this->hasMany(WalletInter::class);
    }
    public function walletDeposits()
    {
        return $this->hasMany(WalletDeposit::class);
    }

    public function walletBanks()
    {
        return $this->hasMany(WalletBank::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
