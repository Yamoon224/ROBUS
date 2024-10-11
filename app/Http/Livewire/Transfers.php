<?php

namespace App\Http\Livewire;

use App\Events\AdminNotifEvent;
use App\Events\ChooseInterEvent;
use App\Events\ChoosePaymentEvent;
use App\Events\ChoosePhoneCreditEvent;
use App\Events\ChooseReloadCardEvent;
use App\Events\ChooseTransactionEvent;
use App\Events\ChooseVirementEvent;
use App\Events\CreateWalletBankEvent;
use App\Events\CreateWalletPaiementEvent;
use App\Events\CreateWalletPhoneCreditEvent;
use App\Events\CreateWalletReloadCardEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Http\Controllers\Admin\MailController;
use App\Http\Services\NotificationSendService;
use App\Http\Services\ValidateDailyLimitService;
use App\Mail\CodeValidated;
use App\Models\AdminsCom;
use App\Models\Banks\BankCard;
use App\Models\Costs\BankCost;
use App\Models\Costs\DepositCost;
use App\Models\Costs\PaymentCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\TransactionCost;
use App\Models\Custumers\Country;
use App\Models\Invoices\InvoiceBank;
use App\Models\Invoices\InvoiceDeposit;
use App\Models\Invoices\InvoiceInter;
use App\Models\Invoices\InvoicePayment;
use App\Models\Invoices\InvoicePhoneCredit;
use App\Models\Invoices\InvoiceTransaction;
use App\Models\Moneys\LevelMoney;
use App\Models\Offer;
use App\Models\Payments\Deposit;
use App\Models\Payments\Payment;
use App\Models\Payments\PaymentBank;
use App\Models\Payments\PaymentPhoneCredit;
use App\Models\Payments\Transaction;
use App\Models\Payments\TransactionInter;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class Transfers extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $searchTransaction = '';
    public $search = '';
    public $wallet;
    public $searchPaiment = '';
    public $searchVirement = '';
    public $searchUser = '';
    public $searchInter = '';
    public $searchReloadCard = '';
    public $searchPhoneCredit = '';
    public $statusFilterPaiement = '';
    public $statusFilterVirement = '';
    public $statusFilterInter = '';
    public $statusFilterReloadCard = '';
    public $statusFilterPhoneCredit = '';
    public $statusFilterTransaction = '';
    public $searchRobus = '';
    public $percent_robus_distri;
    // Valueur currencie

    public $currency_custumers;

    // Transactions
    public $transactionId;
    public $transaction_description;
    public $transactionStatus;
    public $transaction_phone;
    public $transaction_cost;
    public $provider_transaction;
    public $compte_number_robus;
    public $transaction_amount_robus;
    public $transaction_amount_money;
    public $transaction_description_robus;
    public $password_transaction;
    public $showNTransfersNotification = false;
    public $currentStep = 1;
    public $code_validated;
    public $code_validated_inter;
    // Edit
    public $transaction_description_edit;
    public $transaction_phone_edit;
    public $transaction_cost_edit;
    public $provider_transaction_edit;
    public $compte_number_robus_edit;
    public $transaction_amount_robus_edit;
    public $transaction_amount_money_edit;
    public $transaction_description_robus_edit;

    // Paiements
    public $paiementDescription;
    public $paiementId;
    public $paiement_cost;
    public $offer_paiement;
    public $paiementStatus;
    public $provider_payment;

    // SODECIE
    public $paiement_name_sodecie;
    public $paiement_amount_t_sodecie;
    public $paiement_amount_p_sodecie;
    public $paiement_decoder;
    public $paiement_num_contrat;
    public $provider_payment_edit;

    public $provider_payment_name;

    // CIE
    public $paiement_name_cie;
    public $paiement_id_invoice;
    public $paiement_amount_total;
    public $paiement_amount_partiel;
    public $paiement_id_invoice_pre;
    public $pre_phone_invoice;
    public $pre_id_invoice;

    // CANAL
    public $paiement_phone_canal;
    public $paiement_decoder_canal;
    public $paiement_amount_Canal;

    // Choise
    public $paiementChoix_phone;
    public $paiementChoix_type;
    public $facture_choix_type;

    // Edit
    public $paiement_num_contrat_edit;
    public $paiement_id_invoice_edit;
    public $paiement_decoder_canal_edit;
    public $paiement_name_sodecie_edit;
    public $paiement_name_cie_edit;
    public $paiement_phone_canal_edit;
    public $paiement_amount_Canal_edit;
    public $paiement_amount_p_sodecie_edit;
    public $paiement_amount_t_sodecie_edit;
    public $paiement_amount_total_edit;
    public $paiement_amount_partiel_edit;
    public $paiementChoix_phone_edit;
    public $paiementChoix_type_edit;
    public $offer_paiement_edit;
    public $facture_choix_type_edit;
    public $phone_invoice_pre_edit;
    public $pre_id_invoice_edit;
    public $name_reloadcard_edit;
    public $offer_paiement_edit_amount;

    // Virement
    public $virementDescription;
    public $virementId;
    public $virement_cost;
    public $virementStatus;
    public $provider_virement;
    public $firstname_user;
    public $lastname_user;
    public $phone_user;
    public $amount_virement;
    public $account_number_user_bank;
    public $showNBankNotification = false;
    public $country_virement;
    public $account_number_user;


    // edit
    public $virementDescription_edit;
    public $provider_virement_edit;
    public $firstname_user_edit;
    public $lastname_user_edit;
    public $phone_user_edit;
    public $amount_virement_edit;
    public $account_number_user_edit;
    public $country_virement_edit;

    // Inters
    public $inter_description;
    public $inter_description_robus;
    public $interId;
    public $inter_cost;
    public $interStatus;
    public $provider_inter;
    public $country_inter;
    public $amount_inter;
    public $account_number_inter;
    public $phone_inter;
    public $amount_inter_robus;
    public $country_inter_robus;
    public $country_inter_out_civ;
    public $provider_inter_out_civ;
    public $phone_inter_out_civ;
    public $amount_inter_out_civ;
    public $showNPaiementNotification = false;
    public $choose_type_transaction_inter;

    // Phone credit inter
    public $provider_phone_credit_inter;
    public $number_phone_credit_inter;
    public $amount_phone_credit_inter;
    public $country_inter_robus_inter;

    // Inter edit Phone credit
    public $provider_phone_credit_inter_edit;
    public $number_phone_credit_inter_edit;
    public $amount_phone_credit_inter_edit;
    public $country_inter_robus_inter_edit;

    // Virment bank inter
    public $virement_interId;
    public $virement_inter_Status;
    public $provider_Inter_virement;
    public $firstname_user_inter;
    public $lastname_user_inter;
    public $phone_user_inter;
    public $amount_virement_inter;
    public $account_number_user_bank_inter;
    public $showNBankNotification_inter = false;
    public $country_virement_inter;
    public $account_number_user_inter;
    public $amount_virement_nsia_inter;

    // Edit virement bank Inter
    public $provider_Inter_virement_edit;
    public $firstname_user_inter_edit;
    public $lastname_user_inter_edit;
    public $name_relo_user_inter_edit;
    public $phone_user_inter_edit;
    public $amount_virement_inter_edit;
    public $account_number_user_bank_inter_edit;
    public $account_number_user_inter_edit;
    public $amount_virement_nsia_inter_edit;
    public $devise_user;

    // Edit Invoice paiement
    public $phone_invoice_inter_edit;
    public $id_invoice_inter_edit;

    // Choose Paiement Inter invoice
    public $choose_type_paiement;
    public $provider_payment_inter;
    public $choose_type_transaction;

    // SODECIE
    public $paiement_name_sodecie_inter;
    public $paiement_amount_t_sodecie_inter;
    public $paiement_amount_p_sodecie_inter;
    public $paiement_decoder_inter;
    public $paiement_num_contrat_inter;
    public $provider_payment_edit_inter;

    // CIE
    public $paiement_name_cie_inter;
    public $paiement_id_invoice_inter;
    public $paiement_amount_total_inter;
    public $paiement_amount_partiel_inter;
    public $paiement_id_invoice_pre_inter;
    public $pre_phone_invoice_inter;
    public $pre_id_invoice_inter;

    // CANAL
    public $paiement_phone_canal_inter;
    public $paiement_decoder_canal_inter;
    public $paiement_amount_Canal_inter;
    public $offer_paiement_inter;
    // edit
    public $inter_description_edit;
    public $inter_description_robus_edit;
    public $provider_inter_edit;
    public $country_inter_edit;
    public $amount_inter_edit;
    public $account_number_inter_edit;
    public $phone_inter_edit;
    public $amount_inter_robus_edit;
    public $country_inter_robus_edit;
    public $payment_description_robus;

    // Relaod cards
    public $reloadcard_description;
    public $reloadcardId;
    public $reloadcard_cost;
    public $reloadcardStatus;
    public $provider_reloadcard;
    public $amount_reloadcard;
    public $account_number_reload_card;
    public $name_reloadcard;

    // Reload card inter
    public $provider_reloadcard_inter;
    public $amount_reloadcard_inter;
    public $account_number_reload_card_inter;
    public $name_reloadcard_inter;

    // edit
    public $reloadcard_description_edit;
    public $provider_reloadcard_edit;
    public $country_reloadcard_edit;
    public $amount_reloadcard_edit;
    public $account_number_reloadcard_edit;
    public $phone_reloadcard_edit;
    public $amount_reloadcard_robus_edit;
    public $country_reloadcard_robus_edit;

    // Phone credit
    public $phoneCreditId;
    public $phoneCredit_cost;
    public $phoneCreditStatus;
    public $provider_phone_credit;
    public $number_phone_credit;
    public $amount_phone_credit;
    public $description_phone_credit;

    // Edit
    public $provider_phone_credit_edit;
    public $number_phone_credit_edit;
    public $amount_phone_credit_edit;

    // Protected valeurs Services
    private $phoneCreditService1;

    // step valeurs
    public $stepPaiment = 1;
    public $stepVirement = 1;
    public $stepReloadCard = 1;
    public $stepPhoneCredit = 1;
    public $password_validated;


    protected $listeners = [
        '' => 'transactionInterReload',
        'transaction_inters' => 'transactionData',
        'transaction_national' => 'transactionNationData',
        'transaction_centres' => 'transactionAll',
        'echo:wallet-bank-created,.Created_wallet_bank' => 'notifyNewBank',
        'echo:updated_bank,.update_wallet_bank' => 'notifyNewBank',
        'echo:wallet-paiement-created,.Created_wallet_paiement' => 'notifyNewPaiement',
        'echo:updated_paiement,.update_wallet_paiement' => 'notifyNewPaiement',
        'echo:wallet-transfers-created,.Created_wallet_transfers' => 'notifyNewTransfers',
        'echo:updated_transfers,.update_wallet_transfers' => 'notifyNewTransfers',
        'echo:wallet-inter-created,.Created_wallet_inter' => 'notifyNewInter',
        'echo:updated_inter,.update_wallet_inter' => 'notifyNewInter',
        'echo:wallet-card-created,.Created_wallet_card' => 'notifyNewCard',
        'echo:updated_card,.update_wallet_card' => 'notifyNewCard',
        'echo:wallet-phone-created,.Created_wallet_phone' => 'notifyNewPhone',
        'echo:updated_phone,.update_wallet_phone' => 'notifyNewPhone',
        // Choose Event Channel
        'echo:wallet-bank-choose-created,.Created_wallet_bank_choose' =>
        'notifyNewBank',
        'echo:wallet-transfers-choose-created,.Created_wallet_transfers_choose' =>
        'notifyNewTransfers',
        'echo:wallet-paiement-choose-created,.Created_wallet_paiement_choose' =>
        'notifyNewPaiement',
        'echo:wallet-card-choose-created,.Created_wallet_card_choose' =>
        'notifyNewCard',
        'echo:wallet-inter-choose-created,.Created_wallet_inter_choose' =>
        'notifyNewInter',
        'echo:wallet-phone-choose-created,.Created_wallet_phone_choose' =>
        'notifyNewPhone',
        'cancelTransactionInTransfers' => 'cancelTransactionField'
    ];



    public function transactionAll($data)
    {
        $data;
        $this->choose_type_transaction = null;
        $this->emit('modal-hide-transfers');
        $this->emit('menu-transaction-active');
    }

    public function transactionData()
    {
        $this->choose_type_paiement = null;
        $this->emit('modal-hide-inters');
        $this->emit('menu-transaction-active');
        $this->emit('menu-inters-active');
    }

    public function transactionNationData()
    {
        $this->choose_type_transaction = null;
        $this->emit('modal-hide-transfers-validated');
        $this->emit('modal-hide-transfers');
    }

    public function transactionInterReload($data)
    {
        $data;
        $this->choose_type_transaction = null;
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
    }

    // Initialise valeur after click 
    public function loadChoose1()
    {
        $this->provider_phone_credit_inter = null;
        $this->provider_payment_inter = null;
        $this->paiement_decoder_canal_inter = '';
        $this->paiement_name_cie_inter = '';
        $this->paiement_id_invoice_inter = '';
        $this->pre_id_invoice_inter = '';
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
    }

    public function loadChoose10()
    {
        $this->provider_phone_credit_inter = null;
        $this->provider_payment_inter = null;
        $this->paiement_decoder_canal_inter = '';
        $this->paiement_name_cie_inter = '';
        $this->paiement_id_invoice_inter = '';
        $this->pre_id_invoice_inter = '';
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
    }

    public function loadChoose2()
    {
        $this->provider_phone_credit_inter = null;
        $this->number_phone_credit_inter = '';
        $this->amount_phone_credit_inter = '';
        $this->provider_inter = null;
        $this->paiementChoix_phone = null;
        $this->offer_paiement = null;
        $this->facture_choix_type = null;
        $this->choose_type_paiement = null;
        $this->paiement_amount_total_inter = ' ';
        $this->paiement_decoder_canal_inter = '';
        $this->paiement_name_cie_inter = '';
        $this->paiement_id_invoice_inter = '';
        $this->pre_id_invoice_inter = '';
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
        $this->amount_inter = '';
        $this->account_number_inter = '';
        $this->phone_inter = '';
        $this->amount_inter_robus = '';
    }

    public function loadChoose3()
    {
        $this->provider_inter = null;
        $this->pre_id_invoice_inter = '';
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
        $this->amount_inter = '';
        $this->account_number_inter = '';
        $this->phone_inter = '';
        $this->amount_inter_robus = '';
        $this->country_inter_robus = '';
        $this->provider_payment_inter = null;
    }

    public function loadChoose8()
    {
        $this->provider_inter = null;
        $this->pre_id_invoice_inter = '';
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
        $this->amount_inter = '';
        $this->account_number_inter = '';
        $this->phone_inter = '';
        $this->amount_inter_robus = '';
        $this->country_inter_robus = '';
        $this->provider_payment_inter = null;
    }
    //  Notify and real times and active menus
    public function notifyNewBank()
    {
        $this->showNBankNotification = true;
        // $this->emit('modal-hide-virement');
        $this->emit('menu-virement-active');
    }

    public function notifyAllWallet()
    {
        $this->showNBankNotification = true;
        // $this->emit('modal-hide-virement');
    }

    public function notifyNewPaiement()
    {
        $this->showNPaiementNotification = true;
        $this->emit('menu-paiement-active');
    }

    public function notifyNewTransfers()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-transfers');
        $this->emit('menu-transaction-active');
    }

    public function notifyNewInter()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-inters');
        $this->emit('menu-inters-active');
    }

    public function notifyNewCard()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-reloadCard');
        $this->emit('menu-reloadCarte-active');
    }

    public function notifyNewPhone()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-phoneCredit');
        $this->emit('menu-phoneCredit-active');
    }

    // Sted 1 validated Transaction
    public function firstStepSubmit()
    {
        // $todays = Carbon::today();
        $todays = strtotime(Carbon::now());
        $compare_date = date('H:i:s', $todays);
        $temps_ecoule = strtotime($compare_date);
        $ecoule_min = date('s', $temps_ecoule);
        $rt = rand(40, 906769895) * time() + $ecoule_min;
        $code_valdated = sprintf('%0.4s', str_shuffle($rt . $ecoule_min));
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_transaction)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        // Verifier transfers sum days
        $som_transfers =  $this->getSumValTransactionProperty();
        $walletUser = Auth::guard('web')->user();
        $rt = rand(40, 500000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();


        if (sabdy()->country->name_groupe == 'CIV') {
            $percent_robus_distri =  TransactionCost::whereId(26)->first();
        } elseif (sabdy()->country->name_groupe == 'AFL') {
            $percent_robus_distri =  TransactionCost::whereId(32)->first();
        } elseif (sabdy()->country->name_groupe == 'AFC') {
            $percent_robus_distri =  TransactionCost::whereId(33)->first();
        } elseif (sabdy()->country->name_groupe == 'USA') {
            $percent_robus_distri =  TransactionCost::whereId(34)->first();
        } elseif (sabdy()->country->name_groupe == 'URP') {
            $percent_robus_distri =  TransactionCost::whereId(35)->first();
        } elseif (sabdy()->country->name_groupe == 'MAG') {
            $percent_robus_distri =  TransactionCost::whereId(36)->first();
        } elseif (sabdy()->country->name_groupe == 'ASIE') {
            $percent_robus_distri =  TransactionCost::whereId(44)->first();
        } elseif (sabdy()->country->name_groupe == 'AFE') {
            $percent_robus_distri =  TransactionCost::whereId(45)->first();
        }

        try {

            // If choose Withdrawal
            if ($this->choose_type_transaction == 2) {


                // If ROBUS providers
                if ($this->provider_transaction == 9) {

                    $this->validate(
                        [
                            'transaction_amount_robus' => 'required',
                            'compte_number_robus' => 'required',
                        ],
                        [
                            'compte_number_robus.exists' =>
                            'Ce compte n\'existe pas dans chez nous ',
                            'transaction_amount_robus.required' =>
                            'Votre montant est obligatoire',
                            'compte_number_robus.required' =>
                            'le numero est obligatoire',
                        ]
                    );


                    if (sabdy()->is_distributer == 1 || sabdy()->is_supervisor == 1 || sabdy()->is_supervisor == 1) {

                        // Seach wallet User
                        $wallet_search = Wallet::where('wallet_id',  $this->compte_number_robus)
                            ->where('country_id', $walletUser->country->id)
                            ->first();
                    } elseif (sabdy()->is_admin == 1) {

                        // Seach wallet User
                        $wallet_search = Wallet::where('wallet_id',  $this->compte_number_robus)
                            ->first();
                    } else {

                        $this->resetTransactionField();
                        $this->emit('modal-hide-transfers');
                        $this->emit('error', 'Pas de selection de providers !');
                    }


                    if ($wallet_search == null &&  $this->compte_number_robus == $walletUser->wallet->wallet_id) {
                        $this->resetTransactionField();
                        $this->emit('modal-hide-transfers');
                        $this->emit('error', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
                        return;
                    }

                    if ($this->isUserNotEligible($wallet_search)) {
                        $this->handleError('Pas de selection de providers !');
                        return;
                    }


                    if ($som_transfers >= $valeur_ceiling) {
                        $this->resetTransactionField();
                        $this->emit('modal-hide-transfers');
                        $this->emit('warning', 'Votre montant journier est atteint !');
                        return;
                    }

                    $wallet_send = $wallet_search;

                    $commission_receiveid =  $this->transaction_amount_robus;

                    $wallet_receveid =  $walletUser->wallet;

                    $commission_send =  $this->convertAdminCommissionInter($commission_receiveid, $wallet_send, $wallet_receveid);

                    // Verify amount
                    if ($commission_send > $wallet_search->amount) {
                        $this->resetTransactionField();
                        $this->emit('modal-hide-transfers');
                        $this->emit('success', 'Verifier votre montant ! !');
                        return;
                    }

                    $wallet = $wallet_search;

                    $commission_valeur = $percent_robus_distri->percent * $this->transaction_amount_robus;

                    $valeur_amount_com = $percent_robus_distri->percent_with_drawal * $commission_valeur;

                    $valuer_with_drawal =  $this->convertAdminCommission($valeur_amount_com, $wallet);

                    $walletAmountSend = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $this->compte_number_robus)->first();

                    $amount_solde_drawal = (float)$walletAmountSend->total_amount -  (float)$commission_send;

                    $amount_drawal = $amount_solde_drawal;

                    $this->updatePaiementWithdrawalWallet($wallet_search,  $commission_send);

                    // $amount_solde = $check->wallet->amount +  $commission;
                    $amount_solde = (float)sabdy()->wallet->amount +  (float) $this->transaction_amount_robus;

                    $this->updatePaiementDrawalWallet($walletUser,  $this->transaction_amount_robus);

                    $walletTransaction = $this->createDrawalTransaction(
                        $walletUser,
                        $this->transaction_amount_robus,
                        $this->transaction_description_robus,
                        $this->transaction_phone,
                        $this->compte_number_robus,
                        $provider,
                        $commission_send,
                        $this->choose_type_transaction,
                        $amount_solde,
                        $amount_drawal
                    );

                    $transaction = $this->createNewInvoiceTransaction($walletTransaction, $transactionCompiCode, $transactionCode);

                    $this->createInvoiceTransactionDrawal($transaction, $walletTransaction);

                    $this->TransactionDistributorDrawal(
                        $valuer_with_drawal,
                        $admin_civ,
                        $walletTransaction
                    );

                    event(new CreateWalletTransfersEvent($walletTransaction));

                    $this->notifyCreateUsers($walletUser, $admins, 'Transactions', 'Demande de transfert est en cours de validation', 'Demande de Retrait effectuer par  ' . ' ' . $walletTransaction->wallet->sidUser->firstname);

                    if ($admins->count() > 0) {
                        $this->notifyAdmins($admins);
                    }

                    $this->resetTransactionField();
                    $this->emit('modal-hide-transfers');
                    $this->emit('success', 'Retrait effectué avec success !');

                    // Send Email
                    if ($wallet_search->sidUser->email != null) {

                        Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Robus vous a retiré la somme de  : ' . $walletTransaction->amount . ' Sur votre compte ' . ' Votre montant est de : ' .  $walletUser->wallet->amount));
                    }

                    if ($walletUser->email != null) {
                        $this->sendMailNotification($walletTransaction);
                    }
                } else {
                    $this->resetTransactionField();
                    $this->emit('modal-hide-transfers');
                    $this->emit('error', 'Pas de selection de providers !');
                }
            } else {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers');
                $this->emit('info', 'Vous devez faire un choix de selection !');
            }
        } catch (\Exception $exception) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    private function isUserNotEligible($wallet_search)
    {
        return !$wallet_search->sidUser->is_under_distributer ||
            !$wallet_search->sidUser->is_custumers ||
            !$wallet_search->sidUser->is_distributer ||
            (!$wallet_search->sidUser->is_supervisor && sabdy()->is_admin == 1);
    }

    private function handleError($message)
    {
        $this->resetTransactionField();
        $this->emit('modal-hide-transfers');
        $this->emit('error', $message);
    }

    private function convertAdminCommissionInter($amount, $walletSend, $walletReceveid)
    {

        $country_civ = Country::find(107);

        if ($walletSend->country->id == 107) {

            if ($walletSend->currencie->value >  $walletReceveid->currencie->value) {
                return $amount / $walletReceveid->currencie->value;
            } elseif ($walletSend->currencie->value ==  $walletReceveid->currencie->value) {
                return $amount;
            } else {
                return $amount * $walletReceveid->currencie->value;
            }
        } else {

            if ($walletReceveid->country->id == 107) {

                if ($walletReceveid->currencie->value >  $walletSend->currencie->value) {
                    // Convertion in FCA
                    $amount = $amount / $walletSend->currencie->value;

                    return $amount;
                } elseif ($walletReceveid->currencie->value ==  $walletSend->currencie->value) {
                    return $amount;
                } else {

                    $amount = $amount / $walletSend->currencie->value;

                    return $amount;
                }
            } else {

                if ($walletReceveid->currencie->value >  $walletSend->currencie->value) {
                    // Convertion in FCA
                    if ($country_civ->currencie->value >  $walletSend->currencie->value) {
                        $amount_cfa = $amount * $walletSend->currencie->value;
                        // convertion in valeurs
                        $amount = $amount_cfa / $walletReceveid->currencie->value;
                    } else {
                        // convertion in valeurs
                        $amount = $amount / $walletReceveid->currencie->value;
                    }
                    return $amount;
                } elseif ($walletReceveid->currencie->value ==  $walletSend->currencie->value) {
                    return $amount;
                } else {

                    if ($country_civ->currencie->value <  $walletSend->currencie->value) {
                        $amount_cfa = $amount * $walletSend->currencie->value;
                        // convertion in valeurs
                        $amount = $amount_cfa / $walletReceveid->currencie->value;
                    } else {
                        // convertion in valeurs
                        $amount = $amount / $walletReceveid->currencie->value;
                    }
                    return $amount;
                }
            }
        }
    }

    private function convertAdminCommission($amount, $wallet)
    {
        $country_civ = Country::find(107);

        $robus_currency = sabdy()->country->currencie->value;

        if ($country_civ->currencie->value >  $wallet->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value ==  $wallet->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }

    public function addTransactionWithdrawal()
    {

        $walletUser = Auth::guard('web')->user();
        $rt = rand(40, 500000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ_drawal = $this->getAdminDrawalCIVProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        if (sabdy()->country->name_groupe == 'CIV') {
            $percent_robus_distri =  TransactionCost::whereId(26)->first();
            $percent_robus_custumer =  TransactionCost::whereId(28)->first();
        } elseif (sabdy()->country->name_groupe == 'AFL') {
            $percent_robus_distri =  TransactionCost::whereId(32)->first();
            $percent_robus_custumer =  TransactionCost::whereId(37)->first();
        } elseif (sabdy()->country->name_groupe == 'AFC') {
            $percent_robus_distri =  TransactionCost::whereId(33)->first();
            $percent_robus_custumer =  TransactionCost::whereId(38)->first();
        } elseif (sabdy()->country->name_groupe == 'USA') {
            $percent_robus_distri =  TransactionCost::whereId(34)->first();
            $percent_robus_custumer =  TransactionCost::whereId(39)->first();
        } elseif (sabdy()->country->name_groupe == 'URP') {
            $percent_robus_custumer =  TransactionCost::whereId(40)->first();
            $percent_robus_distri =  TransactionCost::whereId(35)->first();
        } elseif (sabdy()->country->name_groupe == 'MAG') {
            $percent_robus_distri =  TransactionCost::whereId(36)->first();
            $percent_robus_custumer =  TransactionCost::whereId(41)->first();
        } elseif (sabdy()->country->name_groupe == 'ASIE') {
            $percent_robus_distri =  TransactionCost::whereId(44)->first();
            $percent_robus_custumer =  TransactionCost::whereId(46)->first();
        } elseif (sabdy()->country->name_groupe == 'AFE') {
            $percent_robus_distri =  TransactionCost::whereId(45)->first();
            $percent_robus_custumer =  TransactionCost::whereId(47)->first();
        }

        try {

            $this->validate(
                [
                    'code_validated' => 'required',
                ],
                [
                    'code_validated.required' => 'Code de validation obligatoire',
                ]
            );

            $verify_code = WalletTransaction::whereCodeValidated($this->code_validated)->first();

            $wallet_user = Wallet::whereWalletId($this->compte_number_robus)->first();

            // Verify Valeur Of transactions

            if ($verify_code->num_wallet == $wallet_user->wallet_id) {

                // Temps 5m of transactions

                $ecoule = strtotime($verify_code->created_at);

                $compare_date = date('H:i:s', $ecoule);

                $temps_ecoule = time() - strtotime($compare_date);

                $ecoule_min = date('i:s', $temps_ecoule);

                // Verifie valeur code validation delete temps Elapsed 
                if ($ecoule_min > '05:20') {
                    $verify_code->delete();
                    $this->resetTransactionField();
                    $this->emit('modal-hide-transfers');
                    $this->emit('error', 'Les 5 min son ecoule !');
                } else {


                    // If person use reaload acompte is distributer of s-distributer
                    if ($walletUser->is_under_distributer == 1) {

                        // Valur off commission
                        $commission_valeur = $percent_robus_distri->percent * $verify_code->amount;
                        // 30% with drawal
                        $valuer_with_drawal = $percent_robus_distri->percent_with_drawal * $commission_valeur;
                    } else {

                        // Valur off commission
                        $commission_valeur = $percent_robus_custumer->percent * $verify_code->amount;
                        // 30% with drawal
                        $valuer_with_drawal = $percent_robus_custumer->percent_with_drawal * $commission_valeur;
                    }


                    $this->updateVerifyCodeWallet($verify_code);

                    $this->updatePaiementWithdrawalWallet($wallet_user, $verify_code->amount);

                    $transaction = $this->createNewInvoiceTransaction($verify_code, $transactionCompiCode, $transactionCode);

                    $this->createInvoiceTransactionDrawal($transaction, $verify_code);

                    $this->TransactionDistributorDrawal(
                        $valuer_with_drawal,
                        $admin_civ,
                        $transaction
                    );

                    $this->updatePaiementDrawalWallet($walletUser,  $verify_code->amount);

                    $this->updateWalletCommissions($verify_code,  $valuer_with_drawal);

                    event(new CreateWalletTransfersEvent($transaction));

                    $message = 'Vous avez effectué un retrait de ' .
                        $verify_code->amount . $walletUser->wallet->currencie->code .
                        ' ' .
                        'par ' . $verify_code->provider->name .
                        ' a ' . $verify_code->created_at .
                        'votre demande est en-cours de validation ' .
                        'Robus vous remercie';

                    if ($this->shouldNotifyCI($verify_code)) {

                        $this->sendSuccessMessageNotification($verify_code, $message);
                    }

                    $this->notifyCreateUsers($walletUser, $admins, 'Transactions', 'Demande de transfert est en cours de validation', 'Demande de Retrait effectuer par  ' . ' ' . $verify_code->wallet->sidUser->firstname);

                    if ($admins->count() > 0) {
                        $this->notifyAdmins($admins);
                    }

                    $message = 'Vous avez effectué un retrait de ' .
                        $verify_code->amount . $walletUser->wallet->currencie->code .
                        ' ' .
                        'par ' . $verify_code->provider->name .
                        ' a ' . $verify_code->created_at .
                        'votre demande est en-cours de validation ' .
                        'Robus vous remercie';

                    if ($this->shouldNotifyCI($verify_code)) {

                        $this->sendSuccessMessageNotification($verify_code, $message);
                    }

                    $this->resetTransactionField();
                    $this->emit('modal-hide-transfers');
                    $this->emit('success', 'Transfers effectué !');

                    if ($walletUser->email != null) {
                        $this->sendMailNotification($transaction);
                    }
                }
            } else {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers');
                $this->emit('error', 'Erreur de validation !');
            }
        } catch (\Exception $exception) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function back($step)
    {
        $this->currentStep = $step;
    }


    // Add Paiment
    public function addPaiement()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_payment)->first();
        $offer = Offer::whereId($this->offer_paiement)->first();
        $som_transfers =  $this->getSumValTransactionProperty();

        // Verify solde of User Auth
        $valeur_ceiling = $walletUser->silver_ceiling;

        try {

            if ($provider) {

                if ($walletUser->country->code == 'CI') {

                    //Provider CIE  
                    $percent_robus = PaymentCost::whereId(2)->first();

                    if ($this->provider_payment == 8) {

                        // Validation rules and messages
                        $validationRules = [
                            'paiement_name_cie' => 'required',
                            'paiement_amount_total' => 'required',
                        ];
                        $validationMessages = [
                            'paiement_amount_total.required' => 'Votre montant est obligatoire',
                            'paiement_name_cie.required' => 'Nom est obligatoire',
                        ];

                        if ($this->facture_choix_type == 1) {
                            $validationRules['paiement_id_invoice_pre'] = 'required';
                            $validationMessages['paiement_id_invoice_pre.required'] = 'Numero est obligatoire';
                        } elseif ($this->facture_choix_type == 2) {
                            $validationRules['paiement_id_invoice'] = 'required';
                            $validationMessages['paiement_id_invoice.required'] = 'Numero est obligatoire';
                        }

                        // Validate the input
                        $this->validate($validationRules, $validationMessages);

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                            return;
                        }

                        // dd($this->paiement_amount_total);
                        $reel_amount =  $this->search_amount($this->paiement_amount_total);

                        // $validatedamountDays->search_amount(2000);
                        $valAmount = $reel_amount +  $percent_robus->amount_auther;

                        $amount_solde =  $walletUser->wallet->amount -  $valAmount;

                        if ($valAmount > $walletUser->wallet->amount) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Verifier votre montant  svp !');
                            return;
                        }

                        $this->stepPaiment = 2;
                    } elseif ($this->provider_payment == 10) {

                        //   Provider SODECI
                        $validationRules = [
                            'paiement_name_sodecie' => 'required',
                            'paiement_num_contrat' => 'required|max:9',
                            'paiement_amount_t_sodecie' => 'required',
                        ];
                        $validationMessages = [
                            'paiement_amount_t_sodecie.required' =>
                            'Votre montant est obligatoire',
                            'paiement_name_sodecie.required' =>
                            'Nom  est obligatoire',
                            'paiement_num_contrat.required' =>
                            'Numero  est obligatoire',
                        ];


                        // Validate the input
                        $this->validate($validationRules, $validationMessages);

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                            return;
                        }

                        $reel_amount = $this->search_amount($this->paiement_amount_t_sodecie);

                        $valAmount = $reel_amount +  $percent_robus->amount_auther;

                        $amount_solde =  $walletUser->wallet->amount -  $valAmount;

                        if ($valAmount > $walletUser->wallet->amount) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Verifier votre montant  svp !');
                            return;
                        }

                        $this->stepPaiment = 2;
                    } elseif ($this->provider_payment == 7) {


                        // Percent commission providers
                        $val_amount = $offer->amount + $percent_robus->amount_canal;

                        switch ($this->paiementChoix_phone) {
                            case 1:
                                $validationRules = [
                                    'paiement_phone_canal' => 'required',
                                    'offer_paiement' => 'required',
                                ];
                                $validationMessages = [
                                    'offer_paiement.required' => 'Offre est obligatoire',
                                    'paiement_phone_canal.required' => 'Numero est obligatoire',
                                ];
                                break;

                            case 2:
                                $validationRules = [
                                    'paiement_decoder_canal' => 'required',
                                    'offer_paiement' => 'required',
                                ];
                                $validationMessages = [
                                    'offer_paiement.required' => 'Offre est obligatoire',
                                    'paiement_decoder_canal.required' => 'Code est obligatoire',
                                ];
                                break;
                        }

                        // Validate the input
                        $this->validate($validationRules, $validationMessages);

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                            return;
                        }

                        $amount_solde =  $walletUser->wallet->amount - $val_amount;

                        if ($val_amount > $walletUser->wallet->amount) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Verifier votre montant  svp !');
                            return;
                        }

                        $this->stepPaiment = 2;
                    }

                    if ($som_transfers >= $valeur_ceiling) {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-inters');
                        $this->emit('warning', 'Votre montant journalier est atteint !');
                        return false;
                    }
                   
                } else {
                    $this->resetPaiementField();
                    $this->emit('modal-hide-paiement');
                    $this->emit('warning', 'Vous n\'etes pas en CIV !');
                }
            } else {
                $this->emit('error', 'Paiement existante !');
            }
        } catch (\Exception $exception) {
            $this->resetPaiementField();
            $this->emit('modal-hide-paiement');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addPaiementValidated()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_payment)->first();
        $offer = Offer::whereId($this->offer_paiement)->first();
        $admins = $this->getAdminValeurProperty();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        // Verify solde of User Auth
        $valeur_ceiling = $walletUser->silver_ceiling;

        try {

            if ($provider) {

                $this->validate(
                    [
                        'password_validated' => 'required',
                    ],
                    [
                        'password_validated.required' =>
                        'Mot de passe est obligatoire',
                    ]
                );

                if (Hash::check($this->password_validated, sabdy()->password)) {
                    if ($walletUser->country->code == 'CI') {

                        //Provider CIE  
                        $percent_robus = PaymentCost::whereId(2)->first();

                        if ($this->provider_payment == 8) {

                            // Validation rules and messages
                            $validationRules = [
                                'paiement_name_cie' => 'required',
                                'paiement_amount_total' => 'required',
                            ];
                            $validationMessages = [
                                'paiement_amount_total.required' => 'Votre montant est obligatoire',
                                'paiement_name_cie.required' => 'Nom est obligatoire',
                            ];

                            if ($this->facture_choix_type == 1) {
                                $validationRules['paiement_id_invoice_pre'] = 'required';
                                $validationMessages['paiement_id_invoice_pre.required'] = 'Numero est obligatoire';
                            } elseif ($this->facture_choix_type == 2) {
                                $validationRules['paiement_id_invoice'] = 'required';
                                $validationMessages['paiement_id_invoice.required'] = 'Numero est obligatoire';
                            }

                            // Validate the input
                            $this->validate($validationRules, $validationMessages);

                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('warning', 'Votre montant journier est atteint !');
                                return;
                            }

                            // dd($this->paiement_amount_total);
                            $reel_amount =  $this->search_amount($this->paiement_amount_total);

                            // $validatedamountDays->search_amount(2000);
                            $valAmount = $reel_amount +  $percent_robus->amount_auther;

                            $amount_solde =  $walletUser->wallet->amount -  $valAmount;

                            if ($valAmount > $walletUser->wallet->amount) {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('error', 'Verifier votre montant  svp !');
                                return;
                            }

                            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer || $walletUser->is_under_distributer) {

                                $walletPayment = $this->createNewPaiement(
                                    $walletUser,
                                    $reel_amount,
                                    $this->paiement_decoder_canal,
                                    $this->paiement_id_invoice_pre,
                                    $this->paiement_id_invoice,
                                    $this->paiement_num_contrat,
                                    $this->pre_phone_invoice,
                                    $this->paiement_name_cie,
                                    $provider,
                                    $this->paiement_phone_canal,
                                    $offer,
                                    $percent_robus,
                                    $this->paiementChoix_phone,
                                    $this->paiementChoix_type,
                                    $this->facture_choix_type,
                                    $amount_solde,
                                );

                                $commission =  $walletPayment->amount +  $percent_robus->amount_auther;

                                if ($walletPayment != null) {
                                    $this->updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $commission);
                                }

                                $message = 'Vous avez effectué un paiement de ' . $walletPayment->amount .
                                    $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $walletPayment->provider->name .
                                    ' le ' .
                                    $walletPayment->created_at .
                                    ' votre demande est en cours de validation ' .
                                    'Robus vous remercie';

                                if ($this->shouldNotifyCreateCI($walletUser)) {

                                    $this->sendSuccessMessageCreateNotification($walletUser, $message);
                                }

                                event(new CreateWalletPaiementEvent($walletPayment));

                                $this->notifyCreateUsers($walletUser, $admins, 'Paiement de facture ', 'Demande de paiement est en cours de validation', 'Demande de paiement de ' . ' ' . $walletPayment->wallet->sidUser->firstname);

                                if ($admins->count() > 0) {
                                    $this->notifyAdmins($admins);
                                }

                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('success', 'Votre demande de transaction a bien été prise en compte.');
                                if ($walletUser->email != null) {
                                    $this->sendMailPaiementNotification($walletPayment);
                                }
                            } else {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                                return;
                            }
                        } elseif ($this->provider_payment == 10) {

                            //   Provider SODECI
                            $validationRules = [
                                'paiement_name_sodecie' => 'required',
                                'paiement_num_contrat' => 'required|max:9',
                                'paiement_amount_t_sodecie' => 'required',
                            ];
                            $validationMessages = [
                                'paiement_amount_t_sodecie.required' =>
                                'Votre montant est obligatoire',
                                'paiement_name_sodecie.required' =>
                                'Nom  est obligatoire',
                                'paiement_num_contrat.required' =>
                                'Numero  est obligatoire',
                            ];


                            // Validate the input
                            $this->validate($validationRules, $validationMessages);

                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('warning', 'Votre montant journier est atteint !');
                                return;
                            }

                            $reel_amount = $this->search_amount($this->paiement_amount_t_sodecie);

                            $valAmount = $reel_amount +  $percent_robus->amount_auther;

                            $amount_solde =  $walletUser->wallet->amount -  $valAmount;

                            if ($valAmount > $walletUser->wallet->amount) {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('error', 'Verifier votre montant  svp !');
                                return;
                            }

                            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                                $walletPayment = $this->createNewPaiementSodeci(
                                    $walletUser,
                                    $reel_amount,
                                    $this->paiement_amount_p_sodecie,
                                    $this->paiement_decoder_canal,
                                    $this->paiement_id_invoice,
                                    $this->paiement_num_contrat,
                                    $this->pre_phone_invoice,
                                    $this->pre_id_invoice,
                                    $this->paiement_name_sodecie,
                                    $provider,
                                    $percent_robus,
                                    $this->paiement_phone_canal,
                                    $offer,
                                    $this->paiementChoix_phone,
                                    $this->paiementChoix_type,
                                    $this->facture_choix_type,
                                    $amount_solde,
                                );


                                $commission =  $walletPayment->amount +  $percent_robus->amount_auther;

                                if ($walletPayment != null) {
                                    $this->updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $commission);
                                }

                                $message = 'Vous avez effectué un paiement de ' . $walletPayment->amount .
                                    $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $walletPayment->provider->name .
                                    ' le ' .
                                    $walletPayment->created_at .
                                    ' votre demande est en cours de validation ' .
                                    'Robus vous remercie';

                                if ($this->shouldNotifyCreateCI($walletUser)) {

                                    $this->sendSuccessMessageCreateNotification($walletUser, $message);
                                }

                                event(new CreateWalletPaiementEvent($walletPayment));

                                $this->notifyCreateUsers($walletUser, $admins, 'Paiement de facture ', 'Demande de paiement est en cours de validation', 'Demande de paiement de ' . ' ' . $walletPayment->wallet->sidUser->firstname);

                                if ($admins->count() > 0) {
                                    $this->notifyAdmins($admins);
                                }

                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('success', 'Votre demande de transaction a bien été prise en compte.');
                                if ($walletUser->email != null) {
                                    $this->sendMailPaiementNotification($walletPayment);
                                }
                            } else {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                                return; // Arrête l'exécution si le montant est supérieur au solde
                            }
                        } elseif ($this->provider_payment == 7) {


                            // Percent commission providers
                            $val_amount = $offer->amount + $percent_robus->amount_canal;

                            switch ($this->paiementChoix_phone) {
                                case 1:
                                    $validationRules = [
                                        'paiement_phone_canal' => 'required',
                                        'offer_paiement' => 'required',
                                    ];
                                    $validationMessages = [
                                        'offer_paiement.required' => 'Offre est obligatoire',
                                        'paiement_phone_canal.required' => 'Numero est obligatoire',
                                    ];
                                    break;

                                case 2:
                                    $validationRules = [
                                        'paiement_decoder_canal' => 'required',
                                        'offer_paiement' => 'required',
                                    ];
                                    $validationMessages = [
                                        'offer_paiement.required' => 'Offre est obligatoire',
                                        'paiement_decoder_canal.required' => 'Code est obligatoire',
                                    ];
                                    break;
                            }

                            // Validate the input
                            $this->validate($validationRules, $validationMessages);

                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('warning', 'Votre montant journier est atteint !');
                                return;
                            }

                            $amount_solde =  $walletUser->wallet->amount - $val_amount;

                            if ($val_amount > $walletUser->wallet->amount) {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('error', 'Verifier votre montant  svp !');
                                return;
                            }

                            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                                $walletPayment = $this->createNewPaiementCanal(
                                    $walletUser,
                                    $offer,
                                    $this->paiement_decoder_canal,
                                    $this->paiement_id_invoice,
                                    $this->paiement_num_contrat,
                                    $this->pre_phone_invoice,
                                    $this->pre_id_invoice,
                                    $this->paiement_name_sodecie,
                                    $provider,
                                    $percent_robus,
                                    $this->paiement_phone_canal,
                                    $this->paiementChoix_phone,
                                    $this->paiementChoix_type,
                                    $this->facture_choix_type,
                                    $amount_solde,
                                );

                                $commission = $walletPayment->amount +  $percent_robus->amount_canal;

                                if ($walletPayment != null) {
                                    $this->updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $commission);
                                }

                                $message = 'Vous avez effectué un paiement de ' . $walletPayment->amount .
                                    $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $walletPayment->provider->name .
                                    ' le ' .
                                    $walletPayment->created_at .
                                    ' votre demande est en cours de validation ' .
                                    'Robus vous remercie';

                                if ($this->shouldNotifyCreateCI($walletUser)) {

                                    $this->sendSuccessMessageCreateNotification($walletUser, $message);
                                }


                                event(new CreateWalletPaiementEvent($walletPayment));
                                $this->notifyCreateUsers($walletUser, $admins, 'Paiement de facture ', 'Demande de paiement est en cours de validation', 'Demande de paiement de ' . ' ' . $walletPayment->wallet->sidUser->firstname);
                                if ($admins->count() > 0) {
                                    $this->notifyAdmins($admins);
                                }

                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('success', 'Votre demande de transaction a bien été prise en compte.');
                                if ($walletUser->email != null) {
                                    $this->sendMailPaiementNotification($walletPayment);
                                }
                            } else {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                                return; // Arrête l'exécution si le montant est supérieur au solde
                            }
                        }
                    } else {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('warning', 'Vous n\'etes pas en CIV !');
                    }
                } else {
                    $this->resetPaiementField();
                    $this->emit('modal-hide-paiement');
                    $this->emit('warning', 'Veirifiez votre mot de passe SVP !');
                }
            } else {
                $this->emit('error', 'Paiement existante !');
            }
        } catch (\Exception $exception) {
            $this->resetPaiementField();
            $this->emit('modal-hide-paiement');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    // Add Virement 
    public function addVirement()
    {
        $walletUser = Auth::guard('web')->user();
        $som_transfers =  $this->getSumValTransactionProperty();
        $valeur_ceiling = $walletUser->silver_ceiling;

        try {

            if ($this->provider_virement == 11 || $this->provider_virement == 12) {

                $this->validate(
                    [
                        'firstname_user' => 'required',
                        'lastname_user' => 'required',
                        'phone_user' => 'required',
                        'account_number_user_bank' => 'required',
                        'amount_virement' => 'required',
                        'provider_virement' => 'required',
                    ],
                    [
                        'firstname_user.required' => 'Votre nom est obligatoire',
                        'lastname_user.required' => 'Votre prenom est obligatoire',
                        'phone_user.required' => 'Votre numero est obligatoire',
                        'account_number_user_bank.required' =>
                        'Votre nuomero de compte est obligatoire',
                        'amount_virement.required' =>
                        'Votre montant est obligatoire',
                        'provider_virement;required' =>
                        'Votre fournisseur est obligatoire',
                    ]
                );

                // Percent comission an valeur
                $percent_robus = BankCost::whereId(2)->first();

                if ($som_transfers >= $valeur_ceiling) {
                    $this->resetVirementField();
                    $this->emit('modal-hide-virement');
                    $this->emit('warning', 'Votre montant journier est atteint !');
                    return; // Arrête l'exécution si le montant est supérieur au solde
                }

                $valeur_end = $percent_robus->levelBankIvories->last();

                if ($valeur_end->amount_end < $this->amount_inter) {
                    $this->resetVirementField();
                    $this->emit('modal-hide-virement');
                    $this->emit('info', 'Vous avez depasser le montant max !');
                    return;
                    // Arrête l'exécution si le montant est supérieur au solde
                }
                $this->stepVirement = 2;
            } else {
                $this->emit('error', 'Veuillez selectionnez un virement !');
            }

            if ($som_transfers >= $valeur_ceiling) {
                $this->resetVirementField();
                $this->emit('modal-hide-inters');
                $this->emit('warning', 'Votre montant journalier est atteint !');
                return false;
            }
        } catch (\Exception $exception) {
            $this->resetVirementField();
            $this->emit('modal-hide-virement');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }


    public function addValidatedVirement()
    {
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_virement)->first();
        $country_virement = Country::whereId($this->country_virement)
            ->whereStatus(true)
            ->first();
        $admins = $this->getAdminValeurProperty();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();
        $valeur_ceiling = $walletUser->silver_ceiling;

        try {

            $this->validate(
                [
                    'password_validated' => 'required',
                ],
                [
                    'password_validated.required' =>
                    'Mot de passe est obligatoire',
                ]
            );

            if (Hash::check($this->password_validated, sabdy()->password)) {

                if ($this->provider_virement == 11 || $this->provider_virement == 12) {

                    $this->validate(
                        [
                            'firstname_user' => 'required',
                            'lastname_user' => 'required',
                            'phone_user' => 'required',
                            'account_number_user_bank' => 'required',
                            'amount_virement' => 'required',
                            'provider_virement' => 'required',
                        ],
                        [
                            'firstname_user.required' => 'Votre nom est obligatoire',
                            'lastname_user.required' => 'Votre prenom est obligatoire',
                            'phone_user.required' => 'Votre numero est obligatoire',
                            'account_number_user_bank.required' =>
                            'Votre nuomero de compte est obligatoire',
                            'amount_virement.required' =>
                            'Votre montant est obligatoire',
                            'provider_virement;required' =>
                            'Votre fournisseur est obligatoire',
                        ]
                    );

                    // Percent comission an valeur
                    $percent_robus = BankCost::whereId(2)->first();

                    if ($som_transfers >= $valeur_ceiling) {
                        $this->resetVirementField();
                        $this->emit('modal-hide-virement');
                        $this->emit('warning', 'Votre montant journier est atteint !');
                        return; // Arrête l'exécution si le montant est supérieur au solde
                    }

                    $valeur_end = $percent_robus->levelBankIvories->last();

                    if ($valeur_end->amount_end < $this->amount_inter) {
                        $this->resetVirementField();
                        $this->emit('modal-hide-virement');
                        $this->emit('info', 'Vous avez depasser le montant max !');
                        return;
                        // Arrête l'exécution si le montant est supérieur au solde
                    }

                    $reel_amount = $this->search_amount($this->amount_virement);

                    foreach ($percent_robus->levelBankIvories as $amount_user) {

                        // Verify in interval valeur of amount Use send
                        if ($reel_amount >= $amount_user->amount_begin && $reel_amount <= $amount_user->amount_end) {

                            $amount_valeur = $walletUser->is_under_distributer == 1 ? $reel_amount : $reel_amount + $amount_user->amount;
                            $amountReceved = $walletUser->is_under_distributer == 1 ? (float) $reel_amount : (float) $reel_amount -  (float) $amount_user->amount;
                            $soldeWallet = $walletUser->wallet->amount;

                            if ($amount_valeur > $soldeWallet) {

                                $this->resetVirementField();
                                $this->emit('modal-hide-virement');
                                $this->emit('warning', 'Verifier votre montant !');
                                return;
                            } else {

                                $amount_solde =  $walletUser->wallet->amount -  $amount_valeur;

                                if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {



                                    $walletBank = $this->createNewVirement(
                                        $walletUser,
                                        $reel_amount,
                                        $this->virementDescription,
                                        $provider,
                                        $this->account_number_user_bank,
                                        $amount_user,
                                        $this->firstname_user,
                                        $this->lastname_user,
                                        $this->phone_user,
                                        $amount_solde,
                                        $country_virement,
                                        $amountReceved
                                    );


                                    $message = 'Vous avez effectué un virement de ' . $walletBank->amount . $walletUser->wallet->currencie->code .
                                        ' ' .
                                        'par ' . $walletBank->provider->name .
                                        ' a ' . $walletBank->firstname .
                                        ' ' . $walletBank->firstname .
                                        ' le ' . $walletBank->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';

                                    if ($this->shouldNotifyCreateCI($walletUser)) {

                                        $this->sendSuccessMessageCreateNotification($walletUser, $message);
                                    }


                                    if ($walletBank != null) {

                                        $this->updateamountVirementReceveid($admin_civ, $amount_user, $walletBank, $walletUser, $amount_valeur);
                                    }

                                    event(new CreateWalletBankEvent($walletBank));
                                } else {

                                    $this->resetVirementField();
                                    $this->emit('modal-hide-virement');
                                    $this->emit('error', 'Desolez vous n\'etes pas autorisée !');
                                    return; // Arrête l'exécution si le montant est supérieur au solde
                                }

                                $this->notifyCreateUsers($walletUser, $admins, 'Virement bancaire', 'Demande de virement est en cours de validation', 'Demande de virement de ' . ' ' . $walletBank->wallet->sidUser->firstname);

                                if ($admins->count() > 0) {
                                    $this->notifyAdmins($admins);
                                }
                                $this->resetVirementField();
                                $this->emit('modal-hide-virement');
                                $this->emit('success', 'Votre demande de transaction a bien été prise en compte.');
                                if ($walletUser->email != null) {
                                    $this->sendMailVersementNotification($walletBank);
                                }
                                // Arrête l'exécution si le montant est supérieur au solde
                            }
                        }
                    }
                } else {
                    $this->emit('error', 'Veuillez selectionnez un virement !');
                }
            } else {
                $this->resetVirementField();
                $this->emit('modal-hide-virement');
                $this->emit('error', 'Verifiez votre mot de passe !');
            }
        } catch (\Exception $exception) {
            $this->resetVirementField();
            $this->emit('modal-hide-virement');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }
    // Validators id
    protected $validReloadCardProviders = [13, 14, 15, 16, 17];

    protected function validateReloadCard()
    {
        $this->validate([
            'amount_reloadcard' => 'required|numeric|min:2000',
            'account_number_reload_card' => 'required',
            'name_reloadcard' => 'required',
        ], [
            'amount_reloadcard.required' => 'Votre montant est obligatoire',
            'amount_reloadcard.numeric' => 'Le montant doit être numérique',
            'amount_reloadcard.min' => 'Le montant minimum est de 2000',
            'name_reloadcard.required' => 'Nom est obligatoire',
            'account_number_reload_card.required' => 'Numero est obligatoire',
        ]);
    }
    // Add Reload card
    public function addReloadCard()
    {
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_reloadcard)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers =  $this->getSumValTransactionProperty();

        try {

            if ($provider) {

                $percent_robus = DepositCost::whereId(2)->first();


                if ($walletUser->country->code != 'CI') {
                    return $this->emit('error', 'Ce réchargement se fait que en CI !');
                }


                // Provider Bsic and Ecobank
                if (in_array($this->provider_reloadcard, $this->validReloadCardProviders)) {

                    $this->validateReloadCard();

                    if ($som_transfers >= $valeur_ceiling) {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-reloadCard');
                        $this->emit('warning', 'Votre montant journier est atteint !');
                        return;
                    }


                    if (sabdy()->is_under_distributer) {
                        $valeur_first = $percent_robus->levelDepositIvories->where('is_ivoires', true)->first();
                        $valeur_end = $percent_robus->levelDepositIvories->where('is_ivoires', true)->last();
                        $all_valeurs = $percent_robus->levelDepositIvories->where('is_ivoires', true);
                    } else {

                        $valeur_first = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->first();
                        $valeur_end = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->last();
                        $all_valeurs = $percent_robus->levelDepositIvories->where('is_inter_bank', true);
                    }

                    $reel_amount = $this->search_amount($this->amount_reloadcard);

                    if ($valeur_end->amount_end < $reel_amount) {
                        $this->resetReloadCardField();
                        $this->emit('modal-hide-reloadCard');
                        $this->emit('info', 'Vous avez depasser le montant max !'  . $$valeur_end->amount_end);
                        return;
                    }


                    if ($valeur_first->amount_begin > $reel_amount) {
                        $this->resetReloadCardField();
                        $this->emit('modal-hide-reloadCard');
                        $this->emit('info', 'Vous avez depasser le montant min !' . $valeur_first->amount_begin);
                        return;
                    }

                    $this->stepReloadCard = 2;
                } else {
                    $this->emit('error', 'Aucune selection !');
                }

                if ($som_transfers >= $valeur_ceiling) {
                    $this->resetReloadCardField();
                    $this->emit('modal-hide-inters');
                    $this->emit('warning', 'Votre montant journalier est atteint !');
                    return false;
                }
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetReloadCardField();
            $this->emit('modal-hide-reloadCard');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedReloadCard()
    {
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_reloadcard)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $admins = $this->getAdminValeurProperty();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        try {

            if ($provider) {

                $percent_robus = DepositCost::whereId(2)->first();


                if ($walletUser->country->code != 'CI') {
                    return $this->emit('error', 'Ce réchargement se fait que en CI !');
                }


                $this->validate(
                    [
                        'password_validated' => 'required',
                    ],
                    [
                        'password_validated.required' =>
                        'Mot de passe est obligatoire',
                    ]
                );

                if (Hash::check($this->password_validated, sabdy()->password)) {
                    // Provider Bsic and Ecobank
                    if (in_array($this->provider_reloadcard, $this->validReloadCardProviders)) {

                        $this->validateReloadCard();

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                            return;
                        }


                        if (sabdy()->is_under_distributer) {
                            $valeur_first = $percent_robus->levelDepositIvories->where('is_ivoires', true)->first();
                            $valeur_end = $percent_robus->levelDepositIvories->where('is_ivoires', true)->last();
                            $all_valeurs = $percent_robus->levelDepositIvories->where('is_ivoires', true);
                        } else {

                            $valeur_first = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->first();
                            $valeur_end = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->last();
                            $all_valeurs = $percent_robus->levelDepositIvories->where('is_inter_bank', true);
                        }

                        $reel_amount = $this->search_amount($this->amount_reloadcard);

                        if ($valeur_end->amount_end < $reel_amount) {
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('info', 'Vous avez depasser le montant max !'  . $$valeur_end->amount_end);
                            return;
                        }


                        if ($valeur_first->amount_begin > $reel_amount) {
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('info', 'Vous avez depasser le montant min !' . $valeur_first->amount_begin);
                            return;
                        }



                        foreach ($all_valeurs as $amount_user) {

                            if ($reel_amount >= $amount_user->amount_begin && $reel_amount <= $amount_user->amount_end) {

                                //  If amount is Null

                                if ($amount_user->amount == null) {

                                    $percent_valeur = $amount_user->percent * $reel_amount;

                                    if (sabdy()->is_under_distributer) {

                                        $amount_verify = $reel_amount;
                                        $valuer_deposit_com = $amount_user->comission_distributer * $reel_amount;
                                        $valuer_admin_com = $percent_valeur - $valuer_deposit_com;
                                        $amount_cost = $amount_user->comission_distributer * $reel_amount;
                                        $amountReceved = (float)$reel_amount;
                                    } else {
                                        $amount_verify = $reel_amount;
                                        $valuer_admin_com =  $amount_user->percent * $reel_amount;
                                        $amount_cost = $amount_user->percent * $percent_valeur;
                                        $valuer_deposit_com = 0;
                                        $amountReceved = (float)$reel_amount - (float)$percent_valeur;
                                    }
                                } else {

                                    $percent_valeur = (float)$amount_user->amount + (float)$reel_amount;

                                    if (sabdy()->is_under_distributer) {

                                        $amount_verify =  (float)$reel_amount;
                                        $valuer_deposit_com = $amount_user->comission_distributer;
                                        $valuer_admin_com = $reel_amount - $valuer_deposit_com;
                                        $amount_cost = $amount_user->comission_distributer;
                                        $amountReceved = (float)$reel_amount;
                                    } else {
                                        $amount_verify = (float)$reel_amount;
                                        $amount_cost = $amount_user->amount;
                                        $valuer_deposit_com = 0;
                                        $valuer_admin_com = (float)$amount_user->amount;
                                        $amountReceved = (float)$reel_amount - (float)$amount_user->amount;
                                    }
                                }


                                $amount_solde =  $walletUser->wallet->amount -  $amount_verify;

                                $soldeWallet = $walletUser->wallet->amount;

                                if ($amount_verify > $soldeWallet) {

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit(
                                        'error',
                                        'Verifier votre montant  svp ou le montant minimum !'
                                    );
                                    return;
                                }

                                if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                                    $walletReloadCard = $this->createNewReloadCard(
                                        $amount_solde,
                                        $walletUser,
                                        $reel_amount,
                                        $this->reloadcard_description,
                                        $this->account_number_reload_card,
                                        $this->name_reloadcard,
                                        $amount_cost,
                                        $provider,
                                        $amountReceved,
                                    );


                                    $message = 'Vous avez effectué un rechargement  de ' .
                                        $walletReloadCard->amount .
                                        $walletUser->wallet->currencie->code .
                                        ' ' .
                                        'par ' .
                                        $walletReloadCard->provider->name .
                                        ' le ' .
                                        $walletReloadCard->created_at .
                                        ' votre demande est en cours de validation ' .
                                        'Robus vous remercie';

                                    if ($this->shouldNotifyCreateCI($walletUser)) {

                                        $this->sendSuccessMessageCreateNotification($walletUser, $message);
                                    }


                                    if ($walletReloadCard != null) {
                                        $this->updateamountReloadCardReceveid($admin_civ, $valuer_deposit_com, $walletReloadCard, $valuer_admin_com, $walletUser, $amount_verify);
                                    }

                                    event(new CreateWalletReloadCardEvent($walletReloadCard));

                                    $this->notifyCreateUsers($walletUser, $admins, 'Rechargement de carte', 'Demande de Rechargement de carte est en cours de validation', 'Demande de Rechargement de carte de ' . ' ' . $walletReloadCard->wallet->sidUser->firstname);

                                    if ($admins->count() > 0) {
                                        $this->notifyAdmins($admins);
                                    }

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit(
                                        'success',
                                        'Votre demande de transaction a bien été prise en compte.'
                                    );

                                    if ($walletUser->email != null) {
                                        $this->sendMailReloadCardNotification($walletReloadCard);
                                    }
                                } else {

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                                    return; // Arrête l'exécution si le montant est supérieur au solde
                                }
                            }
                        }
                    } else {
                        $this->emit('error', 'Aucune selection !');
                    }
                } else {
                    $this->resetReloadCardField();
                    $this->emit('modal-hide-reloadCard');
                    $this->emit('success', 'Verifiez votre mot de passe svp !');
                }
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetReloadCardField();
            $this->emit('modal-hide-reloadCard');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    // Add Phone Credit
    public function addPhoneCredit()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_phone_credit)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers =  $this->getSumValTransactionProperty();

        try {

            if ($provider) {

                // Providers MTN , MOOV , ORANGE , Wave
                $percent_robus = PhoneCreditCost::whereId(1)->first();

                if ($walletUser->country->code != 'CI') {
                    $this->resetPaiementField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit(
                        'warning',
                        'Ce réchargement est uniquement pour la CIV !'
                    );
                    return;
                }

                $this->validate(
                    [
                        'provider_phone_credit' => 'required',
                        'number_phone_credit' => 'required',
                        'amount_phone_credit' => 'required',
                    ],
                    [
                        'provider_phone_credit.required' =>
                        'Fournisseur est obligatoire',
                        'number_phone_credit.required' =>
                        'Numero est obligatoire',
                        'amount_phone_credit.required' =>
                        'Montant est obligatoire',
                    ]
                );


                if ($som_transfers >= $valeur_ceiling) {

                    $this->resetPaiementField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                    return;
                }

                $reel_amount = $this->search_amount($this->amount_phone_credit);


                $val_amount = $reel_amount +  $percent_robus->percent *  $reel_amount;

                if ($val_amount > $walletUser->wallet->amount) {
                    $this->resetPaiementField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit(
                        'error',
                        'Verifier votre montant  svp !'
                    );
                    return;
                }

                $this->stepPhoneCredit = 2;
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetPaiementField();
            $this->emit('modal-hide-phoneCredit');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedPhoneCredit()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_phone_credit)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $admins = $this->getAdminValeurProperty();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        try {

            if ($provider) {

                $this->validate(
                    [
                        'password_validated' => 'required',
                    ],
                    [
                        'password_validated.required' =>
                        'Mot de passe est obligatoire',
                    ]
                );


                if (Hash::check($this->password_validated, sabdy()->password)) {
                    // Providers MTN , MOOV , ORANGE , Wave
                    $percent_robus = PhoneCreditCost::whereId(1)->first();

                    if ($walletUser->country->code != 'CI') {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-phoneCredit');
                        $this->emit(
                            'warning',
                            'Ce réchargement est uniquement pour la CIV !'
                        );
                        return;
                    }

                    $this->validate(
                        [
                            'provider_phone_credit' => 'required',
                            'number_phone_credit' => 'required',
                            'amount_phone_credit' => 'required',
                        ],
                        [
                            'provider_phone_credit.required' =>
                            'Fournisseur est obligatoire',
                            'number_phone_credit.required' =>
                            'Numero est obligatoire',
                            'amount_phone_credit.required' =>
                            'Montant est obligatoire',
                        ]
                    );


                    if ($som_transfers >= $valeur_ceiling) {

                        $this->resetPaiementField();
                        $this->emit('modal-hide-phoneCredit');
                        $this->emit(
                            'warning',
                            'Votre montant journier est atteint !'
                        );
                        return;
                    }

                    $reel_amount = $this->search_amount($this->amount_phone_credit);


                    $val_amount = $reel_amount +  $percent_robus->percent *  $reel_amount;

                    if ($val_amount > $walletUser->wallet->amount) {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-phoneCredit');
                        $this->emit(
                            'error',
                            'Verifier votre montant  svp !'
                        );
                        return;
                    }

                    $amount_solde =  $walletUser->wallet->amount -  $val_amount;

                    if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                        $amountReceved = $reel_amount;

                        $walletPhone = $this->createNewPhoneCredit(
                            $walletUser,
                            $reel_amount,
                            $provider,
                            $percent_robus,
                            $this->number_phone_credit,
                            $amount_solde,
                            $amountReceved
                        );

                        $message =
                            'Vous avez effectué un paiement de credit de ' .
                            $walletPhone->amount .
                            $walletUser->wallet->currencie->code .
                            ' ' .
                            'par ' .
                            $walletPhone->provider->name .
                            ' le ' .
                            $walletPhone->created_at .
                            ' votre demande est en cours de validation ' .
                            'Robus vous remercie';


                        if ($this->shouldNotifyCreateCI($walletUser)) {

                            $this->sendSuccessMessageCreateNotification($walletUser, $message);
                        }



                        if ($walletPhone != null) {
                            $this->updateamountPhoneCreditReceveid($admin_civ, $percent_robus, $walletPhone,  $walletUser, $reel_amount);
                        }


                        event(new CreateWalletPhoneCreditEvent($walletPhone));

                        $this->notifyCreateUsers($walletUser, $admins, 'Paiement crédit', 'Demande de paiement de credit est en cours de validation', 'Demande de paiement de ' . ' ' . $walletPhone->wallet->sidUser->firstname);

                        if ($admins->count() > 0) {
                            $this->notifyAdmins($admins);
                        }

                        $this->resetPhoneCreditField();
                        $this->emit('modal-hide-phoneCredit');
                        $this->emit('success', 'Votre demande de transaction a bien été prise en compte !');
                        if ($walletUser->email != null) {
                            $this->sendMailPhoneCreditNotification($walletPhone);
                        }
                    } else {
                        $this->resetPhoneCreditField();
                        $this->emit('modal-hide-phoneCredit');
                        $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                        return; // Arrête l'exécution si le montant est supérieur au solde
                    }
                } else {
                    $this->resetPhoneCreditField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit('error', 'VQerifiez votre mot de passe SVP !');
                }
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetPaiementField();
            $this->emit('modal-hide-phoneCredit');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }
    // Begin Edit informations
    public function editReloadCard($id, $state)
    {
        $check = WalletDeposit::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->reloadcardId = $id;
        $this->reloadcard_description_edit = $check->description;
        $this->reloadcardStatus = null;
        $this->provider_reloadcard_edit = $provider->name;
        $this->amount_reloadcard_edit = $check->amount;
        $this->account_number_reloadcard_edit = $check->num_carte;
        $this->name_reloadcard_edit = $check->name;
        $this->currency_custumers = $check->wallet->currencie->code ?? null;
        $this->emit($state);
    }

    // Edit Phone credit
    public function editPhoneCredit($id, $state)
    {
        $check = WalletPhoneCredit::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->phoneCreditId = $id;
        $this->phoneCreditStatus = null;
        $this->provider_phone_credit_edit = $provider->name;
        $this->number_phone_credit_edit = $check->phone;
        $this->amount_phone_credit_edit = $check->amount;
        $this->currency_custumers = $check->wallet->currencie->code ?? null;
        $this->emit($state);
    }

    // Edit Transaction
    public function editTransaction($id, $state)
    {
        $check = WalletTransaction::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->transactionId = $id;
        $this->transactionStatus = null;
        $this->provider_transaction_edit = $provider->name;
        $this->transaction_description_edit = $check->description ?? null;
        $this->transaction_phone_edit = $check->phone ?? null;
        $this->compte_number_robus_edit = $check->num_wallet ?? null;
        $this->transaction_amount_robus_edit = $check->amount ?? null;
        $this->transaction_amount_money_edit = $check->amount ?? null;
        $this->transaction_description_robus_edit = $check->description ?? null;
        $this->currency_custumers = $check->wallet->currencie->code ?? null;
        $this->emit($state);
    }

    public function editValidatedTransaction($id, $state)
    {


        $check = WalletTransaction::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->transactionId = $id;
        $this->transactionStatus = null;
        $this->provider_transaction_edit = $provider->name;
        $this->transaction_description_edit = $check->description ?? null;
        $this->transaction_phone_edit = $check->phone ?? null;
        $this->compte_number_robus_edit = $check->num_wallet ?? null;
        $this->transaction_amount_robus_edit = $check->amount ?? null;
        $this->transaction_amount_money_edit = $check->amount ?? null;
        $this->transaction_description_robus_edit = $check->description ?? null;
        $this->currency_custumers = $check->wallet->currencie->code ?? null;
        $this->emit($state);
    }

    // Edit inter
    public function editInter($id, $state)
    {
        $check = WalletInter::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $country = Country::whereId($check->country_id)->first();
        $offer = Offer::whereId($check->offer_id)->first();
        // Mooney paiement
        $this->interId = $id;
        $this->inter_description_edit = $check->description ?? null;
        $this->inter_description_robus_edit = $check->description ?? null;
        $this->interStatus = null;
        $this->provider_inter_edit = $provider->id;
        $this->country_inter_edit = $country->id ?? null;
        $this->amount_inter_edit = $check->amount ?? null;
        $this->account_number_inter_edit = $check->number_compte ?? null;
        $this->phone_inter_edit = $check->phone ?? null;
        $this->amount_inter_robus_edit = $check->amount ?? null;
        $this->country_inter_robus_edit = $country->id ?? null;
        $this->currency_custumers = $check->wallet->currencie->code ?? null;
        //Edit Invoice

        $this->choose_type_paiement = $check->provider_type ?? null;
        $this->paiement_num_contrat_edit = $check->number_contract ?? null;
        $this->paiement_id_invoice_edit = $check->id_invoice;
        $this->paiement_decoder_canal_edit = $check->number_decoder ?? null;
        $this->paiement_phone_canal_edit = $check->number_phone ?? null;
        $this->paiement_name_sodecie_edit = $check->name ?? null;
        $this->paiement_name_cie_edit = $check->name ?? null;
        $this->paiement_amount_t_sodecie_edit = $check->amount ?? null;
        $this->paiement_amount_total_edit = $check->amount ?? null;
        $this->facture_choix_type_edit = $check->factureChoix_type ?? null;
        $this->paiementChoix_phone_edit = $check->paiementChoix_phone ?? null;
        $this->offer_paiement_edit = $offer->name ?? null;
        $this->phone_invoice_pre_edit = $check->phone ?? null;
        $this->pre_id_invoice_edit = $check->id_invoice_pre ?? null;

        // Edit Phone credit
        $this->provider_phone_credit_inter_edit = $provider->name ?? null;
        $this->number_phone_credit_inter_edit = $check->phone ?? null;
        $this->amount_phone_credit_inter_edit = $check->amount ?? null;
        $this->country_inter_robus_inter_edit =  $check->wallet->country->name;
        $this->devise_user =   $check->wallet->currencie->code;

        // Edit Virement bank
        $this->provider_Inter_virement_edit = $provider->name ?? null;
        $this->firstname_user_inter_edit = $check->firstname .  ' ' . $check->lastname ?? null;
        $this->name_relo_user_inter_edit = $check->name ?? null;
        $this->phone_user_inter_edit = $check->phone ?? null;
        $this->amount_virement_inter_edit = $check->amount ?? null;
        $this->account_number_user_bank_inter_edit = $check->number_compte ?? null;
        $this->amount_virement_nsia_inter_edit = $check->amount ?? null;

        //  Edit Paiment Invoice
        $this->phone_invoice_inter_edit = $check->phone_invoice ?? null;
        $this->id_invoice_inter_edit =  $check->id_invoice ?? null;

        $this->emit($state);
    }

    // Edit Paiment
    public function editPaiement($id, $state)
    {
        $check = WalletPayment::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $offer = Offer::whereId($check->offer_id)->first();
        $this->paiementId = $id;
        $this->provider_payment_edit = $provider->id;
        $this->provider_payment_name = $provider->name;
        $this->paiement_num_contrat_edit = $check->number_contract ?? null;
        $this->paiement_id_invoice_edit = $check->id_invoice;
        $this->paiement_decoder_canal_edit = $check->number_decoder ?? null;
        $this->paiement_phone_canal_edit = $check->number_phone ?? null;
        $this->paiement_name_sodecie_edit = $check->name ?? null;
        $this->paiement_name_cie_edit = $check->name ?? null;
        $this->paiement_amount_t_sodecie_edit = $check->amount ?? null;
        $this->paiement_amount_total_edit = $check->amount ?? null;
        $this->paiementStatus = null;
        $this->facture_choix_type_edit = $check->factureChoix_type ?? null;
        $this->paiementChoix_phone_edit = $check->paiementChoix_phone ?? null;
        $this->offer_paiement_edit = $offer->name ?? null;
        $this->offer_paiement_edit_amount = $offer->amount ?? null;
        $this->phone_invoice_pre_edit = $check->phone ?? null;
        $this->pre_id_invoice_edit = $check->id_invoice_pre ?? null;
        $this->devise_user =   $check->wallet->currencie->code;

        $this->emit($state);
    }

    // Edit Virement
    public function editVirement($id, $state)
    {
        $check = WalletBank::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $country = Country::whereId($check->country_id)->first();
        $this->virementId = $id;
        $this->virementStatus = null;
        $this->virementDescription_edit = $check->description;
        $this->provider_virement_edit = $provider->name;
        $this->firstname_user_edit = $check->firstname;
        $this->lastname_user_edit = $check->lastname;
        $this->phone_user_edit = $check->phone;
        $this->country_virement_edit = $country->name ?? null;
        $this->amount_virement_edit = $check->amount;
        $this->account_number_user_edit = $check->number_compte;
        $this->currency_custumers = $check->wallet->currencie->code ?? null;
        $this->emit($state);
    }


    // Begin Update informations

    public function updatePhoneCredit()
    {
        $check = WalletPhoneCredit::whereId($this->phoneCreditId)->first();
        $transactionData = $this->generateTransactionData();
        $transactionCode = $transactionData['transactionCode'];
        $transactionCompiCode = $transactionData['transactionCompiCode'];
        $user_auth = $transactionData['user_auth'];
        $admins = $transactionData['admins'];
        $admin_civ = $transactionData['admin_civ'];
        $percent_robus = PhoneCreditCost::whereId(1)->first();


        try {

            if ($check) {

                $this->validate([
                    'phoneCreditStatus' => 'required',
                    'password_transaction' => 'required',
                ]);

                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description' => $this->description_phone_credit ?? null,
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status' => $this->phoneCreditStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->phoneCreditStatus = "";
                    $this->phoneCreditId = "";

                    if ($check->updated_by == Auth::guard('web')->user()->id) {

                        if ($check->status == 'SUCCESS') {

                            if ($check->updated_by == Auth::guard('web')->user()->id) {


                                $commission_transaction = $check->amount;

                                $paymentPhoneCredit = $this->createPhoneCredit($check, $transactionCode, $transactionCompiCode);

                                $this->createInvoicePhoneCredit($check, $paymentPhoneCredit);

                                $this->updateUserWallet($commission_transaction);

                                if (Auth::guard('web')->user()->is_distributer == 1) {

                                    $this->updateAdminCom($check, $commission_transaction);
                                }

                                $this->handleAdminCommission($check, $admin_civ);

                                $this->notifyUsers($check, $admins, 'Credit téléphonique', 'Demande de paiement de crédit a été validé', 'Demande de paiement de crédit validé');

                                $message = 'Votre demande de credit de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' le ' .
                                    $check->updated_at .
                                    ' a été acceptée avec succès ' .
                                    'Robus vous remercie';

                                if ($this->shouldNotifyCI($check)) {

                                    $this->sendSuccessMessageNotification($check, $message);
                                }

                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('success', 'Achat de credit accepté !');
                                if ($admins->count() > 0) {
                                    $this->notifyAdmins($admins);
                                }
                                $this->sendMailPhoneCreditNotification($check);
                            } else {

                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('error', 'Pas vous le valideur !!');
                                return; // Arrête l'exécution si le montant est supérieur au solde
                            }
                        } elseif ($check->status == 'ERROR') {

                            if ($check->updated_by == Auth::guard('web')->user()->id) {


                                $commission = $check->amount + $check->costs_amount;

                                $this->updateReloadWallet($check, $commission);

                                $this->updateWalletNull($check, $commission);


                                $this->handleReloadClientCommission($check, $admin_civ);

                                $paymentPhoneCredit  = $this->createErrorPhoneCredit($check, $transactionCode, $transactionCompiCode);

                                $this->createInvoicePhoneCredit($check, $paymentPhoneCredit);

                                $this->notifyUsers($check, $admins, 'Credit téléphonique', 'Demande de crédit a été refusé', 'Demande de crédit refusé');

                                $message = 'Votre demande de crédit de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' le ' .
                                    $check->updated_at .
                                    ' a été refusée ' .
                                    'Robus vous remercie';

                                if ($this->shouldNotifyCI($check)) {

                                    $this->sendSuccessMessageNotification($check, $message);
                                }

                                if ($admins->count() > 0) {
                                    $this->notifyAdmins($admins);
                                }

                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('error', 'Achat de credit refusé !');
                                $this->sendMailPhoneCreditNotification($check);
                            } else {
                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('error', 'Pas de modification !');
                                return; // Arrête l'exécution si le montant est supérieur au solde
                            }
                        } else {
                            $this->resetPhoneCreditField();
                            $this->emit('modal-hide-phoneCredit-validated');
                            $this->emit('modal-hide-phoneCredit');
                            $this->emit('error', 'Pas de modification !');
                            return; // Arrête l'exécution si le montant est supérieur au solde

                        }
                    } else {
                        $this->resetPhoneCreditField();
                        $this->emit('modal-hide-phoneCredit');
                        $this->emit('modal-hide-phoneCredit-validated');
                        $this->emit('error', 'Pas le valideur !');
                        return; // Arrête l'exécution si le montant est supérieur au solde
                    }
                } else {
                    $this->resetPhoneCreditField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit('modal-hide-phoneCredit-validated');
                    $this->emit('error', 'Mot de passe Incorrect !');
                    return; // Arrête l'exécution si le montant est supérieur au solde

                }
            } elseif ($this->password_transaction = "" ||  $this->phoneCreditStatus = "") {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit-validated');
                $this->emit('modal-hide-phoneCredit');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
                return; // Arrête l'exécution si le montant est supérieur au solde

            } else {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit');
                $this->emit('modal-hide-phoneCredit-validated');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
                return; // Arrête l'exécution si le montant est supérieur au solde
            }
        } catch (\Exception $exception) {
            $this->resetPhoneCreditField();
            $this->emit('modal-hide-phoneCredit-validated');
            $this->emit('modal-hide-phoneCredit');
            $this->emit('error', 'Vous avez cliquez deux fois ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function updateReloadCard()
    {
        $check = WalletDeposit::whereId($this->reloadcardId)->first();
        $transactionData = $this->generateTransactionData();
        $transactionCode = $transactionData['transactionCode'];
        $transactionCompiCode = $transactionData['transactionCompiCode'];
        $user_auth = $transactionData['user_auth'];
        $admins = $transactionData['admins'];
        $admin_civ = $transactionData['admin_civ'];

        try {

            if ($check) {

                $this->validate([
                    'reloadcardStatus' => 'required',
                    'password_transaction' => 'required',
                ]);



                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description' => $this->reloadcard_description_edit ?? null,
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status' => $this->reloadcardStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->reloadcardStatus = "";
                    $this->reloadcardId = "";

                    if ($check->updated_by == Auth::guard('web')->user()->id) {

                        if ($check->status == 'SUCCESS') {


                            $commission_transaction =  $check->amount_received;


                            $depositWallet = $this->createDeposit($check, $transactionCode, $transactionCompiCode);

                            $this->createInvoiceReloadCard($check, $depositWallet);

                            $this->updateUserWallet($commission_transaction);

                            if (Auth::guard('web')->user()->is_distributer == 1) {

                                $this->updateAdminCom($check, $commission_transaction);
                            }


                            $this->notifyUsers($check, $admins, 'Réchargement de carte', 'Demande de réchargement de carte a été validé', 'Demande de rechargement de carte validé');


                            $message = 'Votre demande de rechargement de carte de ' .
                                $check->amount .
                                $check->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $check->provider->name .
                                ' le ' .
                                $check->updated_at .
                                ' a été acceptée avec succès ' .
                                'Robus vous remercie';

                            if ($this->shouldNotifyCI($check)) {

                                $this->sendSuccessMessageNotification($check, $message);
                            }

                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->wallet->sidUser->is_under_distributer == true) {

                                if ($valeur_amount_com != null) {

                                    $this->handleUnderDistributorCommission($check, $admin_civ);
                                } else {
                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('success', 'Rechargement Acceptée sans commission sous-distibuteur !');
                                    return; // Arrête l'exécution si le montant est supérieur au solde
                                }
                            } else {

                                $this->handleAdminCommission($check, $admin_civ);
                            }

                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard-validated');
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('success', 'Rechargement acceptée !');

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }

                            $this->sendMailReloadCardNotification($check);
                        } elseif ($check->status == 'ERROR') {

                            if ($check->wallet->sidUser->is_under_distributer == 1) {
                                $commission =  $check->amount;
                            } else {
                                $commission =  $check->amount + $check->costs_amount;
                            }

                            $this->updateReloadWallet($check, $commission);

                            $this->updateWalletNull($check, $commission);

                            // Valeur amount commission distributeur
                            $valeur_amount_com = $check->adminCom->amount;

                            $this->handleReloadClientCommission($check, $admin_civ);

                            $this->notifyUsers($check, $admins, 'Réchargement de carte', 'Demande de réchargement de carte a été refusée', 'Demande de rechargement de carte refusée');

                            $message = 'Votre demande de rechargement de carte de ' .
                                $check->amount .
                                $check->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $check->provider->name .
                                ' le ' .
                                $check->updated_at .
                                ' a été refusée ' .
                                'Robus vous remercie';

                            if ($this->shouldNotifyCI($check)) {

                                $this->sendSuccessMessageNotification($check, $message);
                            }


                            $depositWallet = $this->createErrorDeposit($check, $transactionCode, $transactionCompiCode);

                            $this->createInvoiceReloadCard($check, $depositWallet);
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard-validated');
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('error', 'Rechargement refusé !');

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }

                            $this->sendMailReloadCardNotification($check);
                        } else {
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard-validated');
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('error', 'Pas de modification !');
                            return; // Arrête l'exécution si le montant est supérieur au solde
                        }
                    } else {
                        $this->resetReloadCardField();
                        $this->emit('modal-hide-reloadCard-validated');
                        $this->emit('modal-hide-reloadCard');
                        $this->emit('error', 'Pas le valideur !');
                        return; // Arrête l'exécution si le montant est supérieur au solde

                    }
                } else {
                    $this->resetReloadCardField();
                    $this->emit('modal-hide-reloadCard-validated');
                    $this->emit('modal-hide-reloadCard');
                    $this->emit('info', 'Vérifier votre mot de passe !');
                    return; // Arrête l'exécution si le montant est supérieur au solde
                }
            } elseif ($this->password_transaction = "") {
                $this->resetReloadCardField();
                $this->emit('modal-hide-reloadCard-validated');
                $this->emit('modal-hide-reloadCard');
                $this->emit('info', 'Vous avez cliquez deux fois de suite le boutton !!');
            } else {
                $this->resetReloadCardField();
                $this->emit('modal-hide-reloadCard-validated');
                $this->emit('modal-hide-reloadCard');
                $this->emit('error', 'Aucune donnée ou Vous avez cliquez deux fois de suite !');
            }
        } catch (\Exception $exception) {
            $this->resetReloadCardField();
            $this->emit('modal-hide-reloadCard-validated');
            $this->emit('modal-hide-reloadCard');
            $this->emit('error', 'Vous avez cliquez deux fois Ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function updateTransaction()
    {

        $check = WalletTransaction::whereId($this->transactionId)->first();
        $transactionData = $this->generateTransactionData();
        $transactionCode = $transactionData['transactionCode'];
        $transactionCompiCode = $transactionData['transactionCompiCode'];
        $user_auth = $transactionData['user_auth'];
        $admins = $transactionData['admins'];
        $admin_civ = $transactionData['admin_civ'];

        try {


            if ($check) {

                $this->validate([
                    'transactionStatus' => 'required',
                    'password_transaction' => 'required',
                ]);

                if (Hash::check($this->password_transaction, $user_auth->password)) {


                    $commission_transaction = $check->amount_received;

                    $check->update([
                        'description' => $this->transaction_description_edit ?? null,
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status' => $this->transactionStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->transactionStatus = "";
                    $this->transactionId = "";

                    if ($check->updated_by == Auth::guard('web')->user()->id) {

                        if ($check->status == 'SUCCESS') {


                            $transaction = $this->createTransaction($check, $transactionCode, $transactionCompiCode);
                            $this->createInvoiceTransaction($check, $transaction);
                            $this->updateUserWallet($commission_transaction);

                            if (Auth::guard('web')->user()->is_distributer == 1) {

                                $this->updateAdminCom($check, $commission_transaction);
                            }

                            if ($this->shouldNotifyCI($check)) {
                                $this->sendSuccessNotification($check);
                            }


                            if ($check->wallet->sidUser->is_under_distributer == true) {

                                //  Send valeur in Admin
                                $this->handleUnderDistributorCommission($check, $admin_civ);
                            } else {

                                //  Send valeur in Admin
                                $this->handleAdminCommission($check, $admin_civ);
                            }

                            $this->notifyUsers($check, $admins, 'Transfers', 'Demande de transfert a été validé', 'Demande de transfers validé');

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }
                            $this->cancelTransactionField();
                            $this->emit('modal-hide-transfers-validated');
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers accepte !');
                            $this->sendMailNotification($check);
                        } elseif ($check->status == 'ERROR') {


                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                if ($check->provider->id != 9) {


                                    $commission =  $check->amount;


                                    $this->updateReloadWallet($check, $commission);

                                    $this->updateWalletNull($check, $commission);


                                    $this->handleReloadCommission($check, $admin_civ);
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;

                                    $this->updateReloadWallet($check, $commission);

                                    $this->updateWalletNull($check, $commission);

                                    $this->handleReloadClientCommission($check, $admin_civ);
                                }
                            } else {

                                if ($check->provider->id != 9) {
                                    $commission =  $check->amount;
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;
                                }


                                $this->updateReloadWallet($check, $commission);

                                $this->updateWalletNull($check, $commission);

                                $this->handleReloadClientCommission($check, $admin_civ);
                            }

                            $transaction = $this->createErrorTransaction($check, $transactionCode, $transactionCompiCode);
                            $this->createInvoiceTransaction($check, $transaction);

                            $this->notifyUsers($check, $admins, 'Transfers', 'Demande de transfert a été refusé', 'Demande de transfers refusé');

                            if ($this->shouldNotifyCI($check)) {
                                $this->sendErrorNotification($check);
                            }

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }

                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers-validated');
                            $this->emit('modal-hide-transfers');
                            $this->emit('warning', 'Transfers Refusé !');

                            $this->sendMailNotification($check);
                        }
                    } else {
                        $this->cancelTransactionField();
                        $this->emit('modal-hide-transfers-validated');
                        $this->emit('modal-hide-transfers');
                        $this->emit('error', 'Aucune donnée !');
                        return; // Arrête l'exécution si le montant est supérieur au solde

                    }
                } else {
                    $this->cancelTransactionField();
                    $this->emit('modal-hide-transfers-validated');
                    $this->emit('modal-hide-transfers');
                    $this->emit('error', 'Vérifier votre mot de passe !');
                    return; // Arrête l'exécution si le montant est supérieur au solde

                }
            } elseif ($this->password_transaction = "") {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers-validated');
                $this->emit('modal-hide-transfers');
                $this->emit('info', 'Vous avez cliquez dexu fois le boutton !!');
                return; // Arrête l'exécution si le montant est supérieur au solde

            } else {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers-validated');
                $this->emit('modal-hide-transfers');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite !');
            }
        } catch (\Exception $exception) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers-validated');
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Vous avez cliquez deux fois sur le button ou une erreur veuillez informer Support client ROBUS !');
        }
    }

    public function updateVirement()
    {
        $check = WalletBank::whereId($this->virementId)->first();
        $transactionData = $this->generateTransactionData();
        $transactionCode = $transactionData['transactionCode'];
        $transactionCompiCode = $transactionData['transactionCompiCode'];
        $user_auth = $transactionData['user_auth'];
        $admins = $transactionData['admins'];
        $admin_civ = $transactionData['admin_civ'];
        try {

            if ($check) {

                $this->validate([
                    'virementStatus' => 'required',
                    'password_transaction' => 'required',
                ]);


                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description' => $this->virementDescription_edit ?? null,
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status' => $this->virementStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->virementStatus = "";
                    $this->virementId = "";

                    if ($check->updated_by == Auth::guard('web')->user()->id) {


                        if ($check->status == 'SUCCESS') {

                            $commission_transaction = $check->amount_received;

                            $paymentCreated = $this->createVirement($check, $transactionCode, $transactionCompiCode);

                            $this->createInvoiceVirement($check, $paymentCreated);

                            $this->updateUserWallet($commission_transaction);

                            if (Auth::guard('web')->user()->is_distributer == 1) {

                                $this->updateAdminCom($check, $commission_transaction);
                            }

                            $this->notifyUsers($check, $admins, 'Versement bancaire', 'Demande de versement a été validé', 'Demande de versement a été validé');

                            $message =
                                'Votre demande de versement de ' .
                                $check->amount .
                                $check->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $check->provider->name .
                                ' a ' .
                                $check->updated_at .
                                ' a été validée avec succès ' .
                                'Robus vous remercie';

                            if ($this->shouldNotifyCI($check)) {

                                $this->sendSuccessMessageNotification($check, $message);
                            }

                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->wallet->sidUser->is_under_distributer == true) {

                                if ($valeur_amount_com != null) {

                                    $this->handleUnderDistributorCommission($check, $admin_civ);
                                } else {
                                    $this->resetVirementField();
                                    $this->emit('modal-hide-virement');
                                    $this->emit('modal-hide-virement-validated');
                                    $this->emit('success', 'Versement Acceptée sans commission sous-distibuteur !');
                                    return; // Arrête l'exécution si le montant est supérieur au solde
                                }
                            } else {

                                $this->handleAdminCommission($check, $admin_civ);
                            }

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }

                            $this->resetVirementField();
                            $this->emit('modal-hide-virement');
                            $this->emit('modal-hide-virement-validated');
                            $this->emit('success', 'Virement Accepté !');
                            $this->sendMailVersementNotification($check);
                        } elseif ($check->status == 'ERROR') {

                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                $commission =  $check->amount;
                            } else {

                                $commission =  $check->amount + $check->costs_amount;
                            }

                            $this->updateReloadWallet($check, $commission);

                            $this->updateWalletNull($check, $commission);


                            $this->handleReloadClientCommission($check, $admin_civ);

                            $virementWallet  = $this->createErrorVirement($check, $transactionCode, $transactionCompiCode, $commission);

                            $this->createInvoiceVirement($check, $virementWallet);

                            $this->notifyUsers($check, $admins, 'Versement bancaire', 'Demande de versement a été refusé', 'Demande de versement refusé');

                            $message =
                                'Votre demande de virement de ' .
                                $check->amount .
                                $check->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $check->provider->name .
                                ' a ' .
                                $check->firstname .
                                ' ' .
                                $check->firstname .
                                ' a été refusée' .
                                'le ' .
                                $check->updated_at .
                                ' ' .
                                'Robus vous remercie';

                            if ($this->shouldNotifyCI($check)) {

                                $this->sendSuccessMessageNotification($check, $message);
                            }

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }

                            $this->resetVirementField();
                            $this->emit('modal-hide-virement-validated');
                            $this->emit('modal-hide-virement');
                            $this->emit('error', 'Virement refusée !');
                            $this->sendMailVersementNotification($check);
                        } else {
                            $this->resetVirementField();
                            $this->emit('modal-hide-virement-validated');
                            $this->emit('modal-hide-virement');
                            $this->emit('success', 'Virement modifié !');
                        }
                    } else {
                        $this->resetVirementField();
                        $this->emit('modal-hide-virement');
                        $this->emit('modal-hide-virement-validated');
                        $this->emit('error', 'Pas vous le valideur !');
                        return; // Arrête l'exécution si le montant est supérieur au solde

                    }
                } else {
                    $this->resetVirementField();
                    $this->emit('modal-hide-virement-validated');
                    $this->emit('modal-hide-virement');
                    $this->emit('error', 'Mot de passe Incorrect !');
                    return; // Arrête l'exécution si le montant est supérieur au solde

                }
            } elseif ($this->password_transaction = "" ||  $this->virementStatus = "") {
                $this->resetVirementField();
                $this->emit('modal-hide-virement-validated');
                $this->emit('modal-hide-virement');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
                return; // Arrête l'exécution si le montant est supérieur au solde

            } else {
                $this->resetVirementField();
                $this->emit('modal-hide-virement-validated');
                $this->emit('modal-hide-virement');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
                return; // Arrête l'exécution si le montant est supérieur au solde
            }
        } catch (\Exception $exception) {
            $this->resetVirementField();
            $this->emit('modal-hide-virement');
            $this->emit('modal-hide-virement-validated');
            $this->emit('error', 'Vous avez cliquez deux fois ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function updateInter()
    {
        $check = WalletInter::whereId($this->interId)->first();
        $transactionData = $this->generateTransactionData();
        $transactionCode = $transactionData['transactionCode'];
        $transactionCompiCode = $transactionData['transactionCompiCode'];
        $user_auth = $transactionData['user_auth'];
        $admins = $transactionData['admins'];
        $admin_civ = $transactionData['admin_civ'];

        try {


            if ($check) {

                $this->validate(
                    [
                        'interStatus' => 'required',
                        'password_transaction' => 'required',
                    ],
                    [

                        'password_transaction.required' =>
                        'Mot de passe obligatoire',
                        'interStatus.required' =>
                        'Statut obligatoire',
                    ]
                );


                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description'  => $this->inter_description_robus,
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status' => $this->interStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->interStatus = "";
                    $this->interId = "";

                    if ($check->updated_by == Auth::guard('web')->user()->id) {

                        if ($check->status == 'SUCCESS') {


                            $transactionInter = $this->createInter($check, $transactionCode, $transactionCompiCode);
                            $this->createInvoiceInter($check, $transactionInter);

                            if (sabdy()->country->currencie->value > $check->country->currencie->value) {
                                //   Convertion  in CFA
                                $valeur_amount_receveid = (float) $check->amount_received * (float) $check->country->currencie->value;
                            } elseif (sabdy()->country->currencie->value == $check->country->currencie->value) {
                                $valeur_amount_receveid = $check->amount_received;
                            } elseif (sabdy()->country->currencie->value < $check->country->currencie->value) {
                                //   Convertion in Country send
                                $valeur_amount_receveid = (float) $check->amount_received  * (float) $check->country->currencie->value;
                            }

                            if (
                                $check->provider->id == 48
                                || $check->provider->id == 71
                                || $check->provider->id == 72
                                || $check->provider->id == 73
                                || $check->provider->id == 74
                                || $check->provider->id == 75
                                || $check->provider->id == 76
                                || $check->provider->id == 77
                                || $check->provider->id == 78
                                || $check->provider->id == 93
                                || $check->provider->id == 94
                                || $check->provider->id == 95
                                || $check->provider->id == 96
                                || $check->provider->id == 97
                                || $check->provider->id == 49
                                || $check->provider->id == 50
                                || $check->provider->id == 55
                                || $check->provider->id == 98
                                || $check->provider->id == 99
                                || $check->provider->id == 100
                                || $check->provider->id == 101
                                || $check->provider->id == 102

                            ) {

                                $commission_transaction = $valeur_amount_receveid;
                            } else {

                                $commission_transaction = $valeur_amount_receveid;
                            }

                            $this->updateUserWallet($commission_transaction);

                            if (Auth::guard('web')->user()->is_distributer == 1) {

                                $this->updateAdminCom($check, $commission_transaction);
                            }

                            $message =
                                'Votre demande de paiement de ' .
                                $check->amount .
                                $check->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $check->provider->name .
                                ' le ' .
                                $check->updated_at .
                                ' a été validé avec success \n' .
                                'Robus vous remercie';

                            if ($this->shouldNotifyCI($check)) {

                                $this->sendSuccessMessageNotification($check, $message);
                            }


                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->provider_type == 7 || $check->provider_type == 8) {

                                if ($check->wallet->sidUser->is_under_distributer == true) {

                                    if ($valeur_amount_com != null) {
                                        $this->handleUnderDistributorInterCommission($check, $admin_civ);
                                    } else {
                                        $this->resetReloadCardField();
                                        $this->emit('modal-hide-reloadCard');
                                        $this->emit('success', 'Rechargement Acceptée sans commission sous-distibuteur !');
                                        return; // Arrête l'exécution si le montant est supérieur au solde
                                    }
                                    //  Send valeur in Admin
                                } else {

                                    //  Send valeur in Admin
                                    $this->handleAdminCommission($check, $admin_civ);
                                }
                            } else {

                                $this->handleAdminCommission($check, $admin_civ);
                            }


                            $this->notifyUsers($check, $admins, 'Paiement internationnal', 'Demande de  transfers internationnal a été validé', 'Demande de transfers internationnal validé');

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }

                            $this->resetInterField();
                            $this->emit('modal-hide-inters-validated');
                            $this->emit('modal-hide-inters');
                            $this->emit('success', 'Transfers Accepte !');
                            $this->sendMailInterNotification($check);
                        } elseif ($check->status == 'ERROR') {


                            $isSpecialProvider = $this->isSpecialProvider($check->provider->id);

                            if ($check->wallet->sidUser->is_under_distributer == 1) {
                                $commission = $isSpecialProvider ? $check->amount : $check->amount + $check->costs_amount;
                            } else {
                                $commission = $isSpecialProvider ? $check->amount : $check->amount + $check->costs_amount;
                            }

                            $this->updateReloadWallet($check, $commission);

                            $this->updateWalletNull($check, $commission);

                            $this->handleReloadClientCommission($check, $admin_civ);

                            $interWallet  = $this->createErrorInter($check, $transactionCode, $transactionCompiCode);

                            $this->createInvoiceInter($check, $interWallet);

                            $this->notifyUsers($check, $admins, 'Paiement interbational', 'Demande de paiement international a été refusé', 'Demande de paiement international refusé');

                            $message = 'Votre demande de paiement de ' .
                                $check->amount .
                                $check->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $check->provider->name .
                                ' a ' .
                                $check->updated_at .
                                ' a été refusé  ' .
                                'Robus vous remercie';

                            if ($this->shouldNotifyCI($check)) {

                                $this->sendSuccessMessageNotification($check, $message);
                            }

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }

                            $this->resetInterField();
                            $this->emit('modal-hide-inters-validated');
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Transfers refusé !');
                            $this->sendMailInterNotification($check);
                        }
                    } else {
                        $this->resetInterField();
                        $this->emit('modal-hide-inters-validated');
                        $this->emit('modal-hide-inters');
                        $this->emit('error', 'Pas le valideur !');
                        return; // Arrête l'exécution si le montant est supérieur au solde

                    }
                } else {
                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('modal-hide-inters-validated');
                    $this->emit('error', 'Mot de passe Incorrect !');
                    return; // Arrête l'exécution si le montant est supérieur au solde
                }
            } elseif ($this->password_transaction = "" ||  $this->interStatus = "") {
                $this->resetInterField();
                $this->emit('modal-hide-inters-validated');
                $this->emit('modal-hide-inters');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
                return; // Arrête l'exécution si le montant est supérieur au solde

            }
        } catch (\Exception $exception) {
            $this->resetInterField();
            $this->emit('modal-hide-inters-validated');
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Vous avez cliquez deux fois Ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function updatePaiement()
    {
        $check = WalletPayment::whereId($this->paiementId)->first();
        $transactionData = $this->generateTransactionData();
        $transactionCode = $transactionData['transactionCode'];
        $transactionCompiCode = $transactionData['transactionCompiCode'];
        $user_auth = $transactionData['user_auth'];
        $admins = $transactionData['admins'];
        $admin_civ = $transactionData['admin_civ'];

        try {

            if ($check) {


                $this->validate([
                    'paiementStatus' => 'required',
                    'password_transaction' => 'required',
                ]);

                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description' => $this->payment_description_robus ?? null,
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status' => $this->paiementStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->paiementStatus = "";
                    $this->paiementId = "";

                    if ($check->updated_by == Auth::guard('web')->user()->id) {

                        if ($check->status == 'SUCCESS') {


                            $commission_transaction =  $check->amount;

                            $paymentCreated = $this->createPayment($check, $transactionCode, $transactionCompiCode);

                            $this->createInvoicePayment($check, $paymentCreated);

                            $this->updateUserWallet($commission_transaction);

                            if (Auth::guard('web')->user()->is_distributer == 1) {

                                $this->updateAdminCom($check, $commission_transaction);
                            }

                            $this->notifyUsers($check, $admins, 'Paiement de facture', 'Demande de paiement de facture a été validé', 'Demande de paiement de facture validé');

                            $message = 'Votre demande de paiement de facture de ' .
                                $check->amount .
                                $check->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $check->provider->name .
                                ' le ' .
                                $check->updated_at .
                                ' a été acceptée avec succès ' .
                                'Robus vous remercie';

                            if ($this->shouldNotifyCI($check)) {

                                $this->sendSuccessMessageNotification($check, $message);
                            }

                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->wallet->sidUser->is_under_distributer == true) {

                                if ($valeur_amount_com != null) {

                                    $this->handleUnderDistributorPaiementCommission($check, $admin_civ);
                                } else {
                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('success', 'Paiement Accepté !');
                                    $this->emit('success', 'Paiement Acceptée sans commission sous-distibuteur !');
                                    return; // Arrête l'exécution si le montant est supérieur au solde
                                }
                            } else {

                                $this->handleAdminCommission($check, $admin_civ);
                            }

                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('success', 'Paiement Accepté !');

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }

                            $this->sendMailPaiementNotification($check);
                        } elseif ($check->status == 'ERROR') {


                            $commission =  $check->amount + $check->costs_amount;

                            $this->updateReloadWallet($check, $commission);

                            $this->updateWalletNull($check, $commission);


                            $this->handleReloadClientCommission($check, $admin_civ);

                            $paymentCreated = $this->createErrorPayment($check, $transactionCode, $transactionCompiCode);

                            $this->createInvoicePayment($check, $paymentCreated);

                            $this->notifyUsers($check, $admins, 'Paiement de facture', 'Demande de paiement de facture a été refusé', 'Demande de paiement de facture refusé');

                            $message = 'Votre demande de paiement de facture de ' .
                                $check->amount .
                                $check->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $check->provider->name .
                                ' le ' .
                                $check->updated_at .
                                ' a été refusé ' .
                                'Robus vous remercie';

                            if ($this->shouldNotifyCI($check)) {

                                $this->sendSuccessMessageNotification($check, $message);
                            }

                            if ($admins->count() > 0) {
                                $this->notifyAdmins($admins);
                            }

                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement-validated');
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Payment Refusé !');
                            $this->sendMailPaiementNotification($check);
                        } else {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('modal-hide-paiement-validated');
                            $this->emit('success', 'Payment modifié !');
                            return; // Arrête l'exécution si le montant est supérieur au solde

                        }
                    } else {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('modal-hide-paiement-validated');
                        $this->emit('success', 'Pas vous le valideur !');
                        return; // Arrête l'exécution si le montant est supérieur au solde

                    }
                } else {

                    $this->resetPaiementField();
                    $this->emit('modal-hide-paiement-validated');
                    $this->emit('modal-hide-paiement');
                    $this->emit('info', 'Vérifier votre mot de passe !');
                    return; // Arrête l'exécution si le montant est supérieur au solde

                }
            } elseif ($this->password_transaction = "") {
                $this->resetPaiementField();
                $this->emit('modal-hide-paiement');
                $this->emit('modal-hide-paiement-validated');
                $this->emit('info', 'Vous avez cliquez deux fois de suite le boutton !!');
                return; // Arrête l'exécution si le montant est supérieur au solde

            } else {
                $this->resetPaiementField();
                $this->emit('modal-hide-paiement');
                $this->emit('modal-hide-paiement-validated');
                $this->emit('error', 'Aucune donnée ou Vous avez cliquez deux fois de suite !');
            }
        } catch (\Exception $exception) {
            $this->resetPaiementField();
            $this->emit('modal-hide-paiement-validated');
            $this->emit('modal-hide-paiement');
            $this->emit('error', 'Vous avez cliquez deux fois Ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    //Begin  Choose User adminto validated transactions

    protected function updateWallet($walletUpdate, $event, $modalHide, $menuActive, $infoMessage)
    {
        try {

            $walletUpdate->update([
                'updated_by' => Auth::guard('web')->user()->id,
            ]);

            event(new $event($walletUpdate));
            // $this->emit($modalHide);
            $this->emit($menuActive);
            $this->emit('info', $infoMessage);
        } catch (\Exception $exception) {
            $this->emit($menuActive);
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function ChooseUpdateTransaction($id)
    {
        $walletUpdate = WalletTransaction::findOrFail($id);

        try {

            if (Auth::guard('web')->user()->is_distributer == 1 || Auth::guard('web')->user()->is_admin == 1) {

                $this->updateWallet($walletUpdate, ChooseTransactionEvent::class, 'modal-hide-transfers', 'menu-transaction-active', 'Vous avez choisi transaction');
            } else {
                $this->emit('menu-inters-active');
                $this->emit('info', 'Desolez Vous avez ne pouvez pas choisi');
            }
        } catch (\Exception $exception) {
            $this->emit('menu-transaction-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function ChooseUpdatePayment($id)
    {
        $walletUpdate = WalletPayment::findOrFail($id);

        $sidAuth = Auth::guard('web')->user();

        try {

            if (Auth::guard('web')->user()->is_distributer == 1 || Auth::guard('web')->user()->is_admin == 1) {

                if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                    $this->updateWallet($walletUpdate, ChoosePaymentEvent::class, 'modal-hide-paiement', 'menu-paiement-active', 'Vous avez choisi un paiement');
                } else {
                    // $this->emit('modal-hide-paiement');
                    $this->emit('menu-paiement-active');
                    $this->emit(
                        'error',
                        'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                    );
                }
            } else {
                $this->emit('menu-paiement-active');
                $this->emit('info', 'Desolez Vous avez ne pouvez pas choisi');
            }
        } catch (\Exception $exception) {
            $this->emit('menu-paiement-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function ChooseUpdateInter($id)
    {
        $walletUpdate = WalletInter::findOrFail($id);

        try {

            if (Auth::guard('web')->user()->is_distributer == 1 || Auth::guard('web')->user()->is_admin == 1) {

                $this->updateWallet($walletUpdate, ChooseInterEvent::class, 'modal-hide-inters', 'menu-inters-active', 'Vous avez choisi transfert international');
            } else {
                $this->emit('menu-inters-active');
                $this->emit('info', 'Desolez Vous avez ne pouvez pas choisi');
            }
        } catch (\Exception $exception) {
            $this->emit('menu-inters-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }
    public function ChooseUpdateVirement($id)
    {
        $walletUpdate = WalletBank::findOrFail($id);
        $sidAuth = Auth::guard('web')->user();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {


                if (Auth::guard('web')->user()->is_distributer == 1 || Auth::guard('web')->user()->is_admin == 1) {

                    $this->updateWallet($walletUpdate, ChooseVirementEvent::class, 'modal-hide-virement', 'menu-virement-active', 'Vous avez choisi une demande de virement');
                } else {
                    $this->emit('menu-virement-active');
                    $this->emit('info', 'Desolez Vous avez ne pouvez pas choisi');
                }
            } else {
                //  $this->emit('modal-hide-virement');
                $this->emit('menu-virement-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-virement-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function ChooseUpdateReloadCard($id)
    {
        $walletUpdate = WalletDeposit::findOrFail($id);
        $sidAuth = Auth::guard('web')->user();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (Auth::guard('web')->user()->is_distributer == 1 || Auth::guard('web')->user()->is_admin == 1) {

                    $this->updateWallet($walletUpdate, ChooseReloadCardEvent::class, 'modal-hide-reloadCarte', 'menu-reloadCarte-active', 'Vous avez choisi rechargement de carte');
                } else {
                    $this->emit('menu-reloadCarte-active');
                    $this->emit('info', 'Desolez Vous avez ne pouvez pas choisi');
                }
            } else {
                $this->emit('menu-reloadCarte-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-reloadCarte-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function ChooseUpdatePhoneCredit($id)
    {
        $walletUpdate = WalletPhoneCredit::findOrFail($id);

        $sidAuth = Auth::guard('web')->user();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (Auth::guard('web')->user()->is_distributer == 1 || Auth::guard('web')->user()->is_admin == 1) {

                    $this->updateWallet($walletUpdate, ChoosePhoneCreditEvent::class, 'modal-hide-phoneCredit', 'menu-phoneCredit-active', 'Vous avez choisi le rechargement de credit telephonique');
                } else {
                    $this->emit('menu-phoneCredit-active');
                    $this->emit('info', 'Desolez Vous avez ne pouvez pas choisi');
                }
            } else {
                $this->emit('menu-phoneCredit-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-phoneCredit-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    // End Choose   

    // reset form Field
    public function resetInterField()
    {
        $this->provider_phone_credit_inter = null;
        $this->number_phone_credit_inter = '';
        $this->amount_phone_credit_inter = '';
        $this->country_inter_robus_inter = null;
        $this->provider_reloadcard_inter = null;
        $this->account_number_reload_card_inter = '';
        $this->choose_type_transaction_inter = ' ';
        $this->name_reloadcard_inter = '';
        $this->amount_reloadcard_inter = '';
        $this->inter_description = '';
        $this->inter_description_robus = '';
        $this->interId = '';
        $this->interStatus = '';
        $this->currentStep = 1;
        $this->provider_inter = null;
        $this->provider_payment_inter = null;
        $this->paiementChoix_phone = null;
        $this->offer_paiement = null;
        $this->facture_choix_type = null;
        $this->choose_type_paiement = null;
        $this->paiement_amount_total_inter = ' ';
        $this->paiement_name_sodecie_inter = '';
        $this->country_inter = null;
        $this->paiement_decoder_canal_inter = '';
        $this->paiement_name_cie_inter = '';
        $this->paiement_id_invoice_inter = '';
        $this->pre_id_invoice_inter = '';
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
        $this->amount_inter = '';
        $this->account_number_inter = '';
        // Provider virement
        $this->provider_Inter_virement = null;
        $this->phone_inter = '';
        $this->firstname_user_inter = '';
        $this->lastname_user_inter = '';
        $this->account_number_user_bank_inter = '';
        $this->phone_user_inter = '';
        $this->amount_virement_inter = '';
        // Code validated Transactions
        $this->code_validated = '';
        $this->amount_inter_robus = '';
        $this->country_inter_robus = null;
        $this->country_inter_robus = '';
        // $this->emit('modal-hide', 'hide');
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
    }

    public function resetInterOutCiv()
    {
        $this->amount_inter_out_civ = '';
        $this->provider_inter_out_civ = null;
        $this->country_inter_out_civ = null;
        $this->phone_inter_out_civ = '';
        // $this->emit('modal-hide', 'hide');
        $this->choose_type_paiement = null;
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
    }

    public function cancelCivInterField()
    {
        $this->inter_description = '';
        $this->inter_description_robus = '';
        $this->interId = '';
        $this->interStatus = '';
        $this->provider_inter = null;
        $this->provider_payment_inter = null;
        $this->country_inter = '';
        $this->paiementChoix_phone = null;
        $this->phone_inter_out_civ = '';
        $this->offer_paiement = null;
        $this->facture_choix_type = null;
        $this->choose_type_paiement = null;
        $this->paiement_amount_total_inter = ' ';
        $this->country_inter = null;
        $this->paiement_decoder_canal_inter = '';
        $this->paiement_name_cie_inter = '';
        $this->paiement_id_invoice_inter = '';
        $this->choose_type_transaction_inter = null;
        $this->pre_id_invoice_inter = '';
        $this->currentStep = 1;
        $this->code_validated = '';
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
        $this->country_inter_out_civ  = null;
        $this->amount_inter_out_civ  = '';
        // Provider virement
        $this->provider_Inter_virement = null;
        $this->phone_inter = '';
        $this->firstname_user_inter = '';
        $this->lastname_user_inter = '';
        $this->account_number_user_bank_inter = '';
        $this->phone_user_inter = '';
        $this->amount_virement_inter = '';
        $this->amount_inter = '';
        $this->account_number_inter = '';
        $this->phone_inter = '';
        $this->amount_inter_robus = '';
        $this->country_inter_robus = null;

        $this->emit('modal-hide-inters');
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
    }

    // Cancel field
    public function cancelInterField()
    {
        $this->inter_description = '';
        $this->inter_description_robus = '';
        $this->interId = '';
        $this->interStatus = '';
        $this->provider_inter = null;
        $this->provider_payment_inter = null;
        $this->country_inter = '';
        $this->paiementChoix_phone = null;
        $this->phone_inter_out_civ = '';
        $this->offer_paiement = null;
        $this->facture_choix_type = null;
        $this->choose_type_paiement = null;
        $this->paiement_amount_total_inter = ' ';
        $this->country_inter = null;
        $this->paiement_decoder_canal_inter = '';
        $this->paiement_name_cie_inter = '';
        $this->paiement_id_invoice_inter = '';
        $this->choose_type_transaction_inter = null;
        $this->pre_id_invoice_inter = '';
        $this->currentStep = 1;
        $this->code_validated = '';
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
        $this->country_inter_out_civ  = null;
        $this->amount_inter_out_civ  = '';
        // Provider virement
        $this->provider_Inter_virement = null;
        $this->phone_inter = '';
        $this->firstname_user_inter = '';
        $this->lastname_user_inter = '';
        $this->account_number_user_bank_inter = '';
        $this->phone_user_inter = '';
        $this->amount_virement_inter = '';
        $this->amount_inter = '';
        $this->account_number_inter = '';
        $this->phone_inter = '';
        $this->amount_inter_robus = '';
        $this->country_inter_robus = null;

        $this->emit('modal-hide-inters');
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
    }


    public function resetPhoneCreditField()
    {
        $this->provider_phone_credit = null;
        $this->number_phone_credit = '';
        $this->amount_phone_credit = '';
        $this->stepPhoneCredit = 1;
        $this->emit('modal-hide-phoneCredit-validated');
        $this->emit('menu-phoneCredit-active');
        // $this->emit('modal-hide-phoneCredit');
    }

    public function cancelPhoneCreditField()
    {
        $this->provider_phone_credit = null;
        $this->number_phone_credit = '';
        $this->amount_phone_credit = '';
        $this->stepPhoneCredit = 1;

        $this->emit('modal-hide-phoneCredit');
        $this->emit('modal-hide-phoneCredit-validated');
        $this->emit('menu-phoneCredit-active');
    }
    public function resetReloadCardField()
    {
        $this->reloadcard_description = '';
        $this->reloadcardStatus = '';
        $this->provider_reloadcard = null;
        $this->amount_reloadcard = '';
        $this->account_number_reload_card = '';
        $this->name_reloadcard = '';
        $this->stepReloadCard = 1;
        $this->emit('modal-hide-reloadCard-validated');
        $this->emit('menu-reloadCarte-active');
    }

    public function cancelReloadCardField()
    {
        $this->reloadcard_description = '';
        $this->reloadcardStatus = '';
        $this->provider_reloadcard = null;
        $this->amount_reloadcard = '';
        $this->account_number_reload_card = '';
        $this->name_reloadcard = '';
        $this->stepReloadCard = 1;
        $this->emit('modal-hide-reloadCard');
        $this->emit('modal-hide-reloadCard-validated');
        $this->emit('menu-reloadCarte-active');
    }

    public function resetVirementField()
    {
        $this->virementDescription = '';
        $this->virementId = '';
        $this->virementStatus = '';
        $this->provider_virement = null;
        $this->firstname_user = '';
        $this->lastname_user = '';
        $this->phone_user = '';
        $this->amount_virement = '';
        $this->account_number_user = '';
        $this->country_virement = null;
        $this->account_number_user_bank = '';
        $this->emit('modal-hide-virement');
    }

    public function resetVirementInterField()
    {
        $this->virement_interId = '';
        $this->virement_inter_Status = '';
        $this->provider_Inter_virement = '';
        $this->firstname_user_inter = '';
        $this->lastname_user_inter = '';
        $this->phone_user_inter = '';
        $this->amount_virement_inter = '';
        $this->account_number_user_bank_inter = '';
        $this->country_virement_inter = '';
        $this->account_number_user_inter = '';
        $this->emit('modal-hide-virement');
    }

    public function cancelVirementField()
    {
        $this->virementDescription = '';
        $this->virementId = '';
        $this->virementStatus = '';
        $this->provider_virement = null;
        $this->firstname_user = '';
        $this->lastname_user = '';
        $this->phone_user = '';
        $this->amount_virement = '';
        $this->account_number_user = '';
        $this->stepVirement = 1;
        $this->emit('modal-hide-virement');
        $this->emit('modal-hide-virement-validated');
    }

    public function resetTransactionField()
    {
        $this->transaction_description = '';
        $this->transaction_phone = '';
        $this->provider_transaction = null;
        $this->choose_type_transaction = null;
        $this->compte_number_robus = '';
        $this->currentStep = 1;
        // $this->currentStep = 2;
        $this->code_validated = '';
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_description_robus = '';
        $this->emit('modal-hide-transfers-validated');
        $this->emit('modal-hide-transfers');
    }

    public function cancelTransactionField()
    {
        $this->transaction_description = '';
        $this->transaction_phone = '';
        $this->provider_transaction = null;
        $this->choose_type_transaction = null;
        $this->compte_number_robus = '';
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->currentStep = 1;
        $this->code_validated = '';
        $this->transaction_description_robus = '';
        $this->emit('modal-hide-transfers-validated');
        $this->emit('modal-hide-transfers');
    }
    public function errorNumberValidated()
    {
        $this->transaction_description = '';
        $this->transaction_phone = '';
        $this->provider_transaction = null;
        $this->compte_number_robus = '';
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_description_robus = '';
        $this->emit('warning', 'Le numero doit corerespondre a la selection !');
    }

    public function errorNumberUserValidated()
    {
        $this->transaction_description = '';
        $this->transaction_phone = '';
        $this->provider_transaction = null;
        $this->compte_number_robus = '';
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_description_robus = '';
        $this->emit('warning', 'Le numero doit etre pour un client !');
    }

    public function errorNumberPhoneValidated()
    {
        $this->provider_phone_credit = null;
        $this->number_phone_credit = '';
        $this->amount_phone_credit = '';
        $this->emit('warning', 'Le numero doit corerespondre a la selection !');
    }

    public function errorWalletTransactionAccount()
    {
        $this->transaction_description = '';
        $this->transaction_phone = '';
        $this->provider_transaction = null;
        $this->compte_number_robus = '';
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_description_robus = '';
        $this->emit('warning', 'Ce  numero vous appartient \n !');
        $this->emit('modal-hide-transfers');
    }

    public function errorWalletPhoneCreditAccount()
    {
        $this->provider_phone_credit = null;
        $this->number_phone_credit = '';
        $this->amount_phone_credit = '';
        $this->emit('modal-hide-phoneCredit');
        $this->emit(
            'warning',
            'Ce  n\'existe pas dans cette base ou vous appartient \n ! ou n\'es pas nationnal'
        );
    }

    public function errorWalletTransactionWave()
    {
        $this->transaction_description = '';
        $this->transaction_phone = '';
        $this->provider_transaction = null;
        $this->compte_number_robus = '';
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_description_robus = '';
        $this->emit('modal-hide-transfers');
        $this->emit(
            'warning',
            'Le numero doit etres l\'un des 3 reseaux  ! ou n\'es pas nationnal'
        );
    }
    public function resetPaiementField()
    {

        $this->provider_payment = null;
        $this->facture_choix_type = null;
        $this->paiement_num_contrat = '';
        $this->paiement_id_invoice = '';
        $this->paiement_decoder_canal = '';
        $this->paiement_name_sodecie = '';
        $this->paiement_name_cie = '';
        $this->paiement_amount_Canal = '';
        $this->paiement_phone_canal = '';
        $this->paiement_amount_p_sodecie = '';
        $this->paiement_amount_t_sodecie = '';
        $this->paiement_amount_total = '';
        $this->paiementStatus = '';
        $this->paiementChoix_phone = null;
        $this->offer_paiement = null;
        $this->offer_paiement = null;
        $this->paiement_id_invoice_pre = '';
        $this->pre_phone_invoice = '';
        $this->pre_id_invoice = '';
        $this->stepPhoneCredit = 1;
        // $this->emit('modal-hide', 'hide');
        $this->emit('modal-hide-paiement-validated');
    }

    public function cancelPaiementField()
    {
        $this->provider_payment = null;
        $this->paiement_num_contrat = '';
        $this->paiement_id_invoice = '';
        $this->paiement_decoder_canal = '';
        $this->paiement_name_sodecie = '';
        $this->paiement_name_cie = '';
        $this->paiement_amount_Canal = '';
        $this->paiement_phone_canal = '';
        $this->paiement_amount_p_sodecie = '';
        $this->paiement_amount_t_sodecie = '';
        $this->paiement_amount_total = '';
        $this->paiement_amount_partiel = '';
        $this->paiementStatus = '';
        $this->facture_choix_type = null;
        $this->paiementChoix_phone = null;
        $this->pre_phone_invoice = '';
        $this->pre_id_invoice = '';
        $this->offer_paiement = '';
        $this->stepPaiment = 1;
        $this->emit('modal-hide-paiement');
        $this->emit('modal-hide-paiement-validated');
    }
    //    Paginated
    public function gotoPageTrans($page)
    {
        $this->setPage($page);
        $this->emit('menu-transaction-active');
    }

    public function nextPageTrans()
    {
        $this->setPage($this->page + 1);
        $this->emit('menu-transaction-active');
    }

    public function previousPageTrans()
    {
        $this->setPage(max($this->page - 1, 1));
        $this->emit('menu-transaction-active');
    }
    public function gotoPagePaie($page)
    {
        $this->setPage($page);
        $this->emit('menu-paiement-active');
    }

    public function nextPagePaie()
    {
        $this->setPage($this->page + 1);
        $this->emit('menu-paiement-active');
    }

    public function previousPagePaie()
    {
        $this->setPage(max($this->page - 1, 1));
        $this->emit('menu-paiement-active');
    }

    public function gotoPageVirement($page)
    {
        $this->setPage($page);
        $this->emit('menu-virement-active');
    }

    public function nextPageVirement()
    {
        $this->setPage($this->page + 1);
        $this->emit('menu-virement-active');
    }

    public function previousPageVirement()
    {
        $this->setPage(max($this->page - 1, 1));
        $this->emit('menu-virement-active');
    }

    public function gotoPageInter($page)
    {
        $this->setPage($page);
        $this->emit('menu-inters-active');
    }

    public function nextPageInter()
    {
        $this->setPage($this->page + 1);
        $this->emit('menu-inters-active');
    }

    public function previousPageInter()
    {
        $this->setPage(max($this->page - 1, 1));
        $this->emit('menu-inters-active');
    }

    public function previousPageReload()
    {
        $this->setPage(max($this->page - 1, 1));
        $this->emit('menu-reloadCarte-active');
    }

    public function gotoPageReload($page)
    {
        $this->setPage($page);
        $this->emit('menu-reloadCarte-active');
    }

    public function nextPageReload()
    {
        $this->setPage($this->page + 1);
        $this->emit('menu-reloadCarte-active');
    }

    public function previousPagePhone()
    {
        $this->setPage(max($this->page - 1, 1));
        $this->emit('menu-phoneCredit-active');
    }

    public function gotoPagePhone($page)
    {
        $this->setPage($page);
        $this->emit('menu-phoneCredit-active');
    }

    public function nextPagePhone()
    {
        $this->setPage($this->page + 1);
        $this->emit('menu-phoneCredit-active');
    }

    public function render()
    {

        $offers = Offer::whereStatus(true)
            ->latest()
            ->orderBy('name', 'asc')
            ->get();

        $transaction = WalletTransaction::whereIsWeb(false);
        $paiement = WalletPayment::whereIsWeb(false);
        $wallet = Wallet::query();
        $sid_search = SidUser::query();
        $virement_bank = WalletBank::whereIsWeb(false);
        $inter_search = WalletInter::whereIsWeb(false);
        $reload_card = WalletDeposit::whereIsWeb(false);
        $phone_credit = WalletPhoneCredit::whereIsWeb(false);
        $walletUser = Auth::guard('web')->user();
        $commission_reload_val_nat_true_pay = null;
        $commission_reload_val_nat_true_receive = null;
        $commission_paiement_invoice_val_nat_pay = null;
        $commission_paiement_invoice_val_nat_receive = null;
        $commission_paiement_invoice_canal_val_nat = null;
        $commission_paiement_invoice_canal_val_inter = null;
        $commission_virement_val_nat_pay  = null;
        $commission_virement_val_nat_receive  = null;
        $commission_phone_credit_val_inter_pay  = null;
        $commission_phone_credit_val_inter_receive  = null;
        $commission_phone_credit_val_nat_pay  = null;
        $commission_phone_credit_val_nat_receive  = null;
        $commission_transaction_val_nal_pay = null;
        $commission_transaction_val_nal_receive = null;
        $code_validated_val_nat = null;
        $amount_received_canal = null;


        if ($this->code_validated != '') {
            $transaction_code = WalletTransaction::whereCodeValidated(
                $this->code_validated
            )->first();

            if ($transaction_code != null) {
                $code_validated_val_nat = $transaction_code->code_validated ?? null;
            } else {
                $code_validated_val_nat = $transaction_code->code_validated ?? null;
            }
        }

        // True valuer of relaod card
        if ($this->amount_reloadcard != '') {


            $commission_reload = DepositCost::whereId(2)->first();

            if ($walletUser->wallet->sidUser->is_under_distributer == true) {
                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_reload->levelDepositIvories->where('is_ivoires', true)->last();

                $valeur_first = $commission_reload->levelDepositIvories->where('is_ivoires', true)->first();

                //  Conversion valeur
                $conversion_valeur_end  = $valeur_end->amount_end;

                $conversion_valeur_first  = $valeur_first->amount_begin;


                if ($conversion_valeur_first  > $this->amount_reloadcard) {
                    $commission_reload_val_nat_true_pay = "Montant min est  : " .   number_format($conversion_valeur_first, 2, ',', ' ');

                    $commission_reload_val_nat_true_receive = "Montant min est  : " .  number_format($conversion_valeur_first, 2, ',', ' ');
                } elseif ($conversion_valeur_end  < $this->amount_reloadcard) {

                    $commission_reload_val_nat_true_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');

                    $commission_reload_val_nat_true_receive = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');
                } else {

                    foreach ($commission_reload->levelDepositIvories->where('is_ivoires', true)  as $amount_comission) {

                        if ($this->amount_reloadcard >= $amount_comission->amount_begin &&  $this->amount_reloadcard <= $amount_comission->amount_end) {



                            if ($amount_comission->amount == null) {

                                $percent_admin =  $amount_comission->percent * $this->amount_reloadcard;

                                $admin_valeur = $percent_admin * $amount_comission->admin_percent;

                                // Commission valuer ROBUS
                                $commission_reload_val_nat_true_pay = $amount_comission->comission_distributer * $admin_valeur;


                                $valeur_receive = (float) $this->amount_reloadcard;

                                if ($valeur_receive < 0) {

                                    $commission_reload_val_nat_true_receive = 'Montant negatif';
                                } else {

                                    $commission_reload_val_nat_true_receive = (float) $this->amount_reloadcard;
                                }
                            } else {

                                $commission_reload_val_nat_true_pay = (float) $amount_comission->comission_distributer;

                                $valeur_receive = (float) $this->amount_reloadcard;

                                if ($valeur_receive < 0) {

                                    $commission_reload_val_nat_true_receive = 'Montant negatif';
                                } else {

                                    $commission_reload_val_nat_true_receive = (float) $this->amount_reloadcard;
                                }
                            }
                        }
                    }
                }
            } else {

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_reload->levelDepositIvories->where('is_inter_bank', true)->last();

                $valeur_first = $commission_reload->levelDepositIvories->where('is_inter_bank', true)->first();

                //  Conversion valeur
                $conversion_valeur_end  = $valeur_end->amount_end;

                $conversion_valeur_first  = $valeur_first->amount_begin;


                if ($conversion_valeur_first  > $this->amount_reloadcard) {
                    $commission_reload_val_nat_true_pay = "Montant min est  : " .   number_format($conversion_valeur_first, 2, ',', ' ');

                    $commission_reload_val_nat_true_receive = "Montant min est  : " .  number_format($conversion_valeur_first, 2, ',', ' ');
                } elseif ($conversion_valeur_end  < $this->amount_reloadcard) {

                    $commission_reload_val_nat_true_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');

                    $commission_reload_val_nat_true_receive = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');
                } else {

                    foreach ($commission_reload->levelDepositIvories->where('is_inter_bank', true)  as $amount_comission) {
                        if ($this->amount_reloadcard >= $amount_comission->amount_begin &&  $this->amount_reloadcard <= $amount_comission->amount_end) {

                            if ($amount_comission->amount == null) {

                                $commission_reload_val_nat_true_pay = (float) $amount_comission->percent *  $this->amount_reloadcard;

                                $valeur_receive = (float) $this->amount_reloadcard;

                                if ($valeur_receive < 0) {

                                    $commission_reload_val_nat_true_receive = 'Montant negatif';
                                } else {

                                    $commission_reload_val_nat_true_receive = (float) $this->amount_reloadcard;
                                }
                            } else {

                                $commission_reload_val_nat_true_pay = (float) $amount_comission->amount;

                                $valeur_receive = (float) $this->amount_reloadcard;

                                if ($valeur_receive < 0) {

                                    $commission_reload_val_nat_true_receive = 'Montant negatif';
                                } else {

                                    $commission_reload_val_nat_true_receive = (float) $this->amount_reloadcard;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($this->paiement_amount_total != '') {

            $commission_invoice = PaymentCost::whereId(2)->first();

            if ($walletUser->country->code == 'CI') {

                $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->comission_auther;

                $commission_paiement_invoice_val_nat_receive =  (float) $this->paiement_amount_total;
            }
        }


        if ($this->paiement_amount_t_sodecie != '') {

            $commission_invoice = PaymentCost::whereId(2)->first();

            if ($walletUser->wallet->sidUser->is_under_distributer == 1) {

                $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->comission_auther;

                $commission_paiement_invoice_val_nat_receive =  (float) $this->paiement_amount_t_sodecie;
            } else {

                $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->amount_auther;

                $commission_paiement_invoice_val_nat_receive =  (float) $this->paiement_amount_t_sodecie;
            }
        }

        // Invoice canal national

        if ($this->offer_paiement != '') {


            if (sabdy()->country->id == 107) {

                $commission_invoice = PaymentCost::whereId(2)->first();

                foreach ($offers  as $offer) {

                    $offer_amount = $offer->whereId($this->offer_paiement)->first();

                    if (sabdy()->is_under_distributer == true) {

                        $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;


                        $commission_paiement_invoice_canal_val_inter = number_format($commission_invoice->comission_canal, 2, ',', ' ');
                    } else {

                        $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                        $commission_paiement_invoice_canal_val_inter =  number_format($valeur_offer, 2, ',', ' ');
                    }

                    $amount_received_canal =  $valeur_offer;
                }
            }
        }

        // National virement
        if ($this->amount_virement != '') {

            $commission_virement = BankCost::whereId(2)->first();

            // Verify valeur amount max in Landing the ROBUS
            $valeur_end = $commission_virement->levelBankIvories->last();

            //  Conversion valeur
            $conversion_valeur_end  = $valeur_end->amount_end;

            if ($conversion_valeur_end  < $this->amount_virement) {
                $commission_virement_val_nat_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                $commission_virement_val_nat_receive = "Montant maxi est  : " .  $conversion_valeur_end;
            } else {

                foreach ($commission_virement->levelBankIvories as $amount_comission) {
                    if ($this->amount_virement >= $amount_comission->amount_begin && $this->amount_virement <= $amount_comission->amount_end) {
                        if ($walletUser->wallet->sidUser->is_under_distributer == 1) {

                            $commission_virement_val_nat_pay = $amount_comission->comission_distributer;

                            $valeur_receive = (float) $this->amount_virement;

                            if ($valeur_receive < 0) {

                                $commission_virement_val_nat_receive = "Montant negatif";
                            } else {

                                $commission_virement_val_nat_receive = (float) $this->amount_virement;
                            }
                        } else {

                            $commission_virement_val_nat_pay = $amount_comission->amount;

                            $valeur_receive = (float) $this->amount_virement;

                            if ($valeur_receive < 0) {

                                $commission_virement_val_nat_receive = "Montant negatif";
                            } else {

                                $commission_virement_val_nat_receive = (float) $this->amount_virement;
                            }
                        }
                    }
                }
            }
        }

        if ($this->amount_phone_credit != '') {

            $commission_phone_credit = PhoneCreditCost::whereId(1)->first();

            $commission_phone_credit_val_nat_pay = (float) $this->amount_phone_credit * (float) $commission_phone_credit->percent;

            $valeur_receive = (float) $this->amount_phone_credit;


            if ($valeur_receive < 0) {

                $commission_phone_credit_val_nat_receive = "Montant negatif";
            } else {

                $commission_phone_credit_val_nat_receive =  $valeur_receive;
            }
        }

        // Transactions
        $transactions = $transaction->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallet_transactions.status', 'PENDING')
            ->where('wallet_transactions.phone', '!=', null);

        if ($walletUser->is_under_distributer) {
            $transactions->where('wallet_transactions.wallet_id', $walletUser->wallet->id);
        }

        $transactions = $transactions->select('wallet_transactions.*')
            ->orderBy('wallet_transactions.created_at', 'desc')
            ->paginate(15);


        // Paiements
        $paiements = $paiement->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $walletUser->country->id)
            ->where('wallet_payments.status', 'PENDING');

        if ($walletUser->is_under_distributer) {
            $paiements->where('wallet_payments.wallet_id', $walletUser->wallet->id);
        }

        $paiements = $paiements->select('wallet_payments.*')
            ->orderBy('wallet_payments.created_at', 'desc')
            ->paginate(15);

        // Virements
        $virements = $virement_bank
            ->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $walletUser->country->id)
            ->where('wallet_banks.status', 'PENDING');

        if ($walletUser->is_under_distributer) {
            $virements->where('wallet_banks.wallet_id', $walletUser->wallet->id);
        }

        $virements = $virements->select('wallet_banks.*')
            ->orderBy('wallet_banks.created_at', 'desc')
            ->paginate(15);

        // Transactions Internationales
        $internationals = $inter_search
            ->join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.status', 'PENDING');

        if ($walletUser->is_under_distributer) {
            $internationals->where('wallet_inters.wallet_id', $walletUser->wallet->id);
        }

        $internationals = $internationals->select('wallet_inters.*')
            ->orderBy('wallet_inters.created_at', 'desc')
            ->paginate(15);


        // Reload Cards
        $reloadCards = $reload_card
            ->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $walletUser->country->id)
            ->where('wallet_deposits.status', 'PENDING');

        if ($walletUser->is_under_distributer) {
            $reloadCards->where('wallet_deposits.wallet_id', $walletUser->wallet->id);
        }

        $reloadCards = $reloadCards->select('wallet_deposits.*')
            ->orderBy('wallet_deposits.created_at', 'desc')
            ->paginate(15);


        $phoneCredits = $phone_credit
            ->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $walletUser->country->id)
            ->where('wallet_phone_credits.status', 'PENDING');

        if ($walletUser->is_under_distributer) {
            $phoneCredits->where('wallet_phone_credits.wallet_id', $walletUser->wallet->id);
        }

        $phoneCredits = $phoneCredits->select('wallet_phone_credits.*')
            ->orderBy('wallet_phone_credits.created_at', 'desc')
            ->paginate(15);

        return view('livewire.transfers', [
            'walletSolde' => Wallet::where(
                'sid_user_id',
                $walletUser->id
            )->sum('amount'),
            'transactions' => $transactions,
            'paiements' => $paiements,
            'virements' => $virements,
            'Internationals' => $internationals,
            'reloadCards' => $reloadCards,
            'phoneCredits' => $phoneCredits,
            'countrie_number_local' => Auth::guard('web')->user()->country
                ->indecative,
            'wallet_search' => $wallet
                ->where(
                    'wallet_id',
                    'like',
                    '%' . $this->compte_number_robus . '%'
                )
                ->first(),
            'number_search' => $sid_search
                ->where(
                    'phone',
                    'like',
                    '%' . $this->transaction_phone . '%'
                )
                ->where('country_id', $walletUser->country->id)
                ->where('is_custumers', true)
                ->get(),
            'amount_percent' => TransactionCost::where(
                'status',
                true
            )->first(),
            'amount_invoice' => PaymentCost::where('status', true)->first(),
            'amount_bank' => BankCost::whereId(2)->first(),
            'amount_deposit_nation_pay' => $commission_reload_val_nat_true_pay,
            'amount_deposit_nation_receive' => $commission_reload_val_nat_true_receive,
            'amount_deposit_nation_true_pay' => $commission_reload_val_nat_true_pay,
            'amount_deposit_nation_true_receive' => $commission_reload_val_nat_true_receive,
            'amount_paiement_invoice_auther_pay' => $commission_paiement_invoice_val_nat_pay,
            'amount_paiement_invoice_auther_receive' => $commission_paiement_invoice_val_nat_receive,
            'amount_paiement_invoice_canal' => $commission_paiement_invoice_canal_val_nat,
            'amount_paiement_invoice_canal_inter' => $commission_paiement_invoice_canal_val_inter,
            'amount_virement_nation_pay' => $commission_virement_val_nat_pay,
            'amount_virement_nation_receive' => $commission_virement_val_nat_receive,
            'amount_phone_credit_val_pay' => $commission_phone_credit_val_inter_pay,
            'amount_phone_credit_val_receive' => $commission_phone_credit_val_inter_receive,
            'amount_transaction_nat_val_receive' => $commission_transaction_val_nal_receive,
            'amount_transaction_nat_val_pay' => $commission_transaction_val_nal_pay,
            'amount_phone_credit_val_nat_pay' => $commission_phone_credit_val_nat_pay,
            'amount_phone_credit_val_nat_receive' => $commission_phone_credit_val_nat_receive,
            'amount_received_canal' => $amount_received_canal,
            'code_valided' => $code_validated_val_nat,
            'code_valided_inter' => $code_validated_val_nat,
            'withdrawal_robus' => Provider::whereStatus(true)
                ->whereId(9)
                ->get(),
            'withdrawal_robus_inter' => Provider::whereStatus(true)
                ->whereId(51)
                ->get(),
            'payment_error' => $this->PaymentError,
            'payment_success' => $this->PaymentSuccess,
            'payment_pendding' => $this->PaymentPending,
            'transaction_error' => $this->TransactionError,
            'transaction_success' => $this->TransactionSuccess,
            'transaction_pendding' => $this->TransactionPendding,
            'WalletReloads' => $this->WalletReloads,
            'reload_error' => $this->ReloadError,
            'reload_success' => $this->ReloadSuccess,
            'reload_pendding' => $this->ReloadPendding,
            'bank_error' => $this->VirementError,
            'bank_success' => $this->VirementSuccess,
            'bank_pendding' => $this->VirementPendding,
            'inter_error' => $this->InterError,
            'inter_success' => $this->InterSuccess,
            'inter_pendding' => $this->InterPendding,
            'card_error' => $this->DepositError,
            'card_success' => $this->DepositSuccess,
            'card_pendding' => $this->DepositPendding,
            'phone_error' => $this->PhoneCreditError,
            'phone_success' => $this->PhoneCreditSuccess,
            'phone_pendding' => $this->PhoneCreditPendding,
            'WalletBanks' => $this->WalletBanks,
            'WalletDeposits' => $this->WalletDeposits,
            'WalletInters' => $this->WalletInters,
            'WalletPayments' => $this->WalletPayments,
            'WalletTransactions' => $this->WalletTransactions,
            'WalletPhoneCredits' => $this->WalletPhoneCredits,
            'offers_canal' => $offers,
            'countries' => Country::whereRobus(true)
                ->orderBy('name', 'asc')
                ->get(),
            'countrie_inter_cf' => Country::whereRobus(true)->whereId(107)
                ->first(),
            'wallet_countries_users' => SidUser::where('status', true)
                ->orderBy('firstname', 'asc')
                ->get(),
            'providers' => $this->NationTransaction,
            'provider_paiements' => $this->PaiementTransaction,
            'provider_virements' => $this->VirementTransaction,
            'provider_reloads' => $this->ReloadTransaction,
            'provider_credits' => $this->PhoneCreditTransaction,
            'usersCountry' => Auth::guard('web')->user()->wallet->currencie->code,
            'user_auth' => $walletUser,
            'user_send' => Auth::guard('web')->user(),
        ]);
    }

 
    protected function createTransaction($check, $transactionCode, $transactionCompiCode)
    {
        return Transaction::create([
            'wallet_transaction_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' =>  $check->costs_amount,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    protected function createInter($check, $transactionCode, $transactionCompiCode)
    {
        return TransactionInter::create([
            'wallet_inter_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name .
                '_' . $transactionCode,
            'operator_transaction_name' =>
            $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    protected function createPhoneCredit($check, $transactionCode, $transactionCompiCode)
    {
        return PaymentPhoneCredit::create([
            'wallet_phone_credit_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    protected function createDeposit($check, $transactionCode, $transactionCompiCode)
    {
        return  Deposit::create([

            'wallet_deposit_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' =>  $check->costs_amount,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }


    protected function createPayment($check, $transactionCode, $transactionCompiCode)
    {
        return Payment::create([
            'wallet_payment_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode ??  null,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount ?? null,
            'fee_amount' =>  $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }



    protected function createVirement($check, $transactionCode, $transactionCompiCode)
    {
        return PaymentBank::create([
            'wallet_bank_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount,
            'received_amount' =>  $check->amount,
            'status' => $check->status,
        ]);
    }


    protected function createNewVirement(
        $walletUser,
        $amount_virement,
        $virementDescription,
        $provider,
        $account_number_user_bank,
        $amount_user,
        $firstname_user,
        $lastname_user,
        $phone_user,
        $amount_solde,
        $country_virement,
        $amountReceved
    ) {
        return WalletBank::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $amount_virement,
            'description' => $virementDescription,
            'bank_cost_id' => 2,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id' => $provider->id,
            'number_compte' => $account_number_user_bank,
            'costs_amount' => $amount_user->amount,
            'firstname' => $firstname_user,
            'lastname' => $lastname_user,
            'phone' => $phone_user,
            'status' => 'PENDING',
            'amount_solde' => $amount_solde ?? null,
            'country_id' => $country_virement->id ?? null,
            'amount_received' => $amountReceved ?? null,
        ]);
    }

    protected function updateamountVirementReceveid($admin_civ, $amount_user, $walletBank, $walletUser, $amount_valeur)
    {


        if (sabdy()->is_under_distributer) {

            $valuer_deposit =  $amount_user->comission_distributer;

            $walletBank->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);
        } else {

            $valuer_deposit = $amount_user->amount;
        }


        $amount_commission = $amount_valeur;

        $this->updatePaiementWallet($walletUser, $amount_commission);


        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();
            $valeur_single = $valuer_deposit / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' => $valeur_single,
                    'sid_user_id' => $admin_single->id,
                    'wallet_transaction_id' => null,
                    'wallet_bank_id' => $walletBank->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  sabdy()->is_under_distributer ? $valuer_deposit : 0,
                    'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);
            }
        }

        return true;
    }

    protected function createNewPhoneCredit(
        $walletUser,
        $amount_phone_credit,
        $provider,
        $percent_robus,
        $number_phone_credit,
        $amount_solde,
        $amountReceved
    ) {
        return WalletPhoneCredit::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $amount_phone_credit,
            'description' => 'Demande de credit',
            'phone_credit_cost_id' => 1,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id' => $provider->id,
            'costs_amount' => $percent_robus->percent  *  $amount_phone_credit,
            'phone' => Auth::guard('web')->user()->country->indecative .  $number_phone_credit,
            'status' => 'PENDING',
            'amount_solde'  => $amount_solde ?? null,
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }

    public function updateamountPhoneCreditReceveid($admin_civ, $percent_robus, $walletPhone,  $walletUser, $amount)
    {

        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = sabdy()->wallet->amount - $amount_commission;

        $walletPhone->update([
            'amount_solde' => $amount_solde,
        ]);


        $this->updatePaiementWallet($walletUser, $amount_commission);


        $admin_valeur =  $amount * $percent_robus->percent;

        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_phone_credit_id' => $walletPhone->id,
                    'description' => 'Commission administrateur',
                ]);
            }
        }

        return true;
    }

    protected function createDrawalTransaction(
        $walletUser,
        $transaction_amount_robus,
        $transaction_description_robus,
        $transaction_phone,
        $compte_number_robus,
        $provider,
        $commission_send,
        $choose_type_transaction,
        $amount_solde,
        $amount_drawal
    ) {
        return WalletTransaction::create(
            [
                'wallet_id' => $walletUser->wallet->id,
                'amount' => $transaction_amount_robus,
                'description' => $transaction_description_robus ?? null,
                'transaction_cost_id' => 26,
                'phone' => $transaction_phone ??  null,
                'num_wallet' =>  $compte_number_robus,
                'created_by' => Auth::guard('web')->user()->id,
                'provider_id' => $provider->id,
                'code_validated' => null,
                'amount_received'  => $commission_send,
                'choose_type_transaction' => $choose_type_transaction,
                'type_transaction' => 'Compense',
                'type_received' => 'Transfert',
                'status' => 'SUCCESS',
                'amount_solde' => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_drawal ?? null
            ]
        );
    }


    protected function createNewPaiement(
        $walletUser,
        $paiement_amount_total,
        $paiement_decoder_canal,
        $paiement_id_invoice_pre,
        $paiement_id_invoice,
        $paiement_num_contrat,
        $pre_phone_invoice,
        $paiement_name_cie,
        $provider,
        $paiement_phone_canal,
        $offer,
        $percent_robus,
        $paiementChoix_phone,
        $paiementChoix_type,
        $facture_choix_type,
        $amount_solde,
    ) {
        return WalletPayment::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $paiement_amount_total,
            'description' => 'Paiement de facture',
            'payment_cost_id' => 2,
            'number_decoder' => $paiement_decoder_canal ?? null,
            'id_invoice' => $paiement_id_invoice_pre == null ? $paiement_id_invoice : $paiement_id_invoice_pre,
            'number_contract' =>  $paiement_num_contrat ?? null,
            'phone' => $pre_phone_invoice ?? null,
            'id_invoice_pre' =>  $pre_id_invoice ?? null,
            'name' => $paiement_name_cie ?? null,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id' => $provider->id,
            'number_phone' => $paiement_phone_canal ?? null,
            'offer_id' => $offer->id ?? null,
            'costs_amount' => $percent_robus->amount_auther,
            'status' => 'PENDING',
            'paiementChoix_phone' =>  $paiementChoix_phone ?? null,
            'paiementChoix_type' => $paiementChoix_type ?? null,
            'factureChoix_type' => $facture_choix_type ?? null,
            'amount_solde'  =>   $amount_solde ?? null
        ]);
    }

    protected function createNewPaiementSodeci(
        $walletUser,
        $paiement_amount_t_sodecie,
        $paiement_amount_p_sodecie,
        $paiement_decoder_canal,
        $paiement_id_invoice,
        $paiement_num_contrat,
        $pre_phone_invoice,
        $pre_id_invoice,
        $paiement_name_sodecie,
        $provider,
        $percent_robus,
        $paiement_phone_canal,
        $offer,
        $paiementChoix_phone,
        $paiementChoix_type,
        $facture_choix_type,
        $amount_solde,
    ) {
        return WalletPayment::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $paiement_amount_t_sodecie == null ? $paiement_amount_p_sodecie : $paiement_amount_t_sodecie,
            'description' => 'Paiement de facture SODECI',
            'payment_cost_id' => 2,
            'number_decoder' => $paiement_decoder_canal ?? null,
            'id_invoice' => $paiement_id_invoice ?? null,
            'number_contract' => $paiement_num_contrat ?? null,
            'phone' => $pre_phone_invoice ?? null,
            'id_invoice_pre' => $pre_id_invoice ?? null,
            'name' => $paiement_name_sodecie ?? null,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id' => $provider->id,
            'costs_amount' => $percent_robus->amount_auther,
            'number_phone' => $paiement_phone_canal ?? null,
            'offer_id' => $offer->id ?? null,
            'status' => 'PENDING',
            'paiementChoix_phone' => $paiementChoix_phone ?? null,
            'paiementChoix_type' =>  $paiementChoix_type ?? null,
            'factureChoix_type' => $facture_choix_type ?? null,
            'amount_solde'  =>   $amount_solde  ?? null
        ]);
    }

    protected function createNewPaiementCanal(
        $walletUser,
        $offer,
        $paiement_decoder_canal,
        $paiement_id_invoice,
        $paiement_num_contrat,
        $pre_phone_invoice,
        $pre_id_invoice,
        $paiement_name_sodecie,
        $provider,
        $percent_robus,
        $paiement_phone_canal,
        $paiementChoix_phone,
        $paiementChoix_type,
        $facture_choix_type,
        $amount_solde,
    ) {
        return WalletPayment::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $offer->amount ?? null,
            'description' => 'Paiement de facture CANAL',
            'payment_cost_id' => 2,
            'number_decoder' => $paiement_decoder_canal ?? null,
            'id_invoice' => $paiement_id_invoice ?? null,
            'number_contract' =>  $paiement_num_contrat ?? null,
            'phone' => $pre_phone_invoice ?? null,
            'id_invoice_pre' => $pre_id_invoice ?? null,
            'name' => $paiement_name_sodecie ?? null,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id' => $provider->id,
            'costs_amount' => $percent_robus->amount_canal,
            'number_phone' => $paiement_phone_canal ?? null,
            'offer_id' => $offer->id ?? null,
            'status' => 'PENDING',
            'paiementChoix_phone' => $paiementChoix_phone ?? null,
            'paiementChoix_type' =>  $paiementChoix_type ?? null,
            'factureChoix_type' => $facture_choix_type ?? null,
            'amount_solde'  =>   $amount_solde ?? null
        ]);
    }

    protected function createNewReloadCard(
        $amount_solde,
        $walletUser,
        $amount_reloadcard,
        $reloadcard_description,
        $account_number_reload_card,
        $name_reloadcard,
        $amount_user,
        $provider,
        $amountReceved,
    ) {
        return WalletDeposit::create(
            [
                'amount_solde'  => $amount_solde ?? null,
                'wallet_id' => $walletUser->wallet->id,
                'amount' => $amount_reloadcard,
                'description' => $reloadcard_description,
                'num_carte' =>  $account_number_reload_card,
                'name' => $name_reloadcard,
                'deposit_cost_id' => 2,
                'costs_amount' =>  $amount_user,
                'created_by' => Auth::guard('web')->user()->id,
                'provider_id' => $provider->id,
                'amount_received' => $amountReceved,
                'status' => 'PENDING',
            ]
        );
    }

    protected function updateamountReloadCardReceveid($admin_civ, $percent_merchands, $walletOrderCard, $amount_commission, $walletUser, $amount_verify)
    {


        if (sabdy()->is_under_distributer) {

            $valuer_deposit =  $percent_merchands;

            $walletOrderCard->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);
        }

        $admin_valeur =  $amount_commission;

        $this->updatePaiementWallet($walletUser, $amount_verify);

        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();
            $valeur_single = $admin_valeur / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);


                AdminsCom::create([
                    'amount' => $valeur_single,
                    'sid_user_id' => $admin_single->id,
                    'wallet_deposit_id' =>  $walletOrderCard->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  sabdy()->is_under_distributer ? $valuer_deposit : 0,
                    'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);
            }
        }

        return true;
    }

    protected function createErrorPayment($check, $transactionCode, $transactionCompiCode)
    {
        return Payment::create([
            'wallet_payment_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' =>
            'RB_' .
                $check->provider->name .
                '_' .
                $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $check->amount + $check->costs_amount,
            'status' => $check->status,
        ]);
    }

    protected function createErrorDeposit($check, $transactionCode, $transactionCompiCode)
    {
        return  Deposit::create([
            'wallet_deposit_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name .
                '_' .
                $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $check->amount + $check->costs_amount,
            'status' => $check->status,
        ]);
    }

    protected function createErrorTransaction($check, $transactionCode, $transactionCompiCode)
    {
        return  Transaction::create([
            'wallet_transaction_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $check->amount + $check->costs_amount,
            'status' => $check->status,
        ]);
    }

    protected function createErrorPhoneCredit($check, $transactionCode, $transactionCompiCode)
    {
        return  PaymentPhoneCredit::create([
            'wallet_phone_credit_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $check->amount + $check->costs_amount,
            'status' => $check->status,
        ]);
    }

    protected function createErrorVirement($check, $transactionCode, $transactionCompiCode, $commission)
    {
        return    PaymentBank::create([
            'wallet_bank_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $commission,
            'status' => $check->status,
        ]);
    }


    protected function createErrorInter($check, $transactionCode, $transactionCompiCode)
    {
        return    TransactionInter::create([
            'wallet_inter_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name .
                '_' . $transactionCode,
            'operator_transaction_name' =>
            $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    // End update Transactions


    // Begin CREATED invoices transactions 

    protected function createInvoiceTransaction($check, $transaction)
    {
        return  InvoiceTransaction::create([
            'title' => "Transfers d'argent",
            'wallet_transaction_id' => $check->id,
            'transactions_id' => $transaction->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }



    protected function createNewInvoiceTransaction($walletTransaction, $transactionCompiCode, $transactionCode)
    {
        return Transaction::create([
            'wallet_transaction_id' => $walletTransaction->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $walletTransaction->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $walletTransaction->amount,
            'fee_amount' => 0,
            'received_amount' => $walletTransaction->amount,
            'status' => $walletTransaction->status,
        ]);
    }

    protected function createInvoiceTransactionDrawal($transaction, $walletTransaction)
    {
        return   InvoiceTransaction::create([
            'title' => "Transfers d'argent",
            'wallet_transaction_id' => $walletTransaction->id,
            'transactions_id' => $transaction->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $walletTransaction->description,
            'status' => $walletTransaction->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    protected function createInvoiceInter($check, $transaction_inter)
    {
        return  InvoiceInter::create([
            'title' => "Transfers d'argent",
            // 'transaction_inter_id' => $check->id,
            'transaction_inter_id' => $transaction_inter->id,
            // 'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    protected function createInvoicePhoneCredit($check, $paymentPhoneCredit)
    {
        return  InvoicePhoneCredit::create([
            'title' => 'Achat de credit telephonique',
            'wallet_phone_credit_id' => $check->id,
            'payment_credit_id' => $paymentPhoneCredit->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description ?? null,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    protected function createInvoiceReloadCard($check, $depositWallet)
    {
        return   InvoiceDeposit::create([
            'title' => 'Rechargement de carte',
            'wallet_deposit_id' => $check->id,
            'deposit_id' => $depositWallet->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    protected function createInvoicePayment($check, $payment)
    {
        return  InvoicePayment::create([
            'title' => 'Paiement de facture ',
            'wallet_payment_id' => $check->id,
            'payment_id' => $payment->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    protected function createInvoiceVirement($check, $paymentBank)
    {
        return   InvoiceBank::create([
            'title' => 'Virement bancaire',
            'wallet_bank_id' => $check->id,
            'payment_bank_id' => $paymentBank->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    // End Invoice Transactions


    // Begin Update amount Wallet

    protected function updateUserWallet($commission_transaction)
    {
        return Auth::guard('web')->user()->wallet->update([
            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
            'sid_user_relod' => Auth::guard('web')->user()->id,
            'amount' => DB::raw('amount+' . $commission_transaction),
        ]);
    }

    protected function updateReloadWallet($check, $commission)
    {

        return $check->wallet->update([
            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
            'sid_user_relod' => Auth::guard('web')->user()->id,
            'amount' => DB::raw('amount+' . $commission),
        ]);
    }

    protected function updatePaiementWallet($walletUser, $transaction_amount_robus)
    {

        return $walletUser->wallet->update([
            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
            'sid_user_relod' => Auth::guard('web')->user()->id,
            'amount' => DB::raw('amount-' . $transaction_amount_robus),
        ]);
    }

    // Paiements USERS
    protected function updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $amount)
    {


        $valuer_deposit = 0;

        if (sabdy()->is_under_distributer) {

            $valuer_deposit =  $percent_robus->comission_auther;

            $admin_valeur =  $percent_robus->amount_auther -  $percent_robus->comission_auther;

            $walletPayment->update([
                'commissions' => $valuer_deposit,
            ]);
        } else {

            $admin_valeur =  $percent_robus->amount_auther;
        }

        $this->updatePaiementWallet($walletUser, $amount);

        if ($admin_civ->count() > 0) {
            $this->distributeAdminCommissions($admin_civ, $admin_valeur, $walletPayment, $valuer_deposit);
        }
        return true;
    }

    protected function updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $amount)
    {


        $valuer_deposit = 0;

        if (sabdy()->is_under_distributer) {

            $valuer_deposit =  $percent_robus->comission_canal;

            $admin_valeur =  $percent_robus->amount_canal -  $percent_robus->comission_canal;

            $walletPayment->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);
        } else {

            $admin_valeur =  $percent_robus->amount_canal;
        }

        $this->updatePaiementWallet($walletUser, $amount);

        if ($admin_civ->count() > 0) {
            $this->distributeAdminCommissions($admin_civ, $admin_valeur, $walletPayment, $valuer_deposit);
        }
        return true;
    }

    private function distributeAdminCommissions($admin_civ, $admin_valeur, $walletPayment, $valeur_deposit)
    {
        $count_admin = $admin_civ->count();
        $valeur_single = $admin_valeur / $count_admin;

        foreach ($admin_civ as $user) {
            $admin_single = SidUser::find($user->id);
            AdminsCom::create([
                'amount' => $valeur_single,
                'sid_user_id' => $admin_single->id,
                'wallet_payment_id' => $walletPayment->id,
                'description' => 'Commission administrateur',
                'amount_under' => sabdy()->is_under_distributer ? $valeur_deposit : 0,
                'description_underdistributer' => sabdy()->is_under_distributer ? 'Paiement commission sous-distributeur' : null,
            ]);
        }
    }

    protected function updatePaiementDrawalWallet($walletUser, $commission)
    {

        return $walletUser->wallet->update([
            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
            'sid_user_relod' => Auth::guard('web')->user()->id,
            'amount' => DB::raw('amount+' . $commission),
        ]);
    }

    protected function updatePaiementWithdrawalWallet($wallet_search, $commission_send)
    {

        return $wallet_search->update([
            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
            'sid_user_relod' => Auth::guard('web')->user()->id,
            'amount' => DB::raw('amount-' . $commission_send),
        ]);
    }

    protected function updateVerifyCodeWallet($verify_code)
    {

        return  $verify_code->update([
            'type_transaction' => 'Retrait',
            'status' => 'SUCCESS',
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    protected function updateWalletNull($check, $commission)
    {

        $wallet_received =  $check->amount_solde;

        $amount_solde = (float)$wallet_received  +   (float)$commission;

        return  $check->update([
            'commissions' => 0,
            'commissions_admin' => 0,
            'amount_solde' => $amount_solde,
        ]);
    }

    protected function updateWalletCommissions($verify_code, $valuer_with_drawal)
    {

        return  $verify_code->update([
            'commissions' => DB::raw('commissions+' . $valuer_with_drawal),
        ]);
    }





    // End Update amount Wallet

    // Update Amount admins

    protected function updateAdminCom($check, $commission_transaction)
    {
        $check->adminCom->update([
            'amount_distributer' => $commission_transaction,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'description_distributer' => 'Validation distributeur',
        ]);
    }
    // End amount admins


    // Begin Nofitication CIV

    protected function shouldNotifyCI($check)
    {
        return $check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1;
    }

    protected function shouldNotifyCreateCI($walletUser)
    {
        return $walletUser->country->code == 'CI' && $walletUser->is_custumers == 1;
    }


    protected function sendSuccessNotification($check)
    {
        $message = 'Vous demande de transaction de ' . $check->amount . $check->wallet->currencie->code . ' par ' . $check->provider->name . ' le ' . $check->updated_at . ' a été acceptée avec succès. Robus vous remercie';
        $encode = substr($check->wallet->sidUser->phone, -10);

        http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
    }

    protected function sendSuccessMessageNotification($check, $message)
    {
        $encode = substr($check->wallet->sidUser->phone, -10);
        http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
    }

    protected function sendSuccessMessageCreateNotification($walletUser, $message)
    {
        $encode = substr($walletUser->phone, -10);
        http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
    }


    protected function sendErrorNotification($check)
    {
        $message = 'Vous demande de transaction de ' . $check->amount . $check->wallet->currencie->code . ' par ' . $check->provider->name . ' le ' . $check->updated_at . ' a été refusée . Robus vous remercie';
        $encode = substr($check->wallet->sidUser->phone, -10);

        http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
    }

    protected function sendRefundNotification($check)
    {
        $message = 'Vous demande de transaction de ' . $check->amount . $check->wallet->currencie->code . ' par ' . $check->provider->name . ' le ' . $check->updated_at . ' a été remboursée . Robus vous remercie';
        $encode = substr($check->wallet->sidUser->phone, -10);

        http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
    }

    protected function notifyUsers($check, $admins, $msg_title, $msg, $admin_msg)
    {
        $check->wallet->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));

        Notification::send($admins, new AdminNotification($admin_msg));
    }


    protected function notifyCreateUsers($walletUser, $admins, $msg_title, $msg, $admin_msg)
    {
        $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));

        Notification::send($admins, new AdminNotification($admin_msg));
    }

    protected function notifyAdmins($admins)
    {
        foreach ($admins as $user) {
            $admin_single = SidUser::find($user->id);
            $notifs = $admin_single->unreadNotifications;
            event(new AdminNotifEvent($notifs));
        }
    }

    protected function sendMailNotification($check)
    {
        $notify = new MailController;
        $notify->newTransaction($check);
    }



    protected function sendMailInterNotification($check)
    {
        $notify = new MailController;
        $notify->newPaiementInter($check);
    }

    protected function sendMailPhoneCreditNotification($check)
    {
        $notify = new MailController;
        $notify->newPhoneCredit($check);
    }

    protected function sendMailReloadCardNotification($check)
    {
        $notify = new MailController;
        $notify->newReload($check);
    }

    protected function sendMailPaiementNotification($check)
    {
        $notify = new MailController;
        $notify->newPaiement($check);
    }


    protected function sendMailVersementNotification($check)
    {
        $notify = new MailController;
        $notify->newVirement($check);
    }

    // ENd Nofification


    // Update Commissions
    protected function handleAdminCommission($check, $admin_civ)
    {
        $valeur_amount_com = $check->adminCom->amount;

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' => $valeur_amount_com,
                    'sid_user_id' => $admin_single->id,
                    'description' => 'Commission administrateur',
                    'status' => "SUCCESS",
                ]);
                $admin_single->wallet->update([
                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                    'sid_user_relod' => Auth::guard('web')->user()->id,
                    'amount' => DB::raw('amount+' . $valeur_amount_com),
                ]);
            }
        }
    }

    protected function handleReloadCommission($check, $admin_civ)
    {
        $valeur_amount_com = $check->adminCom->amount;

        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $valeur_amount_com / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'status'  => "ERROR",
                    'description' => 'Remboursement des commissions sous distributeur',
                ]);
            }
        }
    }

    protected function isSpecialProvider($providerId)
    {
        $specialProviders = [
            61,
            62,
            63,
            64,
            65,
            59,
            60,
            48,
            49,
            50,
            55,
            71,
            72,
            73,
            74,
            75,
            76,
            77,
            78,
            93,
            94,
            95,
            96,
            97,
            98,
            99,
            100,
            101,
            102,
        ];

        return in_array($providerId, $specialProviders);
    }

    protected function handleReloadClientCommission($check, $admin_civ)
    {

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   0,
                    'status'  => "ERROR",
                    'sid_user_id' =>   $admin_single->id,
                    'description' => 'Remboursement de la commission client',
                ]);
            }
        }
    }

    protected function handleUnderDistributorCommission($check, $admin_civ)
    {
        $valeur_amount_com = $check->adminCom->amount;


        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   $valeur_amount_com,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => 'Paiement commission sous-distributer',
                    'status'  => "SUCCESS",
                    'amount_under' =>  $valeur_amount_com,
                    'description_underdistributer' =>  'Paiement commission sous-distributer'
                ]);

                $admin_single->wallet->update([
                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                    'sid_user_relod' => Auth::guard('web')->user()->id,
                    'amount' => DB::raw('amount-' . $valeur_amount_com),
                ]);
            }
        }
    }


    protected function handleUnderDistributorPaiementCommission($check, $admin_civ)
    {
        $valeur_amount_com = $check->adminCom->amount;

        $under_amount_com = $check->adminCom->amount_under;

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   $valeur_amount_com,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => 'Commission administrateur',
                    'status'  => "SUCCESS",
                    'amount_under' =>  $under_amount_com,
                    'description_underdistributer' =>  'Paiement commission sous-distributer'
                ]);

                // Update amount admin
                $admin_single->wallet->update([
                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                    'sid_user_relod' => Auth::guard('web')->user()->id,
                    'amount' => DB::raw('amount+' . $valeur_amount_com),
                ]);
            }
        }
    }

    protected function getTransactionData(
        $admin_single,
        $amount_single,
        $walletBank,
        $walletReloadCard,
        $walletInter,
        $walletPayment,
        $walletPhoneCredit,
        $walletReload,
        $walletTransaction,
        $walletBankCard
    ) {

        // Define the transaction data structure
        $transaction_data = [
            'amount' => $amount_single,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => null,
            'wallet_bank_id' => null,
            'wallet_deposit_id' => null,
            'wallet_inter_id' => null,
            'wallet_payment_id' => null,
            'wallet_phone_credit_id' => null,
            'wallet_reload_id' => null,
            'bank_card_id' => null,
            'description' => 'Commission',
        ];

        // Determine which ID to use based on available data
        if ($walletBank) {
            $transaction_data['wallet_bank_id'] = $walletBank->id;
        } elseif ($walletReloadCard) {
            $transaction_data['wallet_deposit_id'] = $walletReloadCard->id;
        } elseif ($walletInter) {
            $transaction_data['wallet_inter_id'] = $walletInter->id;
        } elseif ($walletPayment) {
            $transaction_data['wallet_payment_id'] = $walletPayment->id;
        } elseif ($walletPhoneCredit) {
            $transaction_data['wallet_phone_credit_id'] = $walletPhoneCredit->id;
        } elseif ($walletReload) {
            $transaction_data['wallet_reload_id'] = $walletReload->id;
        } elseif ($walletTransaction) {
            $transaction_data['wallet_transaction_id'] = $walletTransaction->id;
        } elseif ($walletBankCard) {
            $transaction_data['bank_card_id'] = $walletBankCard->id;
        }

        return $transaction_data;
    }

    protected function TransactionDistributorCommission(
        $admin_percent,
        $admin_civ,
        $walletBank,
        $walletReloadCard,
        $walletInter,
        $walletPayment,
        $walletPhoneCredit,
        $walletReload,
        $walletTransaction,
        $walletBankCard
    ) {

        // Commission distributer
        $valuer_deposit =  $admin_percent;

        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $valuer_deposit / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                // Get transaction data based on available IDs
                $transaction_data = $this->getTransactionData(
                    $admin_single,
                    $valeur_single,
                    $walletBank,
                    $walletReloadCard,
                    $walletInter,
                    $walletPayment,
                    $walletPhoneCredit,
                    $walletReload,
                    $walletTransaction,
                    $walletBankCard
                );

                AdminsCom::create($transaction_data);

                $admin_single->wallet->update([
                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                    'sid_user_relod' => Auth::guard('web')->user()->id,
                    'amount' => DB::raw('amount+' . $valeur_single),
                ]);
            }
        }
    }

    protected function TransactionDistributorDrawal(
        $valuer_with_drawal,
        $admin_civ,
        $walletTransaction
    ) {

        // Commission distributer
        $valuer_deposit =  $valuer_with_drawal;

        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $valuer_deposit / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_transaction_id' => $walletTransaction->id,
                    'wallet_bank_id' => null,
                    'wallet_deposit_id' => null,
                    'wallet_inter_id' =>  null,
                    'wallet_payment_id' => null,
                    'wallet_phone_credit_id' => null,
                    'wallet_reload_id' => null,
                    'bank_card_id' => null,
                    'description' => 'Retrait d\'argent',
                ]);

                $admin_single->wallet->update([
                    'reload_by' =>  Auth::guard('web')->user()->firstname .
                        ' ' .
                        Auth::guard('web')->user()->lastname,
                    'sid_user_relod' =>  Auth::guard('web')->user()->id,
                    'amount' => DB::raw('amount-' . $valeur_single),
                ]);
            }
        }
    }


    protected function handleUnderDistributorInterCommission($check, $admin_civ)
    {
        $valeur_amount_com = $check->adminCom->amount;

        $under_amount_com = $check->adminCom->amount_under;

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   $valeur_amount_com,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => 'Paiement commission sous-distributer',
                    'status'  => "SUCCESS",
                    'amount_under' =>  $valeur_amount_com,
                    'description_underdistributer' =>  'Paiement commission sous-distributer'
                ]);

                $admin_single->wallet->update([
                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                    'sid_user_relod' => Auth::guard('web')->user()->id,
                    'amount' => DB::raw('amount+' . $valeur_amount_com),
                ]);
            }
        }
    }

    // Generatye data methode

    public function generateTransactionData()
    {
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $user_auth = Auth::guard('web')->user();
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        return [
            'transactionCode' => $transactionCode,
            'transactionCompiCode' => $transactionCompiCode,
            'user_auth' => $user_auth,
            'admins' => $admins,
            'admin_civ' => $admin_civ
        ];
    }


    // Verify ceilling
    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (
            WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_banks.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_banks.created_at', Carbon::today())
                ->select('wallet_banks.*')
                ->where('wallet_banks.status', 'SUCCESS')
                ->get()
            as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletDeposit::join(
                'wallets',
                'wallet_deposits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_deposits.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_deposits.created_at', Carbon::today())
                ->select('wallet_deposits.*')
                ->where('wallet_deposits.status', 'SUCCESS')
                ->get()
            as $deposit
        ) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (
            WalletInter::join(
                'wallets',
                'wallet_inters.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_inters.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_inters.created_at', Carbon::today())
                ->select('wallet_inters.*')
                ->where('wallet_inters.status', 'SUCCESS')
                ->get()
            as $inter
        ) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletPayment::join(
                'wallets',
                'wallet_payments.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_payments.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_payments.created_at', Carbon::today())
                ->select('wallet_payments.*')
                ->where('wallet_payments.status', 'SUCCESS')
                ->get()
            as $payment
        ) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletTransaction::join(
                'wallets',
                'wallet_transactions.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_transactions.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_transactions.created_at', Carbon::today())
                ->select('wallet_transactions.*')
                ->where('wallet_transactions.status', 'SUCCESS')
                ->get()
            as $transaction
        ) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletPhoneCredit::join(
                'wallets',
                'wallet_phone_credits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_phone_credits.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_phone_credits.created_at', Carbon::today())
                ->select('wallet_phone_credits.*')
                ->where('wallet_phone_credits.status', 'SUCCESS')
                ->get()
            as $phone
        ) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (
            BankCard::where('created_by', Auth::guard('web')->user()->id)
                ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )->where(
                    'wallet_reloads.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_reloads.created_at', Carbon::today())
                ->select('wallet_reloads.*')
                ->where('wallet_reloads.status', 'SUCCESS')
                ->get()
            as $reload
        ) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    // Provider property

    public function getNationTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $transaction_nationnal = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 1)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $transaction_nationnal;
    }

    public function getPaiementTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $payments = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 2)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $payments;
    }



    public function getVirementTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $virement_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 4)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $virement_transaction;
    }

    // Admin valeur function
    public function getAdminValeurProperty()
    {

        $admins = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', Auth::guard('web')->user()->country->id)
            ->get();

        return  $admins;
    }

    public function getAdminDrawalCIVProperty()
    {

        $admin_drawal =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->whereId(1)
            ->where('country_id', 107)
            ->first();

        return $admin_drawal;
    }
    public function getAdminCIVProperty()
    {

        $admin_civ =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        return  $admin_civ;
    }

    public function getSumValTransactionProperty()
    {

        $somm_valeur =  $this->WalletBanksCeiling + $this->WalletDepositsCeiling +
            $this->WalletIntersCeiling +
            $this->WalletPaymentsCeiling +
            $this->WalletTransactionsCeiling +
            $this->WalletPhoneCreditsCeiling +
            $this->WalletReloadsCeiling + $this->WalletBankCardCeiling;

        return  $somm_valeur;
    }


    public function getReloadTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $reload_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 5)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $reload_transaction;
    }


    public function getPhoneCreditTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $phone_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 6)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $phone_transaction;
    }



    // Sum Seilver Ceiling
    public function getWalletBanksSeilverCeilingProperty()
    {
        $data = [];
        foreach (
            WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_banks.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_banks.created_at', Carbon::today())
                ->select('wallet_banks.*')
                ->get()
            as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
    // Admin

    public function getWalletBanksProperty()
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        // Construire la requête de base
        $walletBankQuery = WalletBank::join('wallets', 'wallet_banks.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', $userCountryId)
            ->whereDate('wallet_banks.created_at', Carbon::today())
            ->where('wallet_banks.status', 'SUCCESS');

        // Vérifier si l'utilisateur est sous distributeur
        if (sabdy()->is_under_distributer) {
            $walletBankQuery->where('wallet_banks.wallet_id', sabdy()->wallet->id);
        }

        // Exécuter la requête et calculer la somme des montants
        return $walletBankQuery->sum('wallet_banks.amount');
    }

    public function getWalletDepositsProperty()
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        // Construire la requête de base pour les dépôts
        $walletDepositQuery = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', $userCountryId)
            ->whereDate('wallet_deposits.created_at', Carbon::today())
            ->where('wallet_deposits.status', 'SUCCESS');

        // Vérifier si l'utilisateur est sous distributeur
        if (sabdy()->is_under_distributer) {
            $walletDepositQuery->where('wallet_deposits.wallet_id', sabdy()->wallet->id);
        }

        // Retourner la somme des montants des dépôts
        return $walletDepositQuery->sum('wallet_deposits.amount');
    }

    public function getWalletIntersProperty()
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        // Construire la requête de base pour les intérêts
        $walletInterQuery = WalletInter::join('wallets', 'wallet_inters.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', $userCountryId)
            ->whereDate('wallet_inters.created_at', Carbon::today())
            ->where('wallet_inters.status', 'SUCCESS');

        // Vérifier si l'utilisateur est sous distributeur
        if (sabdy()->is_under_distributer) {
            $walletInterQuery->where('wallet_inters.wallet_id', sabdy()->wallet->id);
        }

        // Retourner la somme des montants des intérêts
        return $walletInterQuery->sum('wallet_inters.amount');
    }


    public function getWalletPaymentsProperty()
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        // Construire la requête de base pour les paiements
        $walletPaymentQuery = WalletPayment::join('wallets', 'wallet_payments.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', $userCountryId)
            ->whereDate('wallet_payments.created_at', Carbon::today())
            ->where('wallet_payments.status', 'SUCCESS');

        // Vérifier si l'utilisateur est sous distributeur
        if (sabdy()->is_under_distributer) {
            $walletPaymentQuery->where('wallet_payments.wallet_id', sabdy()->wallet->id);
        }

        // Retourner la somme des montants des paiements
        return $walletPaymentQuery->sum('wallet_payments.amount');
    }

    public function getWalletTransactionsProperty()
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        // Construire la requête de base pour les transactions
        $walletTransactionQuery = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', $userCountryId)
            ->whereDate('wallet_transactions.created_at', Carbon::today())
            ->where('wallet_transactions.status', 'SUCCESS');

        // Vérifier si l'utilisateur est sous distributeur
        if (sabdy()->is_under_distributer) {
            $walletTransactionQuery->where('wallet_transactions.wallet_id', sabdy()->wallet->id);
        }

        // Retourner la somme des montants des transactions
        return $walletTransactionQuery->sum('wallet_transactions.amount');
    }

    public function getWalletPhoneCreditsProperty()
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        // Construire la requête de base pour les crédits de téléphone
        $walletPhoneCreditQuery = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', $userCountryId)
            ->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->where('wallet_phone_credits.status', 'SUCCESS');

        // Vérifier si l'utilisateur est sous distributeur
        if (sabdy()->is_under_distributer) {
            $walletPhoneCreditQuery->where('wallet_phone_credits.wallet_id', sabdy()->wallet->id);
        }

        // Retourner la somme des montants des crédits de téléphone
        return $walletPhoneCreditQuery->sum('wallet_phone_credits.amount');
    }


    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }

    public function getWalletReloadsProperty()
    {
        $data = [];

        foreach (
            WalletReload::join(
                'wallets',
                'wallet_reloads.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_reloads.*')
                ->whereDate('wallet_reloads.created_at', Carbon::today())
                ->select('wallet_reloads.*')
                ->where('wallet_reloads.status', 'SUCCESS')
                ->get()
            as $reload
        ) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    // Details Transaction Admin

    private function getTransactionCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletInfos = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_transactions.status', $status)
                ->where('wallet_transactions.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_transactions.created_at', Carbon::today())
                ->count();
        } else {
            $walletInfos = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_transactions.status', $status)
                ->whereDate('wallet_transactions.created_at', Carbon::today())
                ->count();
        }

        return  $walletInfos;
    }
    public function getTransactionErrorProperty()
    {
        return $this->getTransactionCountByStatus('ERROR');
    }

    public function getTransactionSuccessProperty()
    {
        return $this->getTransactionCountByStatus('SUCCESS');
    }

    public function getTransactionPenddingProperty()
    {
        return $this->getTransactionCountByStatus('PENDING');
    }

    private function getPaymentCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletPaiement = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_payments.status', $status)
                ->where('wallet_payments.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_payments.created_at', Carbon::today())
                ->count();
        } else {
            $walletPaiement = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_payments.status', $status)
                ->whereDate('wallet_payments.created_at', Carbon::today())
                ->count();
        }

        return  $walletPaiement;
    }

    public function getPaymentErrorProperty()
    {
        return $this->getPaymentCountByStatus('ERROR');
    }

    public function getPaymentSuccessProperty()
    {
        return $this->getPaymentCountByStatus('SUCCESS');
    }

    public function getPaymentPendingProperty()
    {
        return $this->getPaymentCountByStatus('PENDING');
    }

    private function getReloadCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletPaiement = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_reloads.status', $status)
                ->where('wallet_reloads.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_reloads.created_at', Carbon::today())
                ->count();
        } else {
            $walletPaiement = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_reloads.status', $status)
                ->whereDate('wallet_reloads.created_at', Carbon::today())
                ->count();
        }

        return  $walletPaiement;
    }
    public function getReloadErrorProperty()
    {
        return $this->getReloadCountByStatus('ERROR');
    }

    public function getReloadSuccessProperty()
    {

        return $this->getReloadCountByStatus('SUCCESS');
    }

    public function getReloadPenddingProperty()
    {

        return $this->getReloadCountByStatus('PENDING');
    }

    private function getVirementCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletPaiement = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_banks.status', $status)
                ->where('wallet_banks.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_banks.created_at', Carbon::today())
                ->count();
        } else {
            $walletPaiement = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_banks.status', $status)
                ->whereDate('wallet_banks.created_at', Carbon::today())
                ->count();
        }

        return  $walletPaiement;
    }
    public function getVirementSuccessProperty()
    {

        return $this->getVirementCountByStatus('SUCCESS');
    }

    public function getVirementPenddingProperty()
    {

        return $this->getVirementCountByStatus('PENDING');
    }

    public function getVirementErrorProperty()
    {

        return $this->getVirementCountByStatus('ERROR');
    }

    private function getInterCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletInters = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_inters.status', $status)
                ->where('wallet_inters.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_inters.created_at', Carbon::today())
                ->count();
        } else {
            $walletInters = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_inters.status', $status)
                ->whereDate('wallet_inters.created_at', Carbon::today())
                ->count();
        }

        return  $walletInters;
    }

    public function getInterErrorProperty()
    {
        return $this->getInterCountByStatus('ERROR');
    }

    public function getInterSuccessProperty()
    {
        return $this->getInterCountByStatus('SUCCESS');
    }

    public function getInterPenddingProperty()
    {
        return $this->getInterCountByStatus('PENDING');
    }

    private function getPhoneCreditCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletInfos = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_phone_credits.status', $status)
                ->where('wallet_phone_credits.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_phone_credits.created_at', Carbon::today())
                ->count();
        } else {
            $walletInfos = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_phone_credits.status', $status)
                ->whereDate('wallet_phone_credits.created_at', Carbon::today())
                ->count();
        }

        return  $walletInfos;
    }

    public function getPhoneCreditErrorProperty()
    {
        return $this->getPhoneCreditCountByStatus('ERROR');
    }

    public function getPhoneCreditSuccessProperty()
    {
        return $this->getPhoneCreditCountByStatus('SUCCESS');
    }

    public function getPhoneCreditPenddingProperty()
    {
        return $this->getPhoneCreditCountByStatus('PENDING');
    }

    private function getDepositCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletInfos = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_deposits.status', $status)
                ->where('wallet_deposits.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_deposits.created_at', Carbon::today())
                ->count();
        } else {
            $walletInfos = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_deposits.status', $status)
                ->whereDate('wallet_deposits.created_at', Carbon::today())
                ->count();
        }

        return  $walletInfos;
    }

    public function getDepositErrorProperty()
    {
        return $this->getDepositCountByStatus('ERROR');
    }

    public function getDepositSuccessProperty()
    {
        return $this->getDepositCountByStatus('SUCCESS');
    }

    public function getDepositPenddingProperty()
    {
        return $this->getDepositCountByStatus('PENDING');
    }
}
