<?php

namespace App\Models\Wallets;

use App\Models\Company;
use App\Models\Currency;
use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use App\Models\Custumers\Country;
use App\Models\Wallets\WalletBank;
use Spatie\Activitylog\LogOptions;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;


    protected $table = 'wallets';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'company_id' => "integer",
        'status' => "boolean",
        'created_at' => 'datetime:d-m-Y H:i:s',
        'deleted_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id')->withTrashed();
    }
    public function currencie()
    {
        return $this->belongsTo(Currency::class, 'currencie_id', 'id')->withTrashed();
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }

    public function sidUserReload()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_relod', 'id')->withTrashed();
    }

    public function walletBanks()
    {
        return $this->hasMany(WalletBank::class);
    }

    public function walletDeposits()
    {
        return $this->hasMany(WalletDeposit::class);
    }

    public function orderCards()
    {
        return $this->hasMany(BankCard::class);
    }

    public function walletInters()
    {
        return $this->hasMany(WalletInter::class);
    }

    public function walletReloads()
    {
        return $this->hasMany(WalletReload::class);
    }
    public function walletPayments()
    {
        return $this->hasMany(WalletPayment::class);
    }
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function walletPushs()
    {
        return $this->hasMany(WalletPush::class);
    }

    public function walletBankSends()
    {
        return $this->hasMany(WalletBankSend::class);
    }

    public function walletDjamos()
    {
        return $this->hasMany(WalletDjamo::class);
    }

    public function walletTresortsMoneys()
    {
        return $this->hasMany(WalletTresortMoney::class);
    }


    public function walletXbets()
    {
        return $this->hasMany(WalletXbet::class);
    }

    public function walletWaves()
    {
        return $this->hasMany(WalletWave::class);
    }

    public function walletPhoneCredits()
    {
        return $this->hasMany(WalletPhoneCredit::class);
    }

    public function scopeMyWallet($query)
    {
        $user = Auth::guard('api')->user();
        if ($user->wallet->is_custumers == true) {
            return $query->where('is_custumers', $user->id);
        } else if ($user->wallet->is_distributer == true) {
            return $query->where('is_distributer', $user->id);
        } else if ($user->wallet->is_under_distributer == true) {
            return $query->where('is_under_distributer', $user->id);
        }

        return $query;
    }

    protected static function boot()
    {
        parent::boot();

        
        // static::forceDeleting(function ($wallet) {

        //     $wallet->walletBanks()->forceDelete();
        //     $wallet->walletDeposits()->forceDelete();
        //     $wallet->orderCards()->forceDelete();
        //     $wallet->walletInters()->forceDelete();
        //     $wallet->walletReloads()->forceDelete();
        //     $wallet->walletPayments()->forceDelete();
        //     $wallet->walletTransactions()->forceDelete();
        //     $wallet->walletPushs()->forceDelete();
        //     $wallet->walletBankSends()->forceDelete();
        //     $wallet->walletDjamos()->forceDelete();
        //     $wallet->walletTresortsMoneys()->forceDelete();
        //     $wallet->walletXbets()->forceDelete();
        //     $wallet->walletWaves()->forceDelete();
        //     $wallet->walletPhoneCredits()->forceDelete();
        // });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
