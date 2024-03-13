<?php

namespace App\Http\Services;


use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use App\Repositories\TransactionRepository;

class ValidateDailyLimitService
{

    /** @var  TransactionRepository */
    public $transctionRepository;

    public function __construct(TransactionRepository $transactionReposi)
    {
        $this->transctionRepository = $transactionReposi;
    }




    public function validateDailyLimit($som_transfers, $valeur_ceiling)
    {
        if ($som_transfers >= $valeur_ceiling) {
            return false;
        }

        return true;
    }

    public function validateAmountSendLimit($som_transfers, $valeur_ceiling)
    {
        if ($som_transfers > $valeur_ceiling) {
            return false;
        }

        return true;
    }

    public function validateAmount($transaction_amount_robus)
    {
        $val_amount = $transaction_amount_robus;

        if ($val_amount > robus()->wallet->amount) {
            return false;
        }

        return true;
    }


    public function validateAmountVirement($transaction_amount_robus)
    {
        $val_amount = $transaction_amount_robus;

        if ($val_amount > robus()->wallet->amount) {
            return false;
        }

        return true;
    }



    public function validateAmountDrawel($transaction_amount_robus, $num_wallet)
    {


        $wallet_search = Wallet::where('wallet_id',  $num_wallet)
            ->where('country_id',  robus()->country->id)
            ->whereStatus(true)
            ->first();

        $val_amount = $transaction_amount_robus;

        if ($val_amount > $wallet_search->amount) {
            return false;
        }


        //  Send message in phone
        return true;
    }


    public function getCeilingProperty()
    {
        $amount_days = $this->getWalletBanksCeilingProperty() + $this->getWalletDepositsCeilingProperty()
            + $this->getWalletIntersCeilingProperty() + $this->getWalletPaymentsCeilingProperty()
            + $this->getWalletTransactionsCeilingProperty() + $this->getWalletPhoneCreditsCeilingProperty()
            + $this->getWalletReloadsCeilingProperty()  + $this->getWalletBankCardCeilingProperty() + $this->getWalletWavesCeilingProperty()
            + $this->getWalletXbetsCeilingProperty();

        return  $amount_days;
    }
    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (BankCard::where('created_by', robus()->id)
            ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (WalletBank::where('created_by', robus()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getWalletWavesCeilingProperty()
    {
        $data = [];
        foreach (WalletWave::where('created_by', robus()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getWalletXbetsCeilingProperty()
    {
        $data = [];
        foreach (WalletXbet::where('created_by', robus()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function adminAllcountries()
    {
        return SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', robus()->country->id)->get();
    }

    public function adminCiv()
    {

        return  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();
    }
    public function getWalletDepositsCeilingProperty()
    {

        $data = [];

        foreach (WalletDeposit::where('created_by',  robus()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (WalletInter::where('created_by',  robus()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function validatedTransaction($code_validated)
    {
        return WalletTransaction::whereCodeValidated($code_validated)->first();
    }

    public function validatedWallet($num_wallet)
    {

        return Wallet::where('wallet_id', $num_wallet)->first();
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (WalletPayment::where('created_by',  robus()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }


    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (WalletTransaction::where('created_by',  robus()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];


        foreach (WalletPhoneCredit::where('created_by', robus()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (WalletReload::where('created_by',  robus()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function search_amount($amount_received)
    {

        // Replace commas or periods with a space
        $reel_amount = str_replace(['.', ','], '', $amount_received);

        // Convert the modified string to float
        $reel_amount = (float) $reel_amount;

        return $reel_amount;

        return $reel_amount;
    }
}
