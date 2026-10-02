<?php

namespace App\Models;

use App\Models\Banks\BankCard;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
// use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletCommission;
use App\Models\Wallets\WalletAdmin;
use App\Models\Wallets\WalletBankSend;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletWave;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminsCom extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

    protected $table = "admins_coms";
    protected $guarded = [];

    public function walletBank()
    {
        return $this->belongsTo(WalletBank::class, 'wallet_bank_id', 'id')->withTrashed();
    }

    public function walletVersement()
    {
        return $this->belongsTo(WalletVersement::class, 'wallet_versement_id', 'id')->withTrashed();
    }


    public function walletDeposit()
    {
        return $this->belongsTo(WalletDeposit::class, 'wallet_deposit_id', 'id')->withTrashed();
    }


    public function walletBankSend()
    {
        return $this->belongsTo(WalletBankSend::class, 'wallet_bank_send_id', 'id')->withTrashed();
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

    public function walletXbet()
    {
        return $this->belongsTo(WalletXbet::class, 'wallet_xbet_id', 'id')->withTrashed();
    }


    public function walletPush()
    {
        return $this->belongsTo(WalletPush::class, 'wallet_push_id', 'id')->withTrashed();
    }

    public function walletAdmin()
    {
        return $this->belongsTo(WalletAdmin::class, 'wallet_admin_id', 'id')->withTrashed();
    }

    public function walletCommission()
    {
        return $this->belongsTo(WalletCommission::class, 'wallet_commission_id', 'id')->withTrashed();
    }

    public function walletDjamo()
    {
        return $this->belongsTo(WalletDjamo::class, 'wallet_djamo_id', 'id')->withTrashed();
    }

    public function walletTresort()
    {
        return $this->belongsTo(WalletTresortMoney::class, 'wallet_tresort_money_id', 'id')->withTrashed();
    }

    public function walletWave()
    {
        return $this->belongsTo(WalletWave::class, 'wallet_wave_id', 'id')->withTrashed();
    }


    public function sidUser()
    {

        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }

    // Dans app/Models/AdminsCom.php

    public function getTypeAttribute()
    {

        if ($this->walletBank) return 'bank_transfer';
        if ($this->walletVersement) return 'versement';
        if ($this->walletDeposit) return 'deposit';
        if ($this->walletBankSend) return 'bank_send';
        if ($this->orderCard) return 'card_order';
        if ($this->walletInter) return 'inter_transfer';
        if ($this->walletReload) return 'reload';
        if ($this->walletPayment) return 'payment';
        if ($this->walletTransaction) return 'transaction';
        if ($this->walletPhoneCredit) return 'phone_credit';
        if ($this->walletXbet) return 'xbet';
        if ($this->walletPush) return 'push';
        if ($this->walletAdmin) return 'admin';
        if ($this->walletCommission) return 'commission';
        if ($this->walletDjamo) return 'djamo';
        if ($this->walletTresort) return 'tresort';
        if ($this->walletWave) return 'wave';


        return 'unknown';
    }

    // Version avec labels directement (optionnel)
    public function getTypeLabelAttribute()
    {
        $types = [
            'bank_transfer' => '🏦 Transfert bancaire',
            'versement' => '💰 Versement',
            'deposit' => '📥 Dépôt',
            'bank_send' => '📤 Envoi bancaire',
            'card_order' => '💳 Commande carte',
            'inter_transfer' => '🌍 Transfert international',
            'reload' => '🔄 Rechargement',
            'payment' => '💵 Paiement',
            'transaction' => '📊 Transaction',
            'phone_credit' => '📱 Crédit téléphonique',
            'xbet' => '🎰 1xBet',
            'push' => '📲 Push',
            'admin' => '👤 Admin',
            'commission' => '💸 Commission',
            'djamo' => '💳 Djamo',
            'tresort' => '💳 Tresort Money',
            'wave' => '🌊 Wave',
            'unknown' => '❓ Type inconnu'
        ];

        $type = $this->type;
        return $types[$type] ?? $type;
    }
}
