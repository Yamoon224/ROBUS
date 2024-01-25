<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminsCom;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\Costs\TransactionCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\PaymentCost;
use App\Models\Costs\DepositCost;
use App\Models\Costs\BankCost;
use App\Models\LevelCountry;
use App\Models\Custumers\Country;

class Gain extends Component
{


    use WithPagination;

    public $search = "";

    public $typeFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public  $groupe_countrie =  "";
    public $statusTransferFilter = "";

    public function render()
    {
        $gains = AdminsCom::query();

        if ($this->startDate && $this->endDate) {

            // $gains->where('description', '!=', 'Validation distributeur')->whereBetween('created_at', [$this->startDate, $this->endDate]);

            $banqueSum_percent =  $this->WalletIntersDays;


            return view('livewire.gain', [
                "gains" => $gains->where('description', '!=', 'Validation distributeur')->orderBy('created_at', 'desc')->whereBetween('created_at', [$this->startDate, $this->endDate])->latest()->paginate(30),
                "sum_gains" => $this->GainAdminsDays,
                "sum_gain_days" => $this->GainAdminDate,
                "sum_gain_drawal_days" =>  $this->GainAdminTransactionsDays,
                "sum_gain_banques" =>  $banqueSum_percent,

            ]);
        }
        if ($this->search != "") {

            $this->resetPage();

            $gains->where("description", "LIKE",  "%" . $this->search . "%")->orWhere("amount", "LIKE",  "%" . $this->search . "%");
        }


        if ($this->statusTransferFilter != "") {


            $banqueSum_percent =  $this->WalletIntersStatuts;

            return view('livewire.gain', [
                "gains" => $gains->where('description', '!=', 'Validation distributeur')->where("status", $this->statusTransferFilter)->orderBy('created_at', 'desc')->paginate(30),
                "sum_gains" => $this->GainAdminsDays,
                "sum_gain_days" => $this->GainAdminStatut,
                "sum_gain_drawal_days" =>  $this->GainAdminTransactionsDays,
                "sum_gain_banques" =>  $banqueSum_percent,

            ]);
        }


        if ($this->typeFilter != "") {
            $gains->where("country_id", $this->typeFilter);
        }


        $banqueSum_percent = $this->WalletInters;

        return view('livewire.gain', [
            "gains" => $gains->where('description', '!=', 'Validation distributeur')->orderBy('created_at', 'desc')->latest()->paginate(30),
            "sum_gains" => $this->GainAdmins,
            "sum_gain_days" => $this->GainAdminTodays,
            "sum_gain_drawal_days" =>  $this->GainAdminTransactionsTodays,
            "sum_gain_banques" =>  $banqueSum_percent,
        ]);
    }

    // Wallet Transactions
    public function getWalletTrantionsactionsProperty()
    {


        $walletTransactions = AdminsCom::join('wallet_transactions', 'admins_coms.wallet_transaction_id', '=', 'wallet_transactions.id')
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletTransactions as $wallet) {
            $sidUser = $wallet->walletTransaction->wallet->sidUser;
            $countrieUser = $sidUser->country->name_groupe;
            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageDistributers($countrieUser);
            } else {
                $percent_robus = $this->getRobusPercentageCustumers($countrieUser);
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    public function getWalletTrantionsactionsDaysProperty()
    {


        $walletTransactions = AdminsCom::join('wallet_transactions', 'admins_coms.wallet_transaction_id', '=', 'wallet_transactions.id')
            ->whereBetween('admins_coms.created_at', [$this->startDate, $this->endDate])
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletTransactions as $wallet) {
            $sidUser = $wallet->walletTransaction->wallet->sidUser;
            $countrieUser = $sidUser->country->name_groupe;
            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageDistributers($countrieUser);
            } else {
                $percent_robus = $this->getRobusPercentageCustumers($countrieUser);
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    public function getWalletTrantionsactionsStatutsProperty()
    {


        $walletTransactions = AdminsCom::join('wallet_transactions', 'admins_coms.wallet_transaction_id', '=', 'wallet_transactions.id')
            ->where("admins_coms.status", $this->statusTransferFilter)
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletTransactions as $wallet) {
            $sidUser = $wallet->walletTransaction->wallet->sidUser;
            $countrieUser = $sidUser->country->name_groupe;
            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageDistributers($countrieUser);
            } else {
                $percent_robus = $this->getRobusPercentageCustumers($countrieUser);
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }
    // Wallet phone credit

    public function getWalletPhonesProperty()
    {


        $walletPhoneCredits = AdminsCom::join('wallet_phone_credits', 'admins_coms.wallet_phone_credit_id', '=', 'wallet_phone_credits.id')
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletPhoneCredits as $wallet) {
            $sidUser = $wallet->walletPhoneCredit->wallet->sidUser;
            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentagePhoneProperty();
            } else {
                $percent_robus = $this->getRobusPercentagePhoneProperty();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    public function getWalletPhonesDaysProperty()
    {


        $walletPhoneCredits = AdminsCom::join('wallet_phone_credits', 'admins_coms.wallet_phone_credit_id', '=', 'wallet_phone_credits.id')
            ->whereBetween('admins_coms.created_at', [$this->startDate, $this->endDate])
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletPhoneCredits as $wallet) {
            $sidUser = $wallet->walletPhoneCredit->wallet->sidUser;
            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentagePhoneProperty();
            } else {
                $percent_robus = $this->getRobusPercentagePhoneProperty();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    public function getWalletPhonesStatutsProperty()
    {


        $walletPhoneCredits = AdminsCom::join('wallet_phone_credits', 'admins_coms.wallet_phone_credit_id', '=', 'wallet_phone_credits.id')
            ->where("admins_coms.status", $this->statusTransferFilter)
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletPhoneCredits as $wallet) {
            $sidUser = $wallet->walletPhoneCredit->wallet->sidUser;
            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentagePhoneProperty();
            } else {
                $percent_robus = $this->getRobusPercentagePhoneProperty();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }


    // Wallet payments

    public function getWalletPaymentsProperty()
    {


        $walletPayments = AdminsCom::join('wallet_payments', 'admins_coms.wallet_payment_id', '=', 'wallet_payments.id')
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletPayments as $wallet) {

            $sidUser = $wallet->walletPayment->wallet->sidUser;

            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentagePayment();
            } else {
                $percent_robus = $this->getRobusPercentagePayment();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    public function getWalletPaymentsDaysProperty()
    {


        $walletPayments = AdminsCom::join('wallet_payments', 'admins_coms.wallet_payment_id', '=', 'wallet_payments.id')
            ->whereBetween('admins_coms.created_at', [$this->startDate, $this->endDate])
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletPayments as $wallet) {

            $sidUser = $wallet->walletPayment->wallet->sidUser;

            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentagePayment();
            } else {
                $percent_robus = $this->getRobusPercentagePayment();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    public function getWalletPaymentsStatutsProperty()
    {


        $walletPayments = AdminsCom::join('wallet_payments', 'admins_coms.wallet_payment_id', '=', 'wallet_payments.id')
            ->where("admins_coms.status", $this->statusTransferFilter)
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletPayments as $wallet) {

            $sidUser = $wallet->walletPayment->wallet->sidUser;

            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentagePayment();
            } else {
                $percent_robus = $this->getRobusPercentagePayment();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }
    // Wallet deposits
    public function getWalletDepositsProperty()
    {


        $walletDeposits = AdminsCom::join('wallet_deposits', 'admins_coms.wallet_deposit_id', '=', 'wallet_deposits.id')
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletDeposits as $wallet) {

            $sidUser = $wallet->walletDeposit->wallet->sidUser;

            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageReloadOrder();
            } else {
                $percent_robus = $this->getRobusPercentageReloadOrder();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    public function getWalletDepositsDaysProperty()
    {


        $walletDeposits = AdminsCom::join('wallet_deposits', 'admins_coms.wallet_deposit_id', '=', 'wallet_deposits.id')
            ->whereBetween('admins_coms.created_at', [$this->startDate, $this->endDate])
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletDeposits as $wallet) {

            $sidUser = $wallet->walletDeposit->wallet->sidUser;

            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageReloadOrder();
            } else {
                $percent_robus = $this->getRobusPercentageReloadOrder();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    public function getWalletDepositsStatutsProperty()
    {


        $walletDeposits = AdminsCom::join('wallet_deposits', 'admins_coms.wallet_deposit_id', '=', 'wallet_deposits.id')
            ->where("admins_coms.status", $this->statusTransferFilter)
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletDeposits as $wallet) {

            $sidUser = $wallet->walletDeposit->wallet->sidUser;

            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageReloadOrder();
            } else {
                $percent_robus = $this->getRobusPercentageReloadOrder();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    // wallet Banks
    public function getWalletBanksProperty()
    {


        $walletBanks = AdminsCom::join('wallet_banks', 'admins_coms.wallet_bank_id', '=', 'wallet_banks.id')
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletBanks as $wallet) {

            $sidUser = $wallet->walletBank->wallet->sidUser;

            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageVirementSDistributers();
            } else {
                $percent_robus = $this->getRobusPercentageVirementCustumers();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }


    public function getWalletBanksDaysProperty()
    {


        $walletBanks = AdminsCom::join('wallet_banks', 'admins_coms.wallet_bank_id', '=', 'wallet_banks.id')
            ->whereBetween('admins_coms.created_at', [$this->startDate, $this->endDate])
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletBanks as $wallet) {

            $sidUser = $wallet->walletBank->wallet->sidUser;

            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageVirementSDistributers();
            } else {
                $percent_robus = $this->getRobusPercentageVirementCustumers();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }


    public function getWalletBanksStatutsProperty()
    {


        $walletBanks = AdminsCom::join('wallet_banks', 'admins_coms.wallet_bank_id', '=', 'wallet_banks.id')
            ->where("admins_coms.status", $this->statusTransferFilter)
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletBanks as $wallet) {

            $sidUser = $wallet->walletBank->wallet->sidUser;

            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageVirementSDistributers();
            } else {
                $percent_robus = $this->getRobusPercentageVirementCustumers();
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }

    // Wallet Inters

    public function getWalletIntersProperty()
    {


        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $walletBanks = AdminsCom::join('wallet_inters', 'admins_coms.wallet_inter_id', '=', 'wallet_inters.id')
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();


        $percent_amount = $walletBanks->map(function ($wallet) {

            $amount_send = $wallet->walletInter->amount;

            $country_user = $wallet->walletInter->wallet;

            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            $amount_banque = $this->getConvertAdminCommission($amount_send, $country_user);

            return optional($percent_robus)->percent_banque * $amount_banque;
        });

        return $percent_amount->sum();
    }

    public function getWalletIntersDaysProperty()
    {
        $walletBanks = AdminsCom::join('wallet_inters', 'admins_coms.wallet_inter_id', '=', 'wallet_inters.id')
            ->whereBetween('admins_coms.created_at', [$this->startDate, $this->endDate])
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();


        $percent_amount = $walletBanks->map(function ($wallet) {

            $amount_send = $wallet->walletInter->amount;

            $country_user = $wallet->walletInter->wallet;

            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            $amount_banque = $this->getConvertAdminCommission($amount_send, $country_user);

            return optional($percent_robus)->percent_banque * $amount_banque;
        });

        return $percent_amount->sum();
    }


    public function getWalletIntersStatutsProperty()
    {
        $walletBanks = AdminsCom::join('wallet_inters', 'admins_coms.wallet_inter_id', '=', 'wallet_inters.id')
            ->where("admins_coms.status", $this->statusTransferFilter)
            ->select('admins_coms.*')
            ->get();


        $percent_amount = $walletBanks->map(function ($wallet) {

            $amount_send = $wallet->walletInter->amount;

            $country_user = $wallet->walletInter->wallet;

            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            $amount_banque = $this->getConvertAdminCommission($amount_send, $country_user);

            return optional($percent_robus)->percent_banque * $amount_banque;
        });

        return $percent_amount->sum();
    }

    protected function getRobusPercentageForWalletInter($wallet)
    {
        $sidUser = $wallet->walletInter->wallet->sidUser;
        $countrieGroupe = $sidUser->country->name_groupe;
        $countrieUserId = $wallet->walletInter->country;
        $countrieUserSend = $wallet->walletInter->wallet->country->id;
        $providerSendUser = $wallet->walletInter->provider_id;
        $typeProviderSendUser = $wallet->walletInter->provider_type;

        if ($typeProviderSendUser == 1 && $providerSendUser != 51) {
            return $this->getRobusPercentageInter($countrieGroupe, $countrieUserId, $providerSendUser, $countrieUserSend);
        }

        return null;
    }

    // amount inters
    public function getConvertAdminCommission($amount, $countrie_send)
    {
        $country_civ = Country::find(107);

        $robus_currency = $countrie_send->country->currencie->value;
        if ($country_civ->currencie->value > $countrie_send->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == $countrie_send->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }


    // Percent valeurs Transactions
    public function getRobusPercentageInter($groupe_name, $countrie, $provider_valeur, $countryId)
    {

        $levelValeur = 0;

        if ($groupe_name == 'AFL') {

            $countriesLevels = [
                // Is Benin
                25 => [
                    36 => ($provider_valeur != 51) ? 2 : 37, // BF
                    133 => ($provider_valeur != 51) ? 3 : 38, // Mali
                    214 => ($provider_valeur != 51) ? 4 : 39, // Togo
                    188 => ($provider_valeur != 51) ? 5 : 40, // Senegal
                    83 => ($provider_valeur != 51) ? 6 : 41, // Ghana
                    157 => ($provider_valeur != 51) ? 7 : 42, // Niger
                    91 => ($provider_valeur != 51) ? 8 : 43, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 1 : 44, // CIV
                    1 => ($provider_valeur != 51) ? 248 : 241, // USA
                    218 => ($provider_valeur != 51) ? 264 : 292, // Tunisia
                    146 => ($provider_valeur != 51) ? 278 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 435 : 312, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 467 : 355, // France
                    179 => ($provider_valeur != 51) ? 531 : 419, // Russie
                    226 => ($provider_valeur != 51) ? 499 : 387, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 483 : 371, // Allemagne
                    39 => ($provider_valeur != 51) ? 129 : 178, // Cameroun
                    79 => ($provider_valeur != 51) ? 130 : 179, // Gabon
                    50 => ($provider_valeur != 51) ? 131 : 180, // COngo Braza
                    57 => ($provider_valeur != 51) ? 132 : 181, // RDC
                    122 => ($provider_valeur != 51) ? 133 : 182, // Liberia
                    43 => ($provider_valeur != 51) ? 134 : 183, // Thacd
                    42 => ($provider_valeur != 51) ? 135 : 184, // Afrique centrale
                ],
                // Is BF
                36 =>  [
                    25 => ($provider_valeur != 51) ? 2 : 37, // Benin
                    133 => ($provider_valeur != 51) ? 9 : 45, // Mali
                    214 => ($provider_valeur != 51) ? 10 : 46, // Togo
                    188 => ($provider_valeur != 51) ? 11 : 47, // Senegal
                    83 => ($provider_valeur != 51) ? 12 : 48, // Ghana
                    157 => ($provider_valeur != 51) ? 13 : 49, // Niger
                    91 => ($provider_valeur != 51) ? 14 : 50, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 15 : 51, // CIV
                    1 => ($provider_valeur != 51) ? 249 : 242, // USA
                    218 => ($provider_valeur != 51) ? 265 : 293, // Tunisia
                    146 => ($provider_valeur != 51) ? 279 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 436 : 313, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 468 : 356, // France
                    179 => ($provider_valeur != 51) ? 532 : 420, // Russie
                    226 => ($provider_valeur != 51) ? 500 : 388, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 516 : 404, // Italie
                    82 => ($provider_valeur != 51) ? 484 : 372, // Allemagne
                    39 => ($provider_valeur != 51) ? 136 : 185, // Cameroun
                    79 => ($provider_valeur != 51) ? 137 : 186, // Gabon
                    50 => ($provider_valeur != 51) ? 138 : 187, // COngo Braza
                    57 => ($provider_valeur != 51) ? 139 : 188, // RDC
                    122 => ($provider_valeur != 51) ? 140 : 189, // Liberia
                    43 => ($provider_valeur != 51) ? 141 : 190, // Thacd
                    42 => ($provider_valeur != 51) ? 142 : 191, // Afrique centrale
                ],
                // Mali
                133 =>  [
                    25 => ($provider_valeur != 51) ? 3 : 38, // Benin
                    214 => ($provider_valeur != 51) ? 16 : 52, // Togo
                    36 => ($provider_valeur != 51) ? 9 : 45, // BF
                    188 => ($provider_valeur != 51) ? 17 : 53, // Senegal
                    83 => ($provider_valeur != 51) ? 18 : 54, // Ghana
                    157 => ($provider_valeur != 51) ? 19 : 55, // Niger
                    91 => ($provider_valeur != 51) ? 20 : 56, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 21 : 57, // CIV
                    1 => ($provider_valeur != 51) ? 570 : 243, // USA
                    218 => ($provider_valeur != 51) ? 574 : 294, // Tunisia
                    146 => ($provider_valeur != 51) ? 575 : 303, // Maroc
                    199 => ($provider_valeur != 51) ? 437 : 314, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 469 : 357, // France
                    179 => ($provider_valeur != 51) ? 533 : 421, // Russie
                    226 => ($provider_valeur != 51) ? 501 : 389, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 517 : 405, // Italie
                    82 => ($provider_valeur != 51) ? 485 : 373, // Allemagne
                    39 => ($provider_valeur != 51) ? 143 : 192, // Cameroun
                    79 => ($provider_valeur != 51) ? 144 : 193, // Gabon
                    50 => ($provider_valeur != 51) ? 145 : 194, // COngo Braza
                    57 => ($provider_valeur != 51) ? 146 : 195, // RDC
                    122 => ($provider_valeur != 51) ? 147 : 196, // Liberia
                    43 => ($provider_valeur != 51) ? 148 : 197, // Thacd
                    42 => ($provider_valeur != 51) ? 149 : 198, // Afrique centrale
                ],
                // Togo
                214 =>  [
                    25 => ($provider_valeur != 51) ?  4 : 39, // Benin
                    188 => ($provider_valeur != 51) ? 22 : 58, // Senegal
                    83 => ($provider_valeur != 51) ? 23 : 59, // Ghana
                    36 => ($provider_valeur != 51) ? 10 : 46, // BF
                    133 => ($provider_valeur != 51) ? 16 : 52, // Mali
                    157 => ($provider_valeur != 51) ? 24 : 60, // Niger
                    91 => ($provider_valeur != 51) ? 25 : 61, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 26 : 62, // CIV
                    1 => ($provider_valeur != 51) ? 250 : 244, // USA
                    218 => ($provider_valeur != 51) ? 266 : 295, // Tunisia
                    146 => ($provider_valeur != 51) ? 280 : 304, // Maroc
                    199 => ($provider_valeur != 51) ? 438 : 315, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 470 : 358, // France
                    179 => ($provider_valeur != 51) ? 534 : 422, // Russie
                    226 => ($provider_valeur != 51) ? 502 : 390, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 518 : 406, // Italie
                    82 => ($provider_valeur != 51) ? 486 : 374, // Allemagne
                    39 => ($provider_valeur != 51) ? 150 : 199, // Cameroun
                    79 => ($provider_valeur != 51) ? 151 : 200, // Gabon
                    50 => ($provider_valeur != 51) ? 152 : 201, // COngo Braza
                    57 => ($provider_valeur != 51) ? 153 : 202, // RDC
                    122 => ($provider_valeur != 51) ? 154 : 203, // Liberia
                    43 => ($provider_valeur != 51) ? 155 : 204, // Thacd
                    42 => ($provider_valeur != 51) ? 156 : 205, // Afrique centrale
                ],
                // Senegal
                188 =>  [

                    25 => ($provider_valeur != 51) ?  5 : 40, // Benin
                    83 => ($provider_valeur != 51) ? 27 : 63, // Ghana
                    133 => ($provider_valeur != 51) ? 28 : 64, // Niger
                    36 => ($provider_valeur != 51) ? 11 : 47, // BF
                    188 => ($provider_valeur != 51) ? 17 : 53, // Mali
                    214 => ($provider_valeur != 51) ? 22 : 58, // TOgo
                    91 => ($provider_valeur != 51) ? 29 : 65, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 30 : 66, // CIV
                    1 => ($provider_valeur != 51) ? 251 : 245, // USA
                    218 => ($provider_valeur != 51) ? 267 : 296, // Tunisia
                    146 => ($provider_valeur != 51) ? 281 : 305, // Maroc
                    199 => ($provider_valeur != 51) ? 439 : 316, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 471 : 359, // France
                    179 => ($provider_valeur != 51) ? 535 : 423, // Russie
                    226 => ($provider_valeur != 51) ? 503 : 391, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 519 : 407, // Italie
                    82 => ($provider_valeur != 51) ? 487 : 375, // Allemagne
                    39 => ($provider_valeur != 51) ? 157 : 206, // Cameroun
                    79 => ($provider_valeur != 51) ? 158 : 207, // Gabon
                    50 => ($provider_valeur != 51) ? 159 : 208, // COngo Braza
                    57 => ($provider_valeur != 51) ? 160 : 209, // RDC
                    122 => ($provider_valeur != 51) ? 161 : 210, // Liberia
                    43 => ($provider_valeur != 51) ? 162 : 211, // Thacd
                    42 => ($provider_valeur != 51) ? 163 : 212, // Afrique centrale
                ],
                // Ghana
                83 =>  [
                    25 => ($provider_valeur != 51) ?  6 : 41, // Benin
                    188 => ($provider_valeur != 51) ? 27 : 63, // Senegal
                    157 => ($provider_valeur != 51) ? 31 : 67, // Niger
                    36 => ($provider_valeur != 51) ? 12 : 48, // bf
                    133 => ($provider_valeur != 51) ? 18 : 54, // Mali
                    214 => ($provider_valeur != 51) ? 23 : 59, // Ghana
                    91 => ($provider_valeur != 51) ? 32 : 68, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 33 : 69, // CIV
                    1 => ($provider_valeur != 51) ? 252 : 571, // USA
                    218 => ($provider_valeur != 51) ? 268 : 297, // Tunisia
                    146 => ($provider_valeur != 51) ? 282 : 306, // Maroc
                    199 => ($provider_valeur != 51) ? 440 : 317, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 472 : 360, // France
                    179 => ($provider_valeur != 51) ? 536 : 424, // Russie
                    226 => ($provider_valeur != 51) ? 504 : 392, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 520 : 408, // Italie
                    82 => ($provider_valeur != 51) ? 488 : 376, // Allemagne
                    39 => ($provider_valeur != 51) ? 164 : 213, // Cameroun
                    79 => ($provider_valeur != 51) ? 165 : 214, // Gabon
                    50 => ($provider_valeur != 51) ? 166 : 215, // COngo Braza
                    57 => ($provider_valeur != 51) ? 167 : 216, // RDC
                    122 => ($provider_valeur != 51) ? 168 : 217, // Liberia
                    43 => ($provider_valeur != 51) ? 169 : 218, // Thacd
                    42 => ($provider_valeur != 51) ? 170 : 219, // Afrique centrale
                ],
                // Niger
                157 =>  [
                    25 => ($provider_valeur != 51) ? 7 : 42, // Benin
                    188 => ($provider_valeur != 51) ? 28 : 64, // Senegal
                    91 => ($provider_valeur != 51) ? 34 : 70, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 35 : 71, // CIV
                    214 => ($provider_valeur != 51) ? 24 : 60, // Togo
                    1 => ($provider_valeur != 51) ? 253 : 246, // USA
                    36 => ($provider_valeur != 51) ? 13 : 49, // BF
                    133 => ($provider_valeur != 51) ? 19 : 55, // Mali
                    218 => ($provider_valeur != 51) ? 269 : 298, // Tunisia
                    146 => ($provider_valeur != 51) ? 283 : 307, // Maroc
                    199 => ($provider_valeur != 51) ? 441 : 318, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 473 : 361, // France
                    179 => ($provider_valeur != 51) ? 537 : 425, // Russie
                    226 => ($provider_valeur != 51) ? 505 : 393, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 521 : 409, // Italie
                    82 => ($provider_valeur != 51) ? 489 : 377, // Allemagne
                    39 => ($provider_valeur != 51) ? 576 : 220, // Cameroun
                    79 => ($provider_valeur != 51) ? 577 : 221, // Gabon
                    50 => ($provider_valeur != 51) ? 578 : 222, // COngo Braza
                    57 => ($provider_valeur != 51) ? 579 : 223, // RDC
                    122 => ($provider_valeur != 51) ? 580 : 224, // Liberia
                    43 => ($provider_valeur != 51) ? 581 : 225, // Thacd
                    42 => ($provider_valeur != 51) ? 582 : 226, // Afrique centrale
                ],
                // Guinnée Conakry
                91 =>  [
                    25 => ($provider_valeur != 51) ? 7 : 42, // Benin
                    188 => ($provider_valeur != 51) ? 29 : 65, // Senegal
                    157 => ($provider_valeur != 51) ? 34 : 70, // Niger
                    83 => ($provider_valeur != 51) ?  32 : 68, // Ghana
                    36 => ($provider_valeur != 51) ? 14 : 50, //BF
                    133 => ($provider_valeur != 51) ? 20 : 56, // Mali
                    107 => ($provider_valeur != 51) ? 36 : 72, // CIV
                    214 => ($provider_valeur != 51) ? 25 : 61, //Togo
                    1 => ($provider_valeur != 51) ? 253 : 246, // USA
                    36 => ($provider_valeur != 51) ? 14 : 50, // BF
                    218 => ($provider_valeur != 51) ? 270 : 299, // Tunisia
                    146 => ($provider_valeur != 51) ? 284 : 308, // Maroc
                    199 => ($provider_valeur != 51) ? 442 : 319, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 474 : 362, // France
                    179 => ($provider_valeur != 51) ? 538 : 426, // Russie
                    226 => ($provider_valeur != 51) ? 506 : 394, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 522 : 410, // Italie
                    82 => ($provider_valeur != 51) ? 490 : 378, // Allemagne
                    39 => ($provider_valeur != 51) ? 171 : 227, // Cameroun
                    79 => ($provider_valeur != 51) ? 172 : 228, // Gabon
                    50 => ($provider_valeur != 51) ? 173 : 229, // COngo Braza
                    57 => ($provider_valeur != 51) ? 174 : 230, // RDC
                    122 => ($provider_valeur != 51) ? 175 : 231, // Liberia
                    43 => ($provider_valeur != 51) ? 176 : 232, // Thacd
                    42 => ($provider_valeur != 51) ? 177 : 233, // Afrique centrale
                ]

                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif ($groupe_name == 'AFC') {

            $countriesLevels = [
                // Is Cameroun
                39 => [
                    25 => ($provider_valeur != 51) ? 129 : 178, // Benin
                    36 => ($provider_valeur != 51) ? 136 : 185, // BF
                    133 => ($provider_valeur != 51) ? 143 : 192, // Mali
                    214 => ($provider_valeur != 51) ? 150 : 199, // Togo
                    188 => ($provider_valeur != 51) ? 157 : 206, // Senegal
                    83 => ($provider_valeur != 51) ? 164 : 213, // Ghana
                    157 => ($provider_valeur != 51) ? 576 : 220, // Niger
                    91 => ($provider_valeur != 51) ? 171 : 227, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 79 : 107, // CIV
                    1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    218 => ($provider_valeur != 51) ? 271 : 327, // Tunisia
                    146 => ($provider_valeur != 51) ? 285 : 333, // Maroc
                    199 => ($provider_valeur != 51) ? 444 : 321, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 476 : 364, // France
                    179 => ($provider_valeur != 51) ? 540 : 428, // Russie
                    226 => ($provider_valeur != 51) ? 508 : 396, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 524 : 412, // Allemagne
                    // 39 => ($provider_valeur != 51) ? 129 : 178, // Cameroun
                    79 => ($provider_valeur != 51) ? 73 : 101, // Gabon
                    50 => ($provider_valeur != 51) ? 74 : 102, // COngo Braza
                    57 => ($provider_valeur != 51) ? 75 : 103, // RDC
                    122 => ($provider_valeur != 51) ? 76 : 104, // Liberia
                    43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 78 : 106, // Afrique centrale
                ],
                // Gabon
                79 =>  [
                    25 => ($provider_valeur != 51) ? 130 : 179, // Benin
                    36 => ($provider_valeur != 51) ? 137 : 186, // BF
                    133 => ($provider_valeur != 51) ? 144 : 193, // Mali
                    214 => ($provider_valeur != 51) ? 151 : 200, // Togo
                    188 => ($provider_valeur != 51) ? 158 : 207, // Senegal
                    83 => ($provider_valeur != 51) ? 165 : 214, // Ghana
                    157 => ($provider_valeur != 51) ? 577 : 221, // Niger
                    91 => ($provider_valeur != 51) ? 173 : 229, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 85 : 113, // CIV
                    1 => ($provider_valeur != 51) ? 256 : 235, // USA
                    218 => ($provider_valeur != 51) ? 272 : 328, // Tunisia
                    146 => ($provider_valeur != 51) ? 286 : 334, // Maroc
                    199 => ($provider_valeur != 51) ? 445 : 322, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 477 : 365, // France
                    179 => ($provider_valeur != 51) ? 541 : 429, // Russie
                    226 => ($provider_valeur != 51) ? 509 : 397, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 525 : 413, // Italie
                    82 => ($provider_valeur != 51) ? 493 : 381, // Allemagne
                    39 => ($provider_valeur != 51) ? 73 : 101, // Cameroun
                    // 79 => ($provider_valeur != 51) ? 137 : 186, // Gabon
                    50 => ($provider_valeur != 51) ? 80 : 108, // COngo Braza
                    57 => ($provider_valeur != 51) ? 81 : 109, // RDC
                    122 => ($provider_valeur != 51) ? 82 : 110, // Liberia
                    43 => ($provider_valeur != 51) ? 83 : 111, // Thacd
                    42 => ($provider_valeur != 51) ? 84 : 112, // Afrique centrale
                ],
                // Congo Brazaville
                50 =>  [
                    25 => ($provider_valeur != 51) ?  131 : 180, // Benin
                    214 => ($provider_valeur != 51) ? 152 : 201, // Togo
                    36 => ($provider_valeur != 51) ? 138 : 187, // BF
                    188 => ($provider_valeur != 51) ? 159 : 208, // Senegal
                    83 => ($provider_valeur != 51) ? 166 : 215, // Ghana
                    157 => ($provider_valeur != 51) ? 578 : 222, // Niger
                    91 => ($provider_valeur != 51) ? 173 : 229, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 90 : 118, // CIV
                    1 => ($provider_valeur != 51) ? 570 : 243, // USA
                    218 => ($provider_valeur != 51) ? 574 : 294, // Tunisia
                    146 => ($provider_valeur != 51) ? 575 : 303, // Maroc
                    199 => ($provider_valeur != 51) ? 437 : 314, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 469 : 357, // France
                    179 => ($provider_valeur != 51) ? 533 : 421, // Russie
                    226 => ($provider_valeur != 51) ? 501 : 389, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 517 : 405, // Italie
                    82 => ($provider_valeur != 51) ? 485 : 373, // Allemagne
                    39 => ($provider_valeur != 51) ? 143 : 192, // Cameroun
                    79 => ($provider_valeur != 51) ? 144 : 193, // Gabon
                    // 50 => ($provider_valeur != 51) ? 145 : 194, // COngo Braza
                    57 => ($provider_valeur != 51) ? 86 : 114, // RDC
                    122 => ($provider_valeur != 51) ? 87 : 115, // Liberia
                    43 => ($provider_valeur != 51) ? 88 : 116, // Thacd
                    42 => ($provider_valeur != 51) ? 89 : 117, // Afrique centrale
                ],
                // RDC
                57 =>  [
                    25 => ($provider_valeur != 51) ?  132 : 181, // Benin
                    188 => ($provider_valeur != 51) ? 160 : 209, // Senegal
                    83 => ($provider_valeur != 51) ? 167 : 216, // Ghana
                    36 => ($provider_valeur != 51) ? 139 : 188, // BF
                    133 => ($provider_valeur != 51) ? 146 : 195, // Mali
                    157 => ($provider_valeur != 51) ? 579 : 223, // Niger
                    91 => ($provider_valeur != 51) ? 174 : 230, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 94 : 122, // CIV
                    1 => ($provider_valeur != 51) ? 258 : 237, // USA
                    218 => ($provider_valeur != 51) ? 374 : 329, // Tunisia
                    146 => ($provider_valeur != 51) ? 288 : 335, // Maroc
                    199 => ($provider_valeur != 51) ? 447 : 323, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 479 : 367, // France
                    179 => ($provider_valeur != 51) ? 543 : 431, // Russie
                    226 => ($provider_valeur != 51) ? 511 : 399, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 527 : 415, // Italie
                    82 => ($provider_valeur != 51) ? 495 : 383, // Allemagne
                    39 => ($provider_valeur != 51) ? 75 : 103, // Cameroun
                    79 => ($provider_valeur != 51) ? 81 : 109, // Gabon
                    50 => ($provider_valeur != 51) ? 86 : 114, // COngo Braza
                    // 57 => ($provider_valeur != 51) ? 153 : 202, // RDC
                    122 => ($provider_valeur != 51) ? 91 : 119, // Liberia
                    43 => ($provider_valeur != 51) ? 92 : 120, // Thacd
                    42 => ($provider_valeur != 51) ? 93 : 121, // Afrique centrale
                ],
                // Liberia
                122 =>  [

                    25 => ($provider_valeur != 51) ? 133 : 182, // Benin
                    83 => ($provider_valeur != 51) ? 168 : 217, // Ghana
                    133 => ($provider_valeur != 51) ?  580 : 224, // Niger
                    36 => ($provider_valeur != 51) ? 140 : 189, // BF
                    188 => ($provider_valeur != 51) ? 147 : 196, // Mali
                    214 => ($provider_valeur != 51) ? 154 : 203, // TOgo
                    91 => ($provider_valeur != 51) ?  175 : 231, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 97 : 125, // CIV
                    1 => ($provider_valeur != 51) ? 238 : 259, // USA
                    218 => ($provider_valeur != 51) ? 275 : 330, // Tunisia
                    146 => ($provider_valeur != 51) ? 336 : 289, // Maroc
                    199 => ($provider_valeur != 51) ? 448 : 324, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 368 : 480, // France
                    179 => ($provider_valeur != 51) ? 544 : 432, // Russie
                    226 => ($provider_valeur != 51) ? 512 : 400, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 416 : 528, // Italie
                    82 => ($provider_valeur != 51) ? 496 : 384, // Allemagne
                    39 => ($provider_valeur != 51) ? 157 : 206, // Cameroun
                    79 => ($provider_valeur != 51) ? 158 : 207, // Gabon
                    50 => ($provider_valeur != 51) ? 159 : 208, // COngo Braza
                    57 => ($provider_valeur != 51) ? 160 : 209, // RDC
                    // 122 => ($provider_valeur != 51) ? 161 : 210, // Liberia
                    43 => ($provider_valeur != 51) ? 95 : 123, // Thacd
                    42 => ($provider_valeur != 51) ? 96 : 124, // Afrique centrale
                ],
                // Tchad
                43 =>  [
                    25 => ($provider_valeur != 51) ? 134 : 183, // Benin
                    188 => ($provider_valeur != 51) ? 162 : 211, // Senegal
                    157 => ($provider_valeur != 51) ? 581 : 225, // Niger
                    36 => ($provider_valeur != 51) ? 141 : 190, // bf
                    133 => ($provider_valeur != 51) ? 148 : 197, // Mali
                    214 => ($provider_valeur != 51) ? 169 : 218, // Ghana
                    91 => ($provider_valeur != 51) ? 176 : 232, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 99 : 127, // CIV
                    1 => ($provider_valeur != 51) ? 260 : 239, // USA
                    218 => ($provider_valeur != 51) ? 276 : 331, // Tunisia
                    146 => ($provider_valeur != 51) ? 290 : 337, // Maroc
                    199 => ($provider_valeur != 51) ? 448 : 325, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 481 : 369, // France
                    179 => ($provider_valeur != 51) ? 545 : 433, // Russie
                    226 => ($provider_valeur != 51) ? 513 : 401, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 529 : 417, // Italie
                    82 => ($provider_valeur != 51) ? 497 : 385, // Allemagne
                    39 => ($provider_valeur != 51) ? 77 : 105, // Cameroun
                    79 => ($provider_valeur != 51) ? 83 : 111, // Gabon
                    50 => ($provider_valeur != 51) ?  88 : 116, // COngo Braza
                    57 => ($provider_valeur != 51) ? 92 : 120, // RDC
                    122 => ($provider_valeur != 51) ? 95 : 123, // Liberia
                    // 43 => ($provider_valeur != 51) ? 169 : 218, // Thacd
                    42 => ($provider_valeur != 51) ? 98 : 126, // Afrique centrale
                ],
                // Centre Afrique
                42 =>  [
                    25 => ($provider_valeur != 51) ? 135 : 184, // Benin
                    188 => ($provider_valeur != 51) ? 163 : 212, // Senegal
                    157 => ($provider_valeur != 51) ? 582 : 226, // Niger
                    83 => ($provider_valeur != 51) ?  170 : 219, // Ghana
                    36 => ($provider_valeur != 51) ? 142 : 191, //BF
                    133 => ($provider_valeur != 51) ? 149 : 198, // Mali
                    107 => ($provider_valeur != 51) ? 100 : 128, // CIV
                    214 => ($provider_valeur != 51) ? 156 : 205, //Togo
                    1 => ($provider_valeur != 51) ? 261 : 240, // USA
                    // 36 => ($provider_valeur != 51) ? 14 : 50, // BF
                    218 => ($provider_valeur != 51) ? 277 : 332, // Tunisia
                    146 => ($provider_valeur != 51) ? 291 : 338, // Maroc
                    199 => ($provider_valeur != 51) ? 450 : 326, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 482 : 370, // France
                    179 => ($provider_valeur != 51) ? 546 : 434, // Russie
                    226 => ($provider_valeur != 51) ? 514 : 402, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 530 : 418, // Italie
                    82 => ($provider_valeur != 51) ? 498 : 386, // Allemagne
                    39 => ($provider_valeur != 51) ? 78 : 106, // Cameroun
                    79 => ($provider_valeur != 51) ?  84 : 112, // Gabon
                    50 => ($provider_valeur != 51) ? 89 : 117, // COngo Braza
                    57 => ($provider_valeur != 51) ? 93 : 121, // RDC
                    122 => ($provider_valeur != 51) ? 96 : 124, // Liberia
                    43 => ($provider_valeur != 51) ? 98 : 126, // Thacd
                    // 42 => ($provider_valeur != 51) ? 177 : 233, // Afrique centrale
                ]
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif ($groupe_name == 'CIV') {


            $countriesLevels = [
                // Is Benin
                107 => [
                    36 => ($provider_valeur != 51) ? 15 : 51, // BF
                    25 => ($provider_valeur != 51) ? 1 : 44, // BENIN
                    133 => ($provider_valeur != 51) ? 21 : 57, // Mali
                    214 => ($provider_valeur != 51) ? 26 : 62, // Togo
                    188 => ($provider_valeur != 51) ? 30 : 66, // Senegal
                    83 => ($provider_valeur != 51) ? 33 : 69, // Ghana
                    157 => ($provider_valeur != 51) ? 35 : 71, // Niger
                    91 => ($provider_valeur != 51) ? 36 : 72, // Guinne conakry
                    // 107 => ($provider_valeur != 51) ? 1 : 44, // CIV
                    // Begin Vue
                    1 => ($provider_valeur != 51) ? 248 : 241, // USA
                    218 => ($provider_valeur != 51) ? 264 : 292, // Tunisia
                    146 => ($provider_valeur != 51) ? 278 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 435 : 312, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 467 : 355, // France
                    179 => ($provider_valeur != 51) ? 531 : 419, // Russie
                    226 => ($provider_valeur != 51) ? 499 : 387, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 483 : 371, // Allemagne
                    // End view
                    39 => ($provider_valeur != 51) ? 79 : 107, // Cameroun
                    79 => ($provider_valeur != 51) ? 85 : 113, // Gabon
                    50 => ($provider_valeur != 51) ? 90 : 118, // COngo Braza
                    57 => ($provider_valeur != 51) ? 94 : 122, // RDC
                    122 => ($provider_valeur != 51) ? 97 : 125, // Liberia
                    43 => ($provider_valeur != 51) ? 99 : 127, // Thacd
                    42 => ($provider_valeur != 51) ? 100 : 128, // Afrique centrale
                ]
            ];
        } elseif ($groupe_name == 'URP') {

            $countriesLevels = [
                // Is Espagne
                199 => [
                    25 => ($provider_valeur != 51) ? 435 : 312, // Benin
                    36 => ($provider_valeur != 51) ? 436 : 313, // BF
                    133 => ($provider_valeur != 51) ? 437 : 314, // Mali
                    214 => ($provider_valeur != 51) ? 438 : 315, // Togo
                    188 => ($provider_valeur != 51) ? 439 : 316, // Senegal
                    83 => ($provider_valeur != 51) ?  440 : 317, // Ghana
                    157 => ($provider_valeur != 51) ? 441 : 318, // Niger
                    91 => ($provider_valeur != 51) ?  442 : 319, // Guinne conakry
                    // End vue
                    107 => ($provider_valeur != 51) ? 443 : 320, // CIV
                    1 => ($provider_valeur == 51) ? 547 : null, // USA
                    218 => ($provider_valeur == 51) ? 556 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 563 : null, // Maroc

                    // 199 => ($provider_valeur != 51) ? 444 : 321, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 584 : null, // France
                    179 => ($provider_valeur == 51) ? 587 : null, // Russie
                    226 => ($provider_valeur == 51) ? 588 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 589 : null, // Italie
                    82 => ($provider_valeur == 51) ? 586 : null, // Allemagne

                    // End vue
                    39 => ($provider_valeur != 51) ? 444 : 321, // Cameroun
                    79 => ($provider_valeur != 51) ? 445 : 322, // Gabon
                    50 => ($provider_valeur != 51) ? 437 : 314, // COngo Braza
                    57 => ($provider_valeur != 51) ? 447 : 323, // RDC
                    122 => ($provider_valeur != 51) ? 448 : 324, // Liberia
                    43 => ($provider_valeur != 51) ? 448 : 325, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  450 : 326, // Afrique centrale
                ],
                // France
                74 =>  [
                    25 => ($provider_valeur != 51) ?  467 : 355, // Benin
                    36 => ($provider_valeur != 51) ? 468 : 356, // BF
                    133 => ($provider_valeur != 51) ? 469 : 357, // Mali
                    214 => ($provider_valeur != 51) ? 470 : 358, // Togo
                    188 => ($provider_valeur != 51) ? 471 : 359, // Senegal
                    83 => ($provider_valeur != 51) ? 472 : 360, // Ghana
                    157 => ($provider_valeur != 51) ? 473 : 361, // Niger
                    91 => ($provider_valeur != 51) ? 474 : 362, // Guinne conakry
                    // vue France Ancien
                    107 => ($provider_valeur != 51) ? 85 : 113, // CIV
                    // En ancien
                    1 => ($provider_valeur == 51) ? 548 : null, // USA
                    218 => ($provider_valeur == 51) ? 557 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 564 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 584 : null, // ESPAGNE
                    // 74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 594 : null, // Russie
                    226 => ($provider_valeur == 51) ? 592 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 593 : null, // Italie
                    82 => ($provider_valeur == 51) ? 591 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 476 : 364, // Cameroun
                    79 => ($provider_valeur != 51) ? 477 : 365, // Gabon
                    50 => ($provider_valeur != 51) ? 469 : 357, // COngo Braza
                    57 => ($provider_valeur != 51) ? 479 : 367, // RDC
                    122 => ($provider_valeur != 51) ? 368 : 480, // Liberia
                    43 => ($provider_valeur != 51) ? 481 : 369, // Thacd
                    42 => ($provider_valeur != 51) ? 482 : 370, // Afrique centrale
                ],
                // Allemagne
                82 =>  [
                    25 => ($provider_valeur != 51) ?  483 : 371, // Benin
                    36 => ($provider_valeur != 51) ? 484 : 372, // BF
                    133 => ($provider_valeur != 51) ? 485 : 373, // Mali
                    214 => ($provider_valeur != 51) ? 486 : 374, // Togo
                    188 => ($provider_valeur != 51) ? 487 : 375, // Senegal
                    83 => ($provider_valeur != 51) ? 488 : 376, // Ghana
                    157 => ($provider_valeur != 51) ? 489 : 377, // Niger
                    91 => ($provider_valeur != 51) ? 490 : 378, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 491 : 379, // CIV

                    1 => ($provider_valeur == 51) ? 550 : null, // USA
                    218 => ($provider_valeur == 51) ? 559 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 566 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 584 : null, // ESPAGNE
                    // 74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 601 : null, // Russie
                    226 => ($provider_valeur == 51) ? 599 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 600 : null, // Italie
                    // 82 => ($provider_valeur == 51) ? 591 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 524 : 412, // Cameroun
                    79 => ($provider_valeur != 51) ? 493 : 381, // Gabon
                    50 => ($provider_valeur != 51) ? 485 : 373, // COngo Braza
                    57 => ($provider_valeur != 51) ? 495 : 383, // RDC
                    122 => ($provider_valeur != 51) ? 496 : 384, // Liberia
                    43 => ($provider_valeur != 51) ? 497 : 385, // Thacd
                    42 => ($provider_valeur != 51) ? 498 : 386, // Afrique centrale
                ],
                // Italie
                106 =>  [
                    25 => ($provider_valeur != 51) ?  515 : 403, // Benin
                    36 => ($provider_valeur != 51) ? 516 : 404, // BF
                    133 => ($provider_valeur != 51) ? 517 : 405, // Mali
                    214 => ($provider_valeur != 51) ? 518 : 406, // Togo
                    188 => ($provider_valeur != 51) ? 519 : 407, // Senegal
                    83 => ($provider_valeur != 51) ? 520 : 408, // Ghana
                    157 => ($provider_valeur != 51) ? 521 : 409, // Niger
                    91 => ($provider_valeur != 51) ? 522 : 410, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 523 : 411, // CIV

                    1 => ($provider_valeur == 51) ? 552 : null, // USA
                    218 => ($provider_valeur == 51) ? 561 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 568 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 589 : null, // ESPAGNE
                    // 74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 604 : null, // Russie
                    226 => ($provider_valeur == 51) ? 602 : null, // Royaume Unie
                    // 106 => ($provider_valeur == 51) ? 600 : null, // Italie
                    82 => ($provider_valeur == 51) ? 591 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 515 : 403, // Cameroun
                    79 => ($provider_valeur != 51) ? 525 : 413, // Gabon
                    50 => ($provider_valeur != 51) ? 517 : 405, // COngo Braza
                    57 => ($provider_valeur != 51) ? 527 : 415, // RDC
                    122 => ($provider_valeur != 51) ? 416 : 528, // Liberia
                    43 => ($provider_valeur != 51) ? 529 : 417, // Thacd
                    42 => ($provider_valeur != 51) ? 530 : 418, // Afrique centrale
                ],
                // Royaume Unie
                226 =>  [
                    25 => ($provider_valeur != 51) ?  499 : 387, // Benin
                    36 => ($provider_valeur != 51) ? 500 : 388, // BF
                    133 => ($provider_valeur != 51) ? 501 : 389, // Mali
                    214 => ($provider_valeur != 51) ? 502 : 390, // Togo
                    188 => ($provider_valeur != 51) ? 503 : 391, // Senegal
                    83 => ($provider_valeur != 51) ? 504 : 392, // Ghana
                    157 => ($provider_valeur != 51) ? 505 : 393, // Niger
                    91 => ($provider_valeur != 51) ? 506 : 394, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 507 : 395, // CIV

                    1 => ($provider_valeur == 51) ? 551 : null, // USA
                    218 => ($provider_valeur == 51) ? 560 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 567 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 588 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 592 : null, // France

                    179 => ($provider_valeur == 51) ? 603 : null, // Russie
                    // 226 => ($provider_valeur == 51) ? 602 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 602 : null, // Italie
                    82 => ($provider_valeur == 51) ? 599 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 508 : 396, // Cameroun
                    79 => ($provider_valeur != 51) ? 509 : 397, // Gabon
                    50 => ($provider_valeur != 51) ? 501 : 389, // COngo Braza
                    57 => ($provider_valeur != 51) ? 511 : 399, // RDC
                    122 => ($provider_valeur != 51) ? 512 : 400, // Liberia
                    43 => ($provider_valeur != 51) ? 513 : 401, // Thacd
                    42 => ($provider_valeur != 51) ? 514 : 402, // Afrique centrale
                ],
                // Ruissie
                179 =>  [
                    25 => ($provider_valeur != 51) ? 531 : 419, // Benin
                    36 => ($provider_valeur != 51) ? 532 : 420, // BF
                    133 => ($provider_valeur != 51) ? 533 : 421, // Mali
                    214 => ($provider_valeur != 51) ? 534 : 422, // Togo
                    188 => ($provider_valeur != 51) ? 535 : 423, // Senegal
                    83 => ($provider_valeur != 51) ? 536 : 424, // Ghana
                    157 => ($provider_valeur != 51) ? 537 : 425, // Niger
                    91 => ($provider_valeur != 51) ?  538 : 426, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 539 : 427, // CIV

                    1 => ($provider_valeur == 51) ? 554 : null, // USA
                    218 => ($provider_valeur == 51) ? 562 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 593 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 587 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 594 : null, // France

                    // 179 => ($provider_valeur == 51) ? 603 : null, // Russie
                    226 => ($provider_valeur == 51) ? 603 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 604 : null, // Italie
                    82 => ($provider_valeur == 51) ? 601 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 540 : 428, // Cameroun
                    79 => ($provider_valeur != 51) ? 541 : 429, // Gabon
                    50 => ($provider_valeur != 51) ? 533 : 421, // COngo Braza
                    57 => ($provider_valeur != 51) ? 543 : 431, // RDC
                    122 => ($provider_valeur != 51) ? 544 : 432, // Liberia
                    43 => ($provider_valeur != 51) ?  545 : 433, // Thacd
                    42 => ($provider_valeur != 51) ? 546 : 434, // Afrique centrale
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif ($groupe_name == 'USA') {

            $countriesLevels = [
                // Is USA
                1 => [
                    25 => ($provider_valeur != 51) ? 248 : 241, // Benin
                    36 => ($provider_valeur != 51) ? 249 : 242, // BF
                    133 => ($provider_valeur != 51) ? 570 : 243, // Mali
                    214 => ($provider_valeur != 51) ? 250 : 244, // Togo
                    188 => ($provider_valeur != 51) ? 251 : 245, // Senegal
                    83 => ($provider_valeur != 51) ? 252 : 571, // Ghana
                    157 => ($provider_valeur != 51) ? 253 : 246, // Niger
                    91 => ($provider_valeur != 51) ? 35 : 71, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 79 : 107, // CIV
                    // 1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 555 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 547 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 548 : null, // France
                    179 => ($provider_valeur == 51) ? 553 : null, // Russie
                    226 => ($provider_valeur == 51) ? 551 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 552 : null, // Italie
                    82 => ($provider_valeur == 51) ? 550 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 255 : 234, // Cameroun
                    79 => ($provider_valeur != 51) ? 256 : 235, // Gabon
                    50 => ($provider_valeur != 51) ? 570 : 243, // COngo Braza
                    57 => ($provider_valeur != 51) ? 258 : 237, // RDC
                    122 => ($provider_valeur != 51) ? 238 : 259, // Liberia
                    43 => ($provider_valeur != 51) ? 260 : 239, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 261 : 240, // Afrique centrale
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif ($groupe_name == 'MAG') {

            $countriesLevels = [
                // Is Tunisia
                218 => [
                    25 => ($provider_valeur != 51) ? 264 : 292, // Benin
                    36 => ($provider_valeur != 51) ? 265 : 293, // BF
                    133 => ($provider_valeur != 51) ? 574 : 294, // Mali
                    214 => ($provider_valeur != 51) ? 266 : 295, // Togo
                    188 => ($provider_valeur != 51) ? 267 : 296, // Senegal
                    83 => ($provider_valeur != 51) ? 268 : 297, // Ghana
                    157 => ($provider_valeur != 51) ? 269 : 298, // Niger
                    91 => ($provider_valeur != 51) ? 270 : 299, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 310 : 300, // CIV
                    1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    // 218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 593 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 556 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 557 : null, // France
                    179 => ($provider_valeur == 51) ? 562 : null, // Russie
                    226 => ($provider_valeur == 51) ? 560 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 561 : null, // Italie
                    82 => ($provider_valeur == 51) ? 559 : null, // Allemagne
                    39 => ($provider_valeur != 51) ?  271 : 327, // Cameroun
                    79 => ($provider_valeur != 51) ? 272 : 328, // Gabon
                    50 => ($provider_valeur != 51) ? 574 : 294, // COngo Braza
                    57 => ($provider_valeur != 51) ? 374 : 329, // RDC
                    122 => ($provider_valeur != 51) ? 275 : 330, // Liberia
                    43 => ($provider_valeur != 51) ? 276 : 331, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 277 : 332, // Afrique centrale
                ],
                // Is Maroc
                146 => [
                    25 => ($provider_valeur != 51) ?  278 : 302, // Benin
                    36 => ($provider_valeur != 51) ? 279 : 302, // BF
                    133 => ($provider_valeur != 51) ? 575 : 303, // Mali
                    214 => ($provider_valeur != 51) ? 280 : 304, // Togo
                    188 => ($provider_valeur != 51) ? 281 : 305, // Senegal
                    83 => ($provider_valeur != 51) ? 282 : 306, // Ghana
                    157 => ($provider_valeur != 51) ? 283 : 307, // Niger
                    91 => ($provider_valeur != 51) ? 284 : 308, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 311 : 309, // CIV
                    1 => ($provider_valeur == 51) ? 555 : null, // USA
                    218 => ($provider_valeur == 51) ? 593 : null, // Tunisia
                    // 146 => ($provider_valeur == 51) ? 593 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 563 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 564 : null, // France
                    179 => ($provider_valeur == 51) ? 569 : null, // Russie
                    226 => ($provider_valeur == 51) ? 567 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 568 : null, // Italie
                    82 => ($provider_valeur == 51) ? 566 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 285 : 333, // Cameroun
                    79 => ($provider_valeur != 51) ? 286 : 334, // Gabon
                    50 => ($provider_valeur != 51) ? 575 : 303, // COngo Braza
                    57 => ($provider_valeur != 51) ? 288 : 335, // RDC
                    122 => ($provider_valeur != 51) ? 336 : 289, // Liberia
                    43 => ($provider_valeur != 51) ? 290 : 337, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  291 : 338, // Afrique centrale
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } else {
            return null;
        }


        if (isset($countriesLevels[$countryId])) {

            $specificCountry = $countriesLevels[$countryId][$countrie->id];

            if ($specificCountry) {
                $levelValeur = $specificCountry;
            } else {
                return null; // La levelValeur pour $provider_valeur n'est pas définie pour $countrie
            }
        } else {
            return null; // Aucune correspondance pour $countrie
        }

        return  LevelCountry::whereId($levelValeur)->first();
    }

    public function getRobusPercentageOrderCardProperty()
    {
        return DepositCost::whereId(1)->first();
    }

    public function getRobusPercentageRelaodCardProperty()
    {
        return BankCost::whereId(1)->first();
    }

    public function getRobusPercentagePhoneInterProperty()
    {
        return PhoneCreditCost::whereId(2)->first();
    }

    public function getRobusPercentagePaymentInterProperty()
    {
        return PaymentCost::whereId(1)->first();
    }


    public function getRobusPercentageVirementCustumersProperty()
    {

        return BankCost::whereId(3)->first();
    }

    public function getRobusPercentageVirementSDistributersProperty()
    {

        return BankCost::whereId(2)->first();
    }

    public function getRobusPercentageReloadOrder()
    {
        return DepositCost::whereId(2)->first();
    }

    public function getRobusPercentagePayment()
    {
        return PaymentCost::whereId(2)->first();
    }

    public function getRobusPercentagePhoneProperty()
    {
        return PhoneCreditCost::whereId(1)->first();
    }


    public function getRobusPercentageCustumers($countryNameGroup)
    {
        // $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(28)->first();
            case 'AFL':
                return TransactionCost::whereId(37)->first();
            case 'AFC':
                return TransactionCost::whereId(38)->first();
            case 'USA':
                return TransactionCost::whereId(39)->first();
            case 'URP':
                return TransactionCost::whereId(40)->first();
            case 'MAG':
                return TransactionCost::whereId(41)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageDistributers($countryNameGroup)
    {
        // $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(26)->first();
            case 'AFL':
                return TransactionCost::whereId(32)->first();
            case 'AFC':
                return TransactionCost::whereId(33)->first();
            case 'USA':
                return TransactionCost::whereId(34)->first();
            case 'URP':
                return TransactionCost::whereId(35)->first();
            case 'MAG':
                return TransactionCost::whereId(36)->first();
            default:
                return null; // Handle the default case as needed
        }
    }

    public function getGainAdminsProperty()
    {
        $data = [];
        foreach (AdminsCom::get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminsDaysProperty()
    {
        $data = [];

        // Exécutez la requête pour obtenir une collection d'objets
        $admins = AdminsCom::whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        foreach ($admins as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::where('description', 'Commission administrateur')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->get() as $bank) {

            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminDateProperty()
    {

        $data = [];


        $admins = AdminsCom::whereBetween('created_at', [$this->startDate, $this->endDate])->where('description', 'Commission administrateur')->whereStatus('SUCCESS')->get();

        foreach ($admins as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getGainAdminStatutProperty()
    {

        $data = [];


        $admins = AdminsCom::where("status", $this->statusTransferFilter)->where('description', 'Commission administrateur')->get();

        foreach ($admins as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminDrawalTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::where('description', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->get() as $bank) {

            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminTransactionsTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->get() as $bank) {

            $data[] = $bank->amount_under;
        }

        return array_sum($data);
    }

    public function getGainAdminTransactionsDaysProperty()
    {


        $data = [];

        $admins = AdminsCom::whereBetween('created_at', [$this->startDate, $this->endDate])->where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->get();

        foreach ($admins as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount_under;
        }


        return array_sum($data);
    }
}
