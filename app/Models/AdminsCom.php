<?php

namespace App\Models;

use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminsCom extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "admins_coms";
    protected $guarded = [];

    public function walletBank()
    {
        return $this->belongsTo(WalletBank::class, 'wallet_bank_id', 'id')->withTrashed();

    }

    public function walletDeposit()
    {
        return $this->belongsTo(WalletDeposit::class, 'wallet_deposit_id', 'id')->withTrashed();

    }

    public function orderCard()
    {
        return $this->belongsTo(BankCard::class, 'bank_card_id', 'id')->withTrashed();

    }

    public function walletInter()
    {
        return $this->belongsTo(WalletInter::class, 'wallet_inter_id', 'id')->withTrashed();

    }

    public function walletReload()
    {
        return $this->belongsTo(WalletReload::class, 'wallet_reload_id', 'id')->withTrashed();

    }
    public function walletPayment()
    {
        return $this->belongsTo(WalletPayment::class, 'wallet_payment_id', 'id')->withTrashed();

    }
    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class, 'wallet_transaction_id', 'id')->withTrashed();

    }

    public function walletPhoneCredit()
    {
        return $this->belongsTo(WalletPhoneCredit::class, 'wallet_phone_credit_id', 'id')->withTrashed();

    }

    public function sidUser()
    {

        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();

    }

}
