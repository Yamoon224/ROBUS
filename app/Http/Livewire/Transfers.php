<?php

namespace App\Http\Livewire;

use App\Events\AdminNotifEvent;
use App\Events\ChooseInterEvent;
use App\Events\ChoosePaymentEvent;
use App\Events\ChoosePhoneCreditEvent;
use App\Events\ChooseReloadCardEvent;
use App\Events\ChooseTransactionEvent;
use App\Events\ChooseVirementEvent;
use App\Events\ChooseWaveEvent;
use App\Events\ChooseXbetEvent;
use App\Events\CreateWalletBankEvent;
use App\Events\CreateWalletPaiementEvent;
use App\Events\CreateWalletPhoneCreditEvent;
use App\Events\CreateWalletReloadCardEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Events\UpdateBankEvent;
use App\Events\UpdateInterEvent;
use App\Events\UpdatePaiementEvent;
use App\Events\UpdatePhoneCreditEvent;
use App\Events\UpdateReloadCardEvent;
use App\Events\UserNotifEvent;
use App\Http\Controllers\Admin\MailController;
use App\Mail\CodeValidated;
use App\Models\AdminsCom;
use App\Models\Banks\BankCard;
use App\Models\Costs\BankCost;
use App\Models\Costs\DepositCost;
use App\Models\Costs\InterCost;
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
use App\Models\Invoices\InvoiceWave;
use App\Models\Invoices\OneBetInvoice;
use App\Models\Moneys\LevelMoney;
use App\Models\Offer;
use App\Models\Payments\Deposit;
use App\Models\Payments\OneBetTransaction;
use App\Models\Payments\Payment;
use App\Models\Payments\PaymentBank;
use App\Models\Payments\PaymentPhoneCredit;
use App\Models\Payments\PaymentWave;
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
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Database\Eloquent\Model;
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
    public $seachXbet = '';
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

    // Edit Xbet
    public $provider_xbet_edit;
    public $provider_name_xbet_edit;
    public $xbet_id;
    public $number_xbet_edit;
    public $amount_xbet_edit;
    public $countrie_xbet_edit;
    public $citie_xbet_edit;
    public $code_xbet_edit;
    public $fullname_xbet_edit;
    public $status_xbet_edit;
    public $choose_xbet_edit;
    public $xbetDescription;


    // Edit Wave UV
    public $provider_wave_edit;
    public $provider_name_wave_edit;
    public $wave_id;
    public $number_wave_edit;
    public $amount_wave_edit;
    public $countrie_wave_edit;
    public $citie_wave_edit;
    public $fullname_wave_edit;
    public $status_wave_edit;
    public $waveDescription;

    // Protected valeurs Services
    private $phoneCreditService1;

    protected $listeners = [
        '' => 'transactionInterReload',
        'transaction_inters' => 'transactionData',
        'transaction_national' => 'transactionNationData',
        'transaction_centres' => 'transactionAll',
        'echo:wallet-bank-created,.Created_wallet_bank' => 'notifyNewBank',
        'echo:updated_bank,.update_wallet_bank' => 'notifyNewBank',
        'echo:wallet-paiement-created,.Created_wallet_paiement' => 'notifyNewPaiement',
        'echo:wallet-xbet-created,.Created_wallet_xbet' => 'notifyNewXbet',
        'echo:updated_paiement,.update_wallet_paiement' => 'notifyNewPaiement',
        'echo:wallet-transfers-created,.Created_wallet_transfers' => 'notifyNewTransfers',
        'echo:updated_transfers,.update_wallet_transfers' => 'notifyNewTransfers',
        'echo:wallet-inter-created,.Created_wallet_inter' => 'notifyNewInter',
        'echo:updated_inter,.update_wallet_inter' => 'notifyNewInter',
        'echo:wallet-card-created,.Created_wallet_card' => 'notifyNewCard',
        'echo:updated_card,.update_wallet_card' => 'notifyNewCard',
        'echo:wallet-phone-created,.Created_wallet_phone' => 'notifyNewPhone',
        'echo:updated_phone,.update_wallet_phone' => 'notifyNewPhone',
        'echo:updated_phone,.update_wallet_phone' => 'notifyNewPhone',
        'echo:updated_xbet,.update_wallet_xbet' => 'notifyNewXbet',
        'echo:updated_wave,.update_wallet_wave' => 'notifyNewWave',
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
        'echo:wallet-xbet-choose-created,.Created_wallet_xbet_choose' =>
        'notifyNewXbet',
        'echo:wallet-wave-choose-created,.Created_wallet_wave_choose' =>
        'notifyNewWave',
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

    public function notifyNewXbet()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-phoneCredit');
        $this->emit('menu-xbet-active');
    }

    public function notifyNewWave()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-phoneCredit');
        $this->emit('menu-wave-active');
    }

    // Add Virement 
    public function addVirement()
    {
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_virement)->first();
        // Country display
        $country_virement = Country::whereId($this->country_virement)
            ->whereStatus(true)
            ->first();

        $admins = $this->getAdminValeurProperty();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();
        // Verifier plafond days with piece
        $valeur_ceiling = $walletUser->silver_ceiling;

        //  Verifier transfers sum amount days
        try {

            // And provider NSIA and Ecobank
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
                } else {

                    // Verify valeur amount max in Landing the ROBUS
                    $valeur_end = $percent_robus->levelBankIvories->last();

                    if ($valeur_end->amount_end < $this->amount_inter) {
                        $this->resetVirementField();
                        $this->emit('modal-hide-virement');
                        $this->emit('info', 'Vous avez depasser le montant max !');
                    } else {

                        foreach ($percent_robus->levelBankIvories as $amount_user) {

                            // Verify in interval valeur of amount Use send
                            if ($this->amount_virement >= $amount_user->amount_begin && $this->amount_virement <= $amount_user->amount_end) {
                                if ($walletUser->is_under_distributer == 1) {
                                    $amount_valeur = $this->amount_virement;
                                } else {

                                    $amount_valeur = $this->amount_virement  +  $amount_user->amount;
                                }
                                // Solde User
                                $soldeWallet = $walletUser->wallet->amount;


                                // Verify Solde User
                                if ($amount_valeur > $soldeWallet) {
                                    $this->resetVirementField();
                                    $this->emit('modal-hide-virement');
                                    $this->emit('warning', 'Verifier votre montant !');
                                } else {

                                    $amount_solde =  $walletUser->wallet->amount -  $amount_valeur;

                                    $walletBank = WalletBank::create([
                                        'wallet_id' => $walletUser->wallet->id,
                                        'amount' => $this->amount_virement,
                                        'description' => $this->virementDescription,
                                        'bank_cost_id' => 2,
                                        'created_by' => Auth::guard('web')->user()->id,
                                        'provider_id' => $provider->id,
                                        'number_compte' => $this->account_number_user_bank,
                                        'costs_amount' => $amount_user->amount,
                                        'firstname' => $this->firstname_user,
                                        'lastname' => $this->lastname_user,
                                        'phone' => $this->phone_user,
                                        'status' => 'PENDING',
                                        'amount_solde' => $amount_solde ?? null,
                                        'country_id' => $country_virement->id ?? null,
                                    ]);

                                    // Send Sms if User leves CIV
                                    if ($walletUser->country->code == 'CI') {

                                        $message = 'Vous avez effectué un virement de ' . $walletBank->amount . $walletUser->wallet->currencie->code .
                                            ' ' .
                                            'par ' . $walletBank->provider->name .
                                            ' a ' . $walletBank->firstname .
                                            ' ' . $walletBank->firstname .
                                            ' le ' . $walletBank->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';

                                        $encode = substr(Auth::guard('web')->user()->phone, -10);

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                    }


                                    if ($walletUser->is_under_distributer == 1) {

                                        // CommiTsion of paiement
                                        $commission =  $walletBank->amount;

                                        $walletUser->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname .
                                                ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount-' . $commission),
                                        ]);

                                        // Commission admin
                                        $admin_valeur =  $amount_user->amount -  $amount_user->comission_distributer;

                                        // Commission distributer
                                        $valuer_deposit =  $amount_user->comission_distributer;

                                        //  Send valeur in Admin
                                        if ($admin_civ->count() > 0) {

                                            $count_admin = $admin_civ->count();

                                            $valeur_single = $valuer_deposit / $count_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                $valeur_deposit =  AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => $walletBank->id,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' => null,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Paiement commission sous-distributer',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        $walletBank->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);
                                    } else {


                                        // Commision of paiement
                                        $commission =  $walletBank->amount  +  $amount_user->amount;


                                        $walletUser->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname .
                                                ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount-' . $commission),
                                        ]);

                                        // Commission admin
                                        $admin_valeur =  $amount_user->amount;

                                        //  Send valeur in Admin
                                        if ($admin_civ->count() > 0) {

                                            $count_admin = $admin_civ->count();

                                            $valeur_single = $admin_valeur / $count_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => $walletBank->id,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' => null,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }
                                    }
                                    // Send Notification In User and Admins and display notification not read
                                    $msg = 'Demande de virement est en cours de validation  ';
                                    $msg_title = 'Virement bancaire';
                                    $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                    $notifs = $walletUser->unreadNotifications;
                                    event(new CreateWalletBankEvent($walletBank));
                                    Notification::send($admins, new AdminNotification('Demande de virement de ' . ' ' . $walletBank->wallet->sidUser->firstname));
                                    if ($admins->count() > 0) {


                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;
                                            event(new AdminNotifEvent($notifs));
                                        }



                                        $this->resetVirementField();
                                        $this->emit('modal-hide-virement');
                                        $this->emit('success', 'Versement effectué !');

                                        if ($walletUser->email != null) {
                                            $notify = new MailController();
                                            $notify->newVirement($walletBank);
                                        }
                                    } else {

                                        $this->resetVirementField();
                                        $this->emit('modal-hide-virement');
                                        $this->emit('success', 'Virement effectué !');

                                        if ($walletUser->email != null) {
                                            $notify = new MailController();
                                            $notify->newVirement($walletBank);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $this->emit('error', 'Veuillez selectionnez un virement !');
            }
        } catch (\Exception $exception) {
            $this->resetVirementField();
            $this->emit('modal-hide-virement');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
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



                    if (sabdy()->is_distributer == 1) {

                        // Seach wallet User
                        $wallet_search = Wallet::where('wallet_id',  $this->compte_number_robus)
                            ->where('country_id', $walletUser->country->id)
                            // ->where('status', true)
                            ->first();

                        if ($wallet_search == null &&  $this->compte_number_robus == $walletUser->wallet->wallet_id) {
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
                        } elseif (!$wallet_search->sidUser->is_under_distributer ||  !$wallet_search->sidUser->is_custumers || !$wallet_search->sidUser->is_distributer) {

                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers');
                                $this->emit('warning', 'Votre montant journier est atteint !');
                            } else {

                                $wallet_send = $wallet_search;

                                $commission_receiveid =  $this->transaction_amount_robus;

                                $wallet_receveid =  $walletUser->wallet;

                                $commission_send =  $this->convertAdminCommissionInter($commission_receiveid, $wallet_send, $wallet_receveid);

                                // Verify amount
                                if ($commission_send > $wallet_search->amount) {
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('success', 'Verifier votre montant ! !');
                                } else {



                                    $wallet = $wallet_search;

                                    $commission_valeur = $percent_robus_distri->percent * $this->transaction_amount_robus;

                                    $valeur_amount_com = $percent_robus_distri->percent_with_drawal * $commission_valeur;



                                    $valuer_with_drawal =  $this->convertAdminCommission($valeur_amount_com, $wallet);

                                    $walletAmountSend = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $this->compte_number_robus)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->total_amount -  (float)$commission_send;

                                    $amount_drawal =      $amount_solde_drawal;

                                    // Update decrement in compte Client
                                    $wallet_search->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount-' . $commission_send),
                                    ]);


                                    // $amount_solde = $check->wallet->amount +  $commission;
                                    $amount_solde = (float)sabdy()->wallet->amount +  (float) $this->transaction_amount_robus;

                                    $walletUser->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+'  .  $this->transaction_amount_robus),
                                    ]);



                                    $walletTransaction = WalletTransaction::create(
                                        [
                                            'wallet_id' => $walletUser->wallet->id,
                                            'amount' => $this->transaction_amount_robus,
                                            'description' => $this->transaction_description_robus ?? null,
                                            'transaction_cost_id' => 26,
                                            'phone' => $this->transaction_phone ??  null,
                                            'num_wallet' =>  $this->compte_number_robus,
                                            'created_by' => Auth::guard('web')->user()->id,
                                            'provider_id' => $provider->id,
                                            'code_validated' => null,
                                            'amount_received'  => $commission_send,
                                            'choose_type_transaction' => $this->choose_type_transaction,
                                            'type_transaction' => 'Compense',
                                            'type_received' => 'Transfert',
                                            'status' => 'SUCCESS',
                                            'amount_solde' => $amount_solde ?? null,
                                            'amount_solde_drawal' => $amount_drawal ?? null
                                        ]
                                    );
                                    // Valeur receive


                                    $transaction = Transaction::create([
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

                                    InvoiceTransaction::create([
                                        'title' => "Transfers d'argent",
                                        'wallet_transaction_id' => $walletTransaction->id,
                                        'transactions_id' => $transaction->id,
                                        'company_id' => Auth::guard('web')->user()->company->id,
                                        'description' => $walletTransaction->description,
                                        'status' => $walletTransaction->status,
                                        'created_by' => Auth::guard('web')->user()->id,
                                    ]);

                                    // Commission Under_distributer

                                    if ($admin_civ->count() > 0) {

                                        $nmbre_admin = $admin_civ->count();

                                        $valeur_single = $valuer_with_drawal / $nmbre_admin;

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

                                    // Send Email
                                    if ($wallet_search->sidUser->email != null) {

                                        Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Robus vous a retiré la somme de  : ' . $walletTransaction->amount . ' Sur votre compte ' . ' Votre montant est de : ' .  $walletUser->wallet->amount));
                                    }

                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newTransaction($walletTransaction);
                                    }

                                    // Notify And User an Admins
                                    $msg_title = 'Transactions';
                                    $msg = 'Demande de transfert est en cours de validation ';
                                    $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                    $notifs = $walletUser->unreadNotifications;
                                    $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                    $notifs = $wallet_search->sidUser->unreadNotifications;
                                    event(new CreateWalletTransfersEvent($walletTransaction));
                                    Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $walletTransaction->wallet->sidUser->firstname));
                                    $admin = SidUser::where('status', true)
                                        ->where('is_admin', true)
                                        ->first();
                                    $notifs = $admin->unreadNotifications;
                                    event(new AdminNotifEvent($notifs));
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('success', 'Retrait effectué avec success !');
                                }
                            }
                        } else {

                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Pas de selection de providers !');
                        }
                    } elseif (sabdy()->is_admin == 1) {

                        // Seach wallet User
                        $wallet_search = Wallet::where('wallet_id',  $this->compte_number_robus)
                            // ->where('country_id', $walletUser->country->id)
                            // ->where('status', true)
                            ->first();

                        if ($wallet_search == null &&  $this->compte_number_robus == $walletUser->wallet->wallet_id) {
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
                        } elseif (
                            !$wallet_search->sidUser->is_under_distributer
                            || !$wallet_search->sidUser->is_custumers
                            || !$wallet_search->sidUser->is_distributer
                            || !$wallet_search->sidUser->is_supervisor
                        ) {


                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers');
                                $this->emit('warning', 'Votre montant journier est atteint !');
                            } else {

                                $wallet_send = $wallet_search;

                                $commission_receiveid =  $this->transaction_amount_robus;

                                $wallet_receveid =  $walletUser->wallet;

                                $commission_send =  $this->convertAdminCommissionInter($commission_receiveid, $wallet_send, $wallet_receveid);

                                // Verify amount
                                if ($commission_send > $wallet_search->amount) {
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('success', 'Verifier votre montant ! !');
                                } else {



                                    $wallet = $wallet_search;

                                    $commission_valeur = $percent_robus_distri->percent * $this->transaction_amount_robus;

                                    $valeur_amount_com = $percent_robus_distri->percent_with_drawal * $commission_valeur;



                                    $valuer_with_drawal =  $this->convertAdminCommission($valeur_amount_com, $wallet);

                                    $walletAmountSend = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $this->compte_number_robus)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->total_amount -  (float)$commission_send;

                                    $amount_drawal =      $amount_solde_drawal;

                                    // Update decrement in compte Client
                                    $wallet_search->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount-' . $commission_send),
                                    ]);


                                    // $amount_solde = $check->wallet->amount +  $commission;
                                    $amount_solde = (float)sabdy()->wallet->amount +  (float) $this->transaction_amount_robus;

                                    $walletUser->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+'  .  $this->transaction_amount_robus),
                                    ]);



                                    $walletTransaction = WalletTransaction::create(
                                        [
                                            'wallet_id' => $walletUser->wallet->id,
                                            'amount' => $this->transaction_amount_robus,
                                            'description' => $this->transaction_description_robus ?? null,
                                            'transaction_cost_id' => 26,
                                            'phone' => $this->transaction_phone ??  null,
                                            'num_wallet' =>  $this->compte_number_robus,
                                            'created_by' => Auth::guard('web')->user()->id,
                                            'provider_id' => $provider->id,
                                            'code_validated' => null,
                                            'amount_received'  => $commission_send,
                                            'choose_type_transaction' => $this->choose_type_transaction,
                                            'type_transaction' => 'Compense',
                                            'type_received' => 'Transfert',
                                            'status' => 'SUCCESS',
                                            'amount_solde' => $amount_solde ?? null,
                                            'amount_solde_drawal' => $amount_drawal ?? null
                                        ]
                                    );
                                    // Valeur receive


                                    $transaction = Transaction::create([
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

                                    InvoiceTransaction::create([
                                        'title' => "Transfers d'argent",
                                        'wallet_transaction_id' => $walletTransaction->id,
                                        'transactions_id' => $transaction->id,
                                        'company_id' => Auth::guard('web')->user()->company->id,
                                        'description' => $walletTransaction->description,
                                        'status' => $walletTransaction->status,
                                        'created_by' => Auth::guard('web')->user()->id,
                                    ]);

                                    // Commission Under_distributer

                                    // if ($admin_civ->count() > 0) {

                                    //     $nmbre_admin = $admin_civ->count();

                                    //     $valeur_single = $valuer_with_drawal / $nmbre_admin;

                                    //     foreach ($admin_civ as $user) {

                                    //         $admin_single = SidUser::find($user->id);

                                    //         AdminsCom::create([
                                    //             'amount' =>   $valeur_single,
                                    //             'sid_user_id' =>   $admin_single->id,
                                    //             'wallet_transaction_id' => $walletTransaction->id,
                                    //             'wallet_bank_id' => null,
                                    //             'wallet_deposit_id' => null,
                                    //             'wallet_inter_id' =>  null,
                                    //             'wallet_payment_id' => null,
                                    //             'wallet_phone_credit_id' => null,
                                    //             'wallet_reload_id' => null,
                                    //             'bank_card_id' => null,
                                    //             'description' => 'Retrait d\'argent',
                                    //         ]);

                                    //         $admin_single->wallet->update([
                                    //             'reload_by' =>  Auth::guard('web')->user()->firstname .
                                    //                 ' ' .
                                    //                 Auth::guard('web')->user()->lastname,
                                    //             'sid_user_relod' =>  Auth::guard('web')->user()->id,
                                    //             'amount' => DB::raw('amount-' . $valeur_single),
                                    //         ]);
                                    //     }
                                    // }

                                    // Send Email
                                    if ($wallet_search->sidUser->email != null) {

                                        Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Robus vous a retiré la somme de  : ' . $walletTransaction->amount . ' Sur votre compte ' . ' Votre montant est de : ' .  $walletUser->wallet->amount));
                                    }

                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newTransaction($walletTransaction);
                                    }

                                    // Notify And User an Admins
                                    $msg_title = 'Transactions';
                                    $msg = 'Demande de transfert est en cours de validation ';
                                    $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                    $notifs = $walletUser->unreadNotifications;
                                    $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                    $notifs = $wallet_search->sidUser->unreadNotifications;
                                    event(new CreateWalletTransfersEvent($walletTransaction));
                                    Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $walletTransaction->wallet->sidUser->firstname));
                                    $admin = SidUser::where('status', true)
                                        ->where('is_admin', true)
                                        ->first();
                                    $notifs = $admin->unreadNotifications;
                                    event(new AdminNotifEvent($notifs));
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('success', 'Retrait effectué avec success !');
                                }
                            }
                        } else {

                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Pas de selection de providers !');
                        }
                    } elseif (sabdy()->is_supervisor == 1) {

                        // Seach wallet User
                        $wallet_search = Wallet::where('wallet_id',  $this->compte_number_robus)
                            // ->where('country_id', $walletUser->country->id)
                            // ->where('status', true)
                            ->first();

                        if ($wallet_search == null &&  $this->compte_number_robus == $walletUser->wallet->wallet_id) {
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
                        } elseif (!$wallet_search->sidUser->is_under_distributer ||  !$wallet_search->sidUser->is_custumers || !$wallet_search->sidUser->is_distributer) {

                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers');
                                $this->emit('warning', 'Votre montant journier est atteint !');
                            } else {


                                $wallet_send = $wallet_search;

                                $commission_receiveid =  $this->transaction_amount_robus;

                                $wallet_receveid =  $walletUser->wallet;

                                $commission_send =  $this->convertAdminCommissionInter($commission_receiveid, $wallet_send, $wallet_receveid);

                                // Verify amount
                                if ($commission_send > $wallet_search->amount) {
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('success', 'Verifier votre montant ! !');
                                } else {



                                    $wallet = $wallet_search;

                                    $commission_valeur = $percent_robus_distri->percent * $this->transaction_amount_robus;

                                    $valeur_amount_com = $percent_robus_distri->percent_with_drawal * $commission_valeur;



                                    $valuer_with_drawal =  $this->convertAdminCommission($valeur_amount_com, $wallet);

                                    $walletAmountSend = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $this->compte_number_robus)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->total_amount -  (float)$commission_send;

                                    $amount_drawal =      $amount_solde_drawal;

                                    // Update decrement in compte Client
                                    $wallet_search->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount-' . $commission_send),
                                    ]);


                                    // $amount_solde = $check->wallet->amount +  $commission;
                                    $amount_solde = (float)sabdy()->wallet->amount +  (float) $this->transaction_amount_robus;

                                    $walletUser->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+'  .  $this->transaction_amount_robus),
                                    ]);



                                    $walletTransaction = WalletTransaction::create(
                                        [
                                            'wallet_id' => $walletUser->wallet->id,
                                            'amount' => $this->transaction_amount_robus,
                                            'description' => $this->transaction_description_robus ?? null,
                                            'transaction_cost_id' => 26,
                                            'phone' => $this->transaction_phone ??  null,
                                            'num_wallet' =>  $this->compte_number_robus,
                                            'created_by' => Auth::guard('web')->user()->id,
                                            'provider_id' => $provider->id,
                                            'code_validated' => null,
                                            'amount_received'  => $commission_send,
                                            'choose_type_transaction' => $this->choose_type_transaction,
                                            'type_transaction' => 'Compense',
                                            'type_received' => 'Transfert',
                                            'status' => 'SUCCESS',
                                            'amount_solde' => $amount_solde ?? null,
                                            'amount_solde_drawal' => $amount_drawal ?? null
                                        ]
                                    );
                                    // Valeur receive


                                    $transaction = Transaction::create([
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

                                    InvoiceTransaction::create([
                                        'title' => "Transfers d'argent",
                                        'wallet_transaction_id' => $walletTransaction->id,
                                        'transactions_id' => $transaction->id,
                                        'company_id' => Auth::guard('web')->user()->company->id,
                                        'description' => $walletTransaction->description,
                                        'status' => $walletTransaction->status,
                                        'created_by' => Auth::guard('web')->user()->id,
                                    ]);

                                    // Commission Under_distributer



                                    // Send Email
                                    if ($wallet_search->sidUser->email != null) {

                                        Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Robus vous a retiré la somme de  : ' . $walletTransaction->amount . ' Sur votre compte ' . ' Votre montant est de : ' .  $walletUser->wallet->amount));
                                    }

                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newTransaction($walletTransaction);
                                    }

                                    // Notify And User an Admins
                                    $msg_title = 'Transactions';
                                    $msg = 'Demande de transfert est en cours de validation ';
                                    $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                    $notifs = $walletUser->unreadNotifications;
                                    $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                    $notifs = $wallet_search->sidUser->unreadNotifications;
                                    event(new CreateWalletTransfersEvent($walletTransaction));
                                    Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $walletTransaction->wallet->sidUser->firstname));
                                    $admin = SidUser::where('status', true)
                                        ->where('is_admin', true)
                                        ->first();
                                    $notifs = $admin->unreadNotifications;
                                    event(new AdminNotifEvent($notifs));
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('success', 'Retrait effectué avec success !');
                                }
                            }
                        } else {

                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Pas de selection de providers !');
                        }
                    } else {

                        $this->resetTransactionField();
                        $this->emit('modal-hide-transfers');
                        $this->emit('error', 'Pas de selection de providers !');
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
    // Sted 2 Validated transaction
    public function addTransactionWithdrawal()
    {

        $walletUser = Auth::guard('web')->user();
        $rt = rand(40, 500000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $percent_robus_distri = TransactionCost::whereId(1)->first();
        $percent_robus_custumer = TransactionCost::whereId(2)->first();
        $percent_robus_distri_gh = TransactionCost::whereId(11)->first();
        $percent_robus_custumer_gh = TransactionCost::whereId(12)->first();
        $percent_robus_distri_ma = TransactionCost::whereId(16)->first();
        $percent_robus_custumer_ma = TransactionCost::whereId(17)->first();
        $percent_robus_distri_tn = TransactionCost::whereId(21)->first();
        $percent_robus_custumer_tn = TransactionCost::whereId(22)->first();
        $percent_robus_distri_fr = TransactionCost::whereId(6)->first();
        $percent_robus_custumer_fr = TransactionCost::whereId(7)->first();
        $percent_robus_distri_ci = TransactionCost::whereId(26)->first();
        $percent_robus_custumer_ci = TransactionCost::whereId(26)->first();
        $admins = $this->getAdminValeurProperty();
        $admin_civ_drawal = $this->getAdminDrawalCIVProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

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

                    if ($walletUser->country->code == 'CF') {

                        // If person use reaload acompte is distributer of s-distributer
                        if ($walletUser->is_under_distributer == 1) {

                            // Valur off commission
                            $commission_valeur = $percent_robus_distri->percent * $verify_code->amount;

                            // 30% with drawal
                            $valuer_with_drawal = $percent_robus_distri->percent_with_drawal * $commission_valeur;


                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Valeur receive

                            // Update decrement in compte Client
                            $wallet_user->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Commission Under_distributer

                            if ($admin_civ->count() > 0) {

                                $nmbre_admin = $admin_civ->count();

                                $valeur_single = $valuer_with_drawal / $nmbre_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => $verify_code->id,
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


                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            $verify_code->update([
                                'commissions' => DB::raw('commissions+' . $valuer_with_drawal),
                            ]);

                            if ($walletUser->country->code == 'CI') {

                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount . $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at .
                                    'votre demande est en-cours de validation ' .
                                    'Robus vous remercie';
                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }
                            // Notify And User an Admins
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        } else {

                            // If is not Distribiter or s-distributer

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            //valeur send
                            $commission_valeur = $percent_robus_custumer->percent * $verify_code->amount;

                            // 30% with drawal
                            $valuer_with_drawal = $percent_robus_custumer->percent_with_drawal * $commission_valeur;

                            // Valeur receive

                            // Send in compte Client
                            $wallet_user->update([
                                'reload_by' =>  Auth::guard('web')->user()->firstname .
                                    ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            // Commission Under_distributer
                            // Commission Under_distributer

                            // if ($admin_civ->count() > 0) {

                            //     $nmbre_admin = $admin_civ->count();

                            //     $valeur_single = $valuer_with_drawal / $nmbre_admin;

                            //     foreach ($admin_civ as $user) {

                            //         $admin_single = SidUser::find($user->id);

                            //         AdminsCom::create([
                            //             'amount' =>   $valeur_single,
                            //             'sid_user_id' =>   $admin_single->id,
                            //             'wallet_transaction_id' => $verify_code->id,
                            //             'wallet_bank_id' => null,
                            //             'wallet_deposit_id' => null,
                            //             'wallet_inter_id' =>  null,
                            //             'wallet_payment_id' => null,
                            //             'wallet_phone_credit_id' => null,
                            //             'wallet_reload_id' => null,
                            //             'bank_card_id' => null,
                            //             'description' => 'Retrait d\'argent',
                            //         ]);

                            //         $admin_single->wallet->update([
                            //             'reload_by' =>  Auth::guard('web')->user()->firstname .
                            //                 ' ' .
                            //                  Auth::guard('web')->user()->lastname,
                            //             'sid_user_relod' =>  Auth::guard('web')->user()->id,
                            //             'amount' => DB::raw('amount-' . $valeur_single),
                            //         ]);
                            //     }
                            // }


                            // $verify_code->update([
                            //     'commissions' => DB::raw('commissions+' . $valuer_with_drawal),
                            // ]);

                            if ($walletUser->country->code == 'CI') {

                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount .
                                    $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at . ' votre demande est en-cours de validation ' .
                                    'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notifications
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Demande de transfert de ' .
                                ' ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        }
                    } elseif ($walletUser->country->code == 'GH') {

                        // If person use reaload acompte is distributer of s-distributer
                        if ($walletUser->is_under_distributer == 1) {

                            // Valur off commission
                            $commission_valeur = $percent_robus_distri_gh->percent * $verify_code->amount;

                            // 30% with drawal
                            $valuer_with_drawal = $percent_robus_distri_gh->percent_with_drawal * $commission_valeur;

                            $conver_drawal_amount =  $valuer_with_drawal * $walletUser->wallet->currencie->value;

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Valeur receive

                            // Update decrement in compte Client
                            $wallet_user->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Commission Under_distributer
                            if ($admin_civ->count() > 0) {

                                $nmbre_admin = $admin_civ->count();

                                $valeur_single = $conver_drawal_amount / $nmbre_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => $verify_code->id,
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


                            $verify_code->update([
                                'commissions' => DB::raw('commissions+' . $valuer_with_drawal),
                            ]);

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            if ($walletUser->country->code == 'CI') {

                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount . $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at .
                                    'votre demande est en-cours de validation ' .
                                    'Robus vous remercie';
                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notify And User an Admins
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        } else {

                            // Valur off commission
                            $commission_valeur = $percent_robus_distri_gh->percent * $verify_code->amount;

                            // 30% with drawal
                            $valuer_with_drawal = $percent_robus_distri_gh->percent_with_drawal * $commission_valeur;

                            $conver_drawal_amount =  $valuer_with_drawal * $walletUser->wallet->currencie->value;

                            // If is not Distribiter or s-distributer

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            //valeur send

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            // Valeur receive

                            // Send in compte Client
                            $wallet_user->update([
                                'reload_by' =>  Auth::guard('web')->user()->firstname .
                                    ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);


                            if ($walletUser->country->code == 'CI') {
                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount .
                                    $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at . ' votre demande est en-cours de validation ' .
                                    'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notifications
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Demande de transfert de ' .
                                ' ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        }
                    } elseif ($walletUser->country->code == 'MA') {
                        // If person use reaload acompte is d

                        if ($walletUser->is_under_distributer == 1) {


                            // Valeur off commission
                            $commission_valeur = $percent_robus_distri_ma->percent * $verify_code->amount;

                            // 30% with drawal
                            $valuer_with_drawal = $percent_robus_distri_ma->percent_with_drawal * $commission_valeur;

                            $conver_drawal_amount =  $valuer_with_drawal * $walletUser->wallet->currencie->value;

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Valeur receive

                            // Update decrement in compte Client
                            $wallet_user->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Commission Under_distributer
                            if ($admin_civ->count() > 0) {

                                $nmbre_admin = $admin_civ->count();

                                $valeur_single = $conver_drawal_amount / $nmbre_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => $verify_code->id,
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

                            $verify_code->update([
                                'commissions' => DB::raw('commissions+' . $valuer_with_drawal),
                            ]);

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            if ($walletUser->country->code == 'CI') {

                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount . $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at .
                                    'votre demande est en-cours de validation ' .
                                    'Robus vous remercie';
                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notify And User an Admins
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        } else {

                            // If is not Distribiter or s-distributer

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);


                            // Send in compte Client
                            $wallet_user->update([
                                'reload_by' =>  Auth::guard('web')->user()->firstname .
                                    ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);


                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            if ($walletUser->country->code == 'CI') {
                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount .
                                    $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at . ' votre demande est en-cours de validation ' .
                                    'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notifications
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Demande de transfert de ' .
                                ' ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        }
                    } elseif ($walletUser->country->code == 'TN') {
                        // If person use reaload acompte is d
                        if ($walletUser->is_under_distributer == 1) {


                            // Valur off commission
                            $commission_valeur = $percent_robus_distri_tn->percent * $verify_code->amount;

                            // 30% with drawal
                            $valuer_with_drawal = $percent_robus_distri_tn->percent_with_drawal * $commission_valeur;

                            $conver_drawal_amount =  $valuer_with_drawal * $walletUser->wallet->currencie->value;


                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Valeur receive

                            // Update decrement in compte Client
                            $wallet_user->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Commission Under_distributer
                            if ($admin_civ->count() > 0) {

                                $nmbre_admin = $admin_civ->count();

                                $valeur_single = $conver_drawal_amount / $nmbre_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => $verify_code->id,
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

                            $verify_code->update([
                                'commissions' => DB::raw('commissions+' . $valuer_with_drawal),
                            ]);

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            if ($walletUser->country->code == 'CI') {

                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount . $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at .
                                    'votre demande est en-cours de validation ' .
                                    'Robus vous remercie';
                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notify And User an Admins
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        } else {

                            // If is not Distribiter or s-distributer

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);


                            // Send in compte Client
                            $wallet_user->update([
                                'reload_by' =>  Auth::guard('web')->user()->firstname .
                                    ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            if ($walletUser->country->code == 'CI') {
                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount .
                                    $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at . ' votre demande est en-cours de validation ' .
                                    'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notifications
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Demande de transfert de ' .
                                ' ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        }
                    } elseif ($walletUser->country->code == 'FR') {
                        // If person use reaload acompte is d
                        if ($walletUser->is_under_distributer == 1) {


                            // Valur off commission
                            $commission_valeur = $percent_robus_distri_fr->percent * $verify_code->amount;

                            // 30% with drawal
                            $valuer_with_drawal = $percent_robus_distri_fr->percent_with_drawal * $commission_valeur;

                            $conver_drawal_amount =  $valuer_with_drawal * $walletUser->wallet->currencie->value;

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Valeur receive

                            // Update decrement in compte Client
                            $wallet_user->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Commission Under_distributer
                            if ($admin_civ->count() > 0) {

                                $nmbre_admin = $admin_civ->count();

                                $valeur_single = $conver_drawal_amount / $nmbre_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => $verify_code->id,
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

                            $verify_code->update([
                                'commissions' => DB::raw('commissions+' . $valuer_with_drawal),
                            ]);

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            if ($walletUser->country->code == 'CI') {

                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount . $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at .
                                    'votre demande est en-cours de validation ' .
                                    'Robus vous remercie';
                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notify And User an Admins
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        } else {

                            // If is not Distribiter or s-distributer

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);


                            // Send in compte Client
                            $wallet_user->update([
                                'reload_by' =>  Auth::guard('web')->user()->firstname .
                                    ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            if ($walletUser->country->code == 'CI') {
                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount .
                                    $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at . ' votre demande est en-cours de validation ' .
                                    'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notifications
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Demande de transfert de ' .
                                ' ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        }
                    } elseif ($walletUser->country->code == 'CI') {
                        // If person use reaload acompte is d
                        if ($walletUser->is_under_distributer == 1) {

                            // Valur off commission
                            $commission_valeur =  $percent_robus_distri_ci->percent * $verify_code->amount;

                            // 30% with drawal
                            $valuer_with_drawal = $percent_robus_distri_ci->percent_with_drawal * $commission_valeur;

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Valeur receive

                            // Update decrement in compte Client
                            $wallet_user->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Commission Under_distributer

                            // Commission Under_distributer
                            if ($admin_civ->count() > 0) {

                                $nmbre_admin = $admin_civ->count();

                                $valeur_single = $valuer_with_drawal / $nmbre_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => $verify_code->id,
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

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            $verify_code->update([
                                'commissions' => DB::raw('commissions+' . $valuer_with_drawal),
                            ]);

                            if ($walletUser->country->code == 'CI') {

                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount . $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at .
                                    'Robus vous remercie';
                                $encode = substr($wallet_user->sidUser->phone, -10);
                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notify And User an Admins
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        } else {

                            // If is not Distribiter or s-distributer

                            $verify_code->update([
                                'type_transaction' => 'Retrait',
                                'status' => 'SUCCESS',
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);


                            // Send in compte Client
                            $wallet_user->update([
                                'reload_by' =>  Auth::guard('web')->user()->firstname .
                                    ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $verify_code->amount),
                            ]);

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $verify_code->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
                                'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $verify_code->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $verify_code->amount,
                                'fee_amount' => 0,
                                'received_amount' => $verify_code->amount,
                                'status' => $verify_code->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $verify_code->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $verify_code->description,
                                'status' => $verify_code->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  .  $verify_code->amount),
                            ]);

                            if ($walletUser->country->code == 'CI') {
                                $message = 'Vous avez effectué un retrait de ' .
                                    $verify_code->amount .
                                    $walletUser->wallet->currencie->code .
                                    ' ' .
                                    'par ' . $verify_code->provider->name .
                                    ' a ' . $verify_code->created_at . ' votre demande est en-cours de validation ' .
                                    'Robus vous remercie';

                                $encode = substr($verify_code->wallet->sidUser->phone, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            // Notifications
                            $msg_title = 'Transactions';
                            $msg = 'Demande de transfert est en cours de validation ';
                            $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                            $notifs = $walletUser->unreadNotifications;
                            $wallet_user->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $wallet_user->sidUser->unreadNotifications;
                            event(new CreateWalletTransfersEvent($verify_code));
                            Notification::send($admins, new AdminNotification('Demande de transfert de ' .
                                ' ' . $verify_code->wallet->sidUser->firstname));
                            $admin = SidUser::where('status', true)
                                ->where('is_admin', true)
                                ->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($verify_code);
                            }
                        }
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

        $check = WalletPayment::whereId($this->paiementId)->first();
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_payment)->first();
        $offer = Offer::whereId($this->offer_paiement)->first();
        $admins = $this->getAdminValeurProperty();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        // Verify solde of User Auth
        $valeur_ceiling = $walletUser->silver_ceiling;

        try {
            if (!$check) {

                if ($walletUser->country->code == 'CI') {

                    //Provider CIE  
                    $percent_robus = PaymentCost::whereId(2)->first();

                    if ($this->provider_payment == 8) {

                        if ($this->facture_choix_type == 1) {

                            $this->validate(
                                [
                                    'paiement_id_invoice_pre' => 'required',
                                    'paiement_name_cie' => 'required',
                                    'paiement_amount_total' => 'required',
                                ],
                                [
                                    'paiement_amount_total.required' =>
                                    'Votre montant est obligatoire',
                                    'paiement_name_cie.required' =>
                                    'Nom  est obligatoire',
                                    'paiement_id_invoice_pre.required' =>
                                    'Numero  est obligatoire',
                                ]
                            );
                        } else {

                            $this->validate(
                                [
                                    'paiement_name_cie' => 'required',
                                    'paiement_id_invoice' => 'required',
                                    'paiement_amount_total' => 'required',
                                ],
                                [
                                    'paiement_amount_total.required' =>
                                    'Votre montant est obligatoire',
                                    'paiement_name_cie.required' =>
                                    'Nom  est obligatoire',
                                    'paiement_id_invoice.required' =>
                                    'Numero  est obligatoire',
                                ]
                            );
                        }


                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                        } else {

                            // Verify amount acompte with comission
                            $valAmount = $this->paiement_amount_total +  $percent_robus->amount_auther;

                            if ($valAmount > $walletUser->wallet->amount) {

                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('error', 'Verifier votre montant  svp !');
                            } else {

                                $amount_solde =  $walletUser->wallet->amount -  $valAmount;

                                $walletPayment = WalletPayment::create([
                                    'wallet_id' => $walletUser->wallet->id,
                                    'amount' => $this->paiement_amount_total,
                                    'description' => 'Paiement de facture CIE',
                                    'payment_cost_id' => 2,
                                    'number_decoder' => $this->paiement_decoder_canal ?? null,
                                    'id_invoice' => $this->facture_choix_type == 1 ? $this->paiement_id_invoice_pre : $this->paiement_id_invoice,
                                    'number_contract' =>  $this->paiement_num_contrat ?? null,
                                    'phone' => $this->pre_phone_invoice ?? null,
                                    'id_invoice_pre' =>  $this->pre_id_invoice ?? null,
                                    'name' => $this->paiement_name_cie ?? null,
                                    'created_by' => Auth::guard('web')->user()->id,
                                    'provider_id' => $provider->id,
                                    'number_phone' => $this->paiement_phone_canal ?? null,
                                    'offer_id' => $offer->id ?? null,
                                    'costs_amount' => $percent_robus->amount_auther,
                                    'status' => 'PENDING',
                                    'paiementChoix_phone' =>  $this->paiementChoix_phone ?? null,
                                    'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                    'factureChoix_type' => $this->facture_choix_type ?? null,
                                    'amount_solde'  =>   $amount_solde ?? null
                                ]);


                                // Commision of paiement
                                $commission =  $walletPayment->amount +  $percent_robus->amount_auther;

                                $walletUser->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount-' . $commission),
                                ]);



                                //  Send valeur in Admin

                                if ($walletUser->is_under_distributer == 1) {

                                    $walletPayment->update(['is_send' => 1,]);

                                    // Commission admin
                                    $admin_valeur =  $percent_robus->amount_auther -  $percent_robus->comission_auther;

                                    // Commission distributer
                                    $valuer_deposit =  $percent_robus->comission_auther;


                                    $walletPayment->update([
                                        'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                    ]);

                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $admin_valeur / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' => null,
                                                'wallet_payment_id' => $walletPayment->id,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                } else {


                                    if ($walletUser->country->code == 'CI') {

                                        $message_pro = 'Votre code de la facture prepéyé est ' . $walletPayment->id_invoice_pre;

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletPayment->phone&from=ROBUS&sms=$message_pro");

                                        $message = 'Vous avez effectué un paiement de ' . $walletPayment->amount .
                                            $walletUser->wallet->currencie->code .
                                            ' ' .
                                            'par ' .
                                            $walletPayment->provider->name .
                                            ' le ' .
                                            $walletPayment->created_at .
                                            ' votre demande est en cours de validation ' .
                                            'Robus vous remercie';

                                        $encode = substr(Auth::guard('web')->user()->phone, -10);

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                    }

                                    $walletPayment->update(['is_send' => 1,]);

                                    // Commission admin
                                    $admin_valeur =  $percent_robus->amount_auther;

                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $admin_valeur / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' => null,
                                                'wallet_payment_id' => $walletPayment->id,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                }

                                $msg_title = 'Paiement facture';
                                $msg = 'Demande de paiement est en cours de validation ';
                                $walletUser->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );
                                $notifs = $walletUser->unreadNotifications;
                                event(new CreateWalletPaiementEvent($walletPayment));
                                Notification::send(
                                    $admins,
                                    new AdminNotification('Demande de paiement de ' . ' ' . $walletPayment->wallet->sidUser->firstname)
                                );

                                if ($admins->count() > 0) {


                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;
                                        event(new AdminNotifEvent($notifs));
                                    }

                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('success', 'Paiement en-cours !');

                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newPaiement($walletPayment);
                                    }
                                } else {

                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('success', 'Paiement en-cours !');

                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newPaiement($walletPayment);
                                    }
                                }
                            }
                        }
                    } elseif ($this->provider_payment == 10) {
                        //   Provider SOCEDI

                        $this->validate(
                            [
                                'paiement_name_sodecie' => 'required',
                                'paiement_num_contrat' => 'required|max:9',
                                'paiement_amount_t_sodecie' => 'required',
                            ],
                            [
                                'paiement_amount_t_sodecie.required' =>
                                'Votre montant est obligatoire',
                                'paiement_name_sodecie.required' =>
                                'Nom  est obligatoire',
                                'paiement_num_contrat.required' =>
                                'Numero  est obligatoire',
                            ]
                        );


                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                        } else {


                            $valAmount = ((float) $this->paiement_amount_total +  (float) $percent_robus->amount_auther);

                            if ($valAmount > $walletUser->wallet->amount) {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit('error', 'Verifier votre montant  svp !');
                            } else {

                                $amount_solde =  $walletUser->wallet->amount -  $valAmount;

                                $walletPayment = WalletPayment::create([
                                    'wallet_id' => $walletUser->wallet->id,
                                    'amount' => $this->paiement_amount_t_sodecie ??  $this->paiement_amount_p_sodecie,
                                    'description' => 'Paiement de facture SODECI',
                                    'payment_cost_id' => 2,
                                    'number_decoder' => $this->paiement_decoder_canal ?? null,
                                    'id_invoice' => $this->paiement_id_invoice ?? null,
                                    'number_contract' => $this->paiement_num_contrat ?? null,
                                    'phone' => $this->pre_phone_invoice ?? null,
                                    'id_invoice_pre' => $this->pre_id_invoice ?? null,
                                    'name' => $this->paiement_name_sodecie ?? null,
                                    'created_by' => Auth::guard('web')->user()->id,
                                    'provider_id' => $provider->id,
                                    'costs_amount' => $percent_robus->amount_auther,
                                    'number_phone' => $this->paiement_phone_canal ?? null,
                                    'offer_id' => $offer->id ?? null,
                                    'status' => 'PENDING',
                                    'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                    'paiementChoix_type' =>  $this->paiementChoix_type ?? null,
                                    'factureChoix_type' => $this->facture_choix_type ?? null,
                                    'amount_solde'  =>   $amount_solde  ?? null
                                ]);

                                // Commision of paiement
                                $commission =  $walletPayment->amount +  $percent_robus->amount_auther;

                                $walletUser->wallet->update([
                                    'amount' => DB::raw('amount-' . $commission),
                                ]);


                                if ($walletUser->is_under_distributer == 1) {

                                    // Commission admin
                                    $admin_valeur =  $percent_robus->amount_auther -  $percent_robus->comission_auther;

                                    // Commission distributer
                                    $valuer_deposit =  $percent_robus->comission_auther;

                                    $walletPayment->update([
                                        'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                    ]);

                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $admin_valeur / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' => null,
                                                'wallet_payment_id' => $walletPayment->id,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                } else {

                                    if ($walletUser->country->code == 'CI') {

                                        $message_pro = 'Votre code de la facture prepéyé est ' . $walletPayment->id_invoice_pre;

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletPayment->phone&from=ROBUS&sms=$message_pro");

                                        $message = 'Vous avez effectué un paiement de ' . $walletPayment->amount .
                                            $walletUser->wallet->currencie->code .
                                            ' ' .
                                            'par ' .
                                            $walletPayment->provider->name .
                                            ' le ' .
                                            $walletPayment->created_at .
                                            ' votre demande est en cours de validation ' .
                                            'Robus vous remercie';

                                        $encode = substr(Auth::guard('web')->user()->phone, -10);

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                    }

                                    // Commission admin
                                    $admin_valeur =  $percent_robus->amount_auther;

                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $admin_valeur / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' => null,
                                                'wallet_payment_id' => $walletPayment->id,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                }

                                $msg_title = 'Paiement de facture';
                                $msg = 'Demande de paiement est en cours de validation ';
                                $walletUser->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );
                                $notifs = $walletUser->unreadNotifications;
                                event(new CreateWalletPaiementEvent($walletPayment));
                                Notification::send(
                                    $admins,
                                    new AdminNotification('Demande de paiement de ' . ' ' . $walletPayment->wallet->sidUser->firstname)
                                );

                                if ($admins->count() > 0) {


                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;
                                        event(new AdminNotifEvent($notifs));
                                    }

                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('success', 'Paiement en-cours !');


                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newPaiement($walletPayment);
                                    }
                                } else {


                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('success', 'Paiement en-cours !');


                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newPaiement($walletPayment);
                                    }
                                }
                            }
                        }
                    } elseif ($this->provider_payment == 7) {

                        // Provider CANAL

                        // Percent commission providers
                        $val_amount = $offer->amount + $percent_robus->amount_canal;

                        if ($this->paiementChoix_phone == 1) {

                            $this->validate(
                                [
                                    'paiement_phone_canal' => 'required',
                                    'offer_paiement' => 'required',
                                ],
                                [
                                    'offer_paiement.required' =>
                                    'Offre  est obligatoire',
                                    'paiement_phone_canal.required' =>
                                    'Numero  est obligatoire',
                                ]
                            );
                        } else {

                            $this->validate(
                                [
                                    'paiement_decoder_canal' => 'required',
                                    'offer_paiement' => 'required',
                                ],
                                [
                                    'offer_paiement.required' => 'Offre  est obligatoire',
                                    'paiement_decoder_canal.required' =>
                                    'Code est obligatoire',
                                ]
                            );
                        }


                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                        } else {


                            // Verify solde of User send transfers

                            if ($val_amount > $walletUser->wallet->amount) {

                                $this->resetPaiementField();
                                $this->emit('modal-hide-paiement');
                                $this->emit(
                                    'error',
                                    'Verifier votre montant  svp !'
                                );
                            } else {


                                $amount_solde =  $walletUser->wallet->amount -  $val_amount;

                                $walletPayment = WalletPayment::create([
                                    'wallet_id' => $walletUser->wallet->id,
                                    'amount' => $offer->amount ?? null,
                                    'description' => 'Paiement de facture CANAL',
                                    'payment_cost_id' => 2,
                                    'number_decoder' => $this->paiementChoix_phone == 2 ? $this->paiement_decoder_canal  : null,
                                    'id_invoice' => $this->paiement_id_invoice ?? null,
                                    'number_contract' =>  $this->paiement_num_contrat ?? null,
                                    'name' => $this->paiement_name_sodecie ?? null,
                                    'created_by' => Auth::guard('web')->user()->id,
                                    'provider_id' => $provider->id,
                                    'costs_amount' => $percent_robus->amount_canal,
                                    'number_phone' => $this->paiementChoix_phone == 1 ? $this->paiement_phone_canal : null,
                                    'offer_id' => $offer->id ?? null,
                                    'status' => 'PENDING',
                                    'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                    'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                    'factureChoix_type' => $this->facture_choix_type ?? null,
                                    'amount_solde'  =>   $amount_solde ?? null
                                ]);

                                // Commision percent an decrement a acompte
                                $commission = $walletPayment->amount +   $percent_robus->amount_canal;

                                $walletUser->wallet->update([
                                    'amount' => DB::raw('amount-' . $commission),
                                ]);


                                if ($walletUser->is_under_distributer == 1) {

                                    // Commission admin
                                    $admin_valeur =  $percent_robus->amount_canal -  $percent_robus->comission_canal;

                                    // Commission distributer
                                    $valuer_deposit =  $percent_robus->comission_canal;

                                    $walletPayment->update([
                                        'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                                    ]);

                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $admin_valeur / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' => null,
                                                'wallet_payment_id' => $walletPayment->id,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                } else {

                                    if ($walletUser->country->code == 'CI') {

                                        $message_pro = 'Votre code de la facture prepéyé est ' . $walletPayment->id_invoice_pre;

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletPayment->phone&from=ROBUS&sms=$message_pro");

                                        $message = 'Vous avez effectué un paiement de ' . $walletPayment->amount .
                                            $walletUser->wallet->currencie->code .
                                            ' ' .
                                            'par ' .
                                            $walletPayment->provider->name .
                                            ' le ' .
                                            $walletPayment->created_at .
                                            ' votre demande est en cours de validation ' .
                                            'Robus vous remercie';

                                        $encode = substr(Auth::guard('web')->user()->phone, -10);

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                    }

                                    // Commission admin
                                    $admin_valeur =  $percent_robus->amount_canal;

                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $admin_valeur / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' => null,
                                                'wallet_payment_id' => $walletPayment->id,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                }

                                $msg_title = 'Paiement de facture';
                                $msg = 'Demande de paiement est en cours de validation ';
                                $walletUser->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );
                                $notifs = $walletUser->unreadNotifications;
                                event(new CreateWalletPaiementEvent($walletPayment));
                                Notification::send(
                                    $admins,
                                    new AdminNotification(
                                        'Demande de paiement de ' .
                                            ' ' . $walletPayment->wallet->sidUser->firstname
                                    )
                                );
                                if ($admins->count() > 0) {


                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;
                                        event(new AdminNotifEvent($notifs));
                                    }

                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('success', 'Paiement en-cours !');
                                } else {


                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('success', 'Paiement en-cours !');

                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newPaiement($walletPayment);
                                    }
                                }
                            }
                        }
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

    // Add Reload card
    public function addReloadCard()
    {
        $check = WalletDeposit::whereId($this->reloadcardId)->first();
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_reloadcard)->first();
        // Verifier plafond days
        $valeur_ceiling = $walletUser->silver_ceiling;
        //  Verifier transfers Som days
        $admins = $this->getAdminValeurProperty();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        try {

            if (!$check) {


                $percent_robus = DepositCost::whereId(2)->first();

                if ($walletUser->country->code == 'CI') {


                    // Provider Bsic and Ecobank
                    if ($this->provider_reloadcard == 14 || $this->provider_reloadcard == 15) {
                        $this->validate(
                            [
                                'amount_reloadcard' => 'required',
                                'account_number_reload_card' => 'required',
                                'name_reloadcard' => 'required',
                            ],
                            [
                                'amount_reloadcard.required' =>
                                'Votre montant est obligatoire',
                                'name_reloadcard.required' =>
                                'Nom  est obligatoire',
                                'account_number_reload_card.required' =>
                                'Numero est obligatoire',
                            ]
                        );



                        if ($som_transfers >= $valeur_ceiling) {

                            $this->resetPaiementField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit(
                                'warning',
                                'Votre montant journier est atteint !'
                            );
                        } else {

                            if ($this->amount_reloadcard < 2000) {

                                $this->resetReloadCardField();
                                $this->emit('modal-hide-reloadCard');
                                $this->emit(
                                    'error',
                                    'Verifier le montant minimum est de 2000 !'
                                );
                            } else {

                                // Verify valeur amount max in Landing the ROBUS
                                $valeur_end = $percent_robus->levelDepositIvories->where('is_ivoires', true)->last();

                                if ($valeur_end->amount_end < $this->amount_inter) {
                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('info', 'Vous avez depasser le montant max !');
                                } else {

                                    foreach ($percent_robus->levelDepositIvories->where('is_ivoires', true) as $amount_user) {

                                        if ($this->amount_reloadcard >= $amount_user->amount_begin && $this->amount_reloadcard <= $amount_user->amount_end) {

                                            //  If amount is Null
                                            if ($amount_user->amount == null) {
                                                if ($walletUser->is_under_distributer == 1) {
                                                    $amount_valeur = $this->amount_reloadcard;
                                                } else {

                                                    $amount_valeur = $this->amount_reloadcard + ($amount_user->percent *  $this->amount_reloadcard);
                                                }

                                                $soldeWallet = $walletUser->wallet->amount;

                                                if ($amount_valeur > $soldeWallet) {

                                                    $this->resetReloadCardField();
                                                    $this->emit('modal-hide-reloadCard');
                                                    $this->emit(
                                                        'error',
                                                        'Verifier votre montant  svp ou le montant minimum !'
                                                    );
                                                } else {

                                                    $amount_solde =  $walletUser->wallet->amount -  $amount_valeur;

                                                    $walletReloadCard = WalletDeposit::create(
                                                        [
                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => $walletUser->wallet->id,
                                                            'amount' => $this->amount_reloadcard,
                                                            'description' => $this->reloadcard_description,
                                                            'num_carte' =>  $this->account_number_reload_card,
                                                            'name' => $this->name_reloadcard,
                                                            'deposit_cost_id' => 2,
                                                            'costs_amount' =>  $amount_user->percent  *  $this->amount_reloadcard,
                                                            'created_by' => Auth::guard('web')->user()->id,
                                                            'provider_id' => $provider->id,
                                                            'status' => 'PENDING',
                                                        ]
                                                    );

                                                    if ($walletUser->country->code == 'CI') {
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

                                                        $encode = substr(Auth::guard('web')->user()->phone, -10);

                                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                                    }

                                                    if ($walletUser->is_under_distributer == 1) {


                                                        $commission =  $walletReloadCard->amount;

                                                        $walletUser->wallet->update([
                                                            'reload_by' => Auth::guard('web')->user()->firstname .
                                                                ' ' . Auth::guard('web')->user()->lastname,
                                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        // Commission distributer
                                                        $valuer_deposit = $amount_user->comission_distributer *   $this->amount_reloadcard;



                                                        //  Send valeur in Admin
                                                        if ($admin_civ->count() > 0) {

                                                            $count_admin = $admin_civ->count();

                                                            $valeur_single = $valuer_deposit / $count_admin;

                                                            foreach ($admin_civ as $user) {
                                                                $admin_single = SidUser::find($user->id);

                                                                $valeur_deposit =  AdminsCom::create([
                                                                    'amount' =>   $valeur_single,
                                                                    'sid_user_id' =>   $admin_single->id,
                                                                    'wallet_transaction_id' => null,
                                                                    'wallet_bank_id' => null,
                                                                    'wallet_deposit_id' => $walletReloadCard->id,
                                                                    'wallet_inter_id' => null,
                                                                    'wallet_payment_id' => null,
                                                                    'wallet_phone_credit_id' => null,
                                                                    'wallet_reload_id' => null,
                                                                    'bank_card_id' => null,
                                                                    'description' => 'Paiement commission sous-distributer',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                                ]);
                                                            }
                                                        }


                                                        $walletReloadCard->update([
                                                            'commissions' => DB::raw('commissions+'  . $valeur_deposit->amount),

                                                        ]);



                                                        $msg_title = 'Rechargement de carte';
                                                        $msg = 'Demande de rechargement de carte est en cours de validation ';
                                                        $walletUser->notify(
                                                            new UserNotification([
                                                                'title' => $msg_title,
                                                                'message' => $msg,
                                                            ])
                                                        );
                                                        $notifs = $walletUser->unreadNotifications;
                                                        event(new CreateWalletReloadCardEvent($walletReloadCard));
                                                        Notification::send(
                                                            $admins,
                                                            new AdminNotification(
                                                                'Demande de rechargment de carte de ' .
                                                                    ' ' .
                                                                    $walletReloadCard->wallet
                                                                    ->sidUser->firstname
                                                            )
                                                        );

                                                        if ($admins->count() > 0) {


                                                            foreach ($admins  as $user) {

                                                                $admin_single = SidUser::find($user->id);

                                                                $notifs = $admin_single->unreadNotifications;
                                                                event(new AdminNotifEvent($notifs));
                                                            }


                                                            $this->resetReloadCardField();
                                                            $this->emit('modal-hide-reloadCard');
                                                            $this->emit(
                                                                'success',
                                                                'Rechargement en-cours !'
                                                            );

                                                            if ($walletUser->email != null) {
                                                                $notify = new MailController();
                                                                $notify->newReload(
                                                                    $walletReloadCard
                                                                );
                                                            }
                                                        } else {


                                                            $this->resetReloadCardField();
                                                            $this->emit('modal-hide-reloadCard');
                                                            $this->emit(
                                                                'success',
                                                                'Rechargement en-cours !'
                                                            );

                                                            if ($walletUser->email != null) {
                                                                $notify = new MailController();
                                                                $notify->newReload(
                                                                    $walletReloadCard
                                                                );
                                                            }
                                                        }
                                                    } else {

                                                        $commission =  $walletReloadCard->amount  + ($amount_user->percent *  $this->amount_reloadcard);

                                                        $walletUser->wallet->update([
                                                            'reload_by' => Auth::guard('web')->user()->firstname .
                                                                ' ' . Auth::guard('web')->user()->lastname,
                                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        // Admin Percent valuer 

                                                        $admin_percent =  $amount_user->admin_percent *  ($amount_user->percent *  $this->amount_reloadcard);


                                                        //  Send valeur in Admin
                                                        if ($admin_civ->count() > 0) {

                                                            $count_admin = $admin_civ->count();

                                                            $valeur_single = $admin_percent / $count_admin;

                                                            foreach ($admin_civ as $user) {
                                                                $admin_single = SidUser::find($user->id);
                                                                AdminsCom::create([
                                                                    'amount' =>   $valeur_single,
                                                                    'sid_user_id' =>   $admin_single->id,
                                                                    'wallet_transaction_id' => null,
                                                                    'wallet_bank_id' => null,
                                                                    'wallet_deposit_id' => $walletReloadCard->id,
                                                                    'wallet_inter_id' => null,
                                                                    'wallet_payment_id' => null,
                                                                    'wallet_phone_credit_id' => null,
                                                                    'wallet_reload_id' => null,
                                                                    'bank_card_id' => null,
                                                                    'description' => 'Commission',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                                ]);
                                                            }
                                                        }

                                                        $msg_title = 'Rechargement de carte';
                                                        $msg = 'Demande de rechargement de carte est en cours de validation ';
                                                        $walletUser->notify(
                                                            new UserNotification([
                                                                'title' => $msg_title,
                                                                'message' => $msg,
                                                            ])
                                                        );
                                                        $notifs = $walletUser->unreadNotifications;
                                                        event(new CreateWalletReloadCardEvent($walletReloadCard));
                                                        Notification::send(
                                                            $admins,
                                                            new AdminNotification(
                                                                'Demande de rechargement de carte de ' .
                                                                    ' ' .
                                                                    $walletReloadCard->wallet
                                                                    ->sidUser->firstname
                                                            )
                                                        );
                                                        if ($admins->count() > 0) {


                                                            foreach ($admins  as $user) {

                                                                $admin_single = SidUser::find($user->id);

                                                                $notifs = $admin_single->unreadNotifications;
                                                                event(new AdminNotifEvent($notifs));
                                                            }


                                                            $this->resetReloadCardField();
                                                            $this->emit('modal-hide-reloadCard');
                                                            $this->emit(
                                                                'success',
                                                                'Rechargement en-cours !'
                                                            );


                                                            if ($walletUser->email != null) {
                                                                $notify = new MailController();
                                                                $notify->newReload(
                                                                    $walletReloadCard
                                                                );
                                                            }
                                                        } else {


                                                            $this->resetReloadCardField();
                                                            $this->emit('modal-hide-reloadCard');
                                                            $this->emit(
                                                                'success',
                                                                'Rechargement en-cours !'
                                                            );

                                                            if ($walletUser->email != null) {
                                                                $notify = new MailController();
                                                                $notify->newReload(
                                                                    $walletReloadCard
                                                                );
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {

                                                if ($walletUser->is_under_distributer == 1) {
                                                    $amount_valeur = $this->amount_reloadcard;
                                                } else {

                                                    $amount_valeur = $this->amount_reloadcard + $amount_user->amount;
                                                }

                                                $soldeWallet = $walletUser->wallet->amount;


                                                if ($amount_valeur > $soldeWallet) {

                                                    $this->resetReloadCardField();
                                                    $this->emit('modal-hide-reloadCard');
                                                    $this->emit(
                                                        'error',
                                                        'Verifier votre montant  svp ou le montant minimum !'
                                                    );
                                                } else {

                                                    $amount_solde =  $walletUser->wallet->amount -  $amount_valeur;

                                                    $walletReloadCard = WalletDeposit::create(
                                                        [
                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => $walletUser->wallet->id,
                                                            'amount' => $this->amount_reloadcard,
                                                            'description' => $this->reloadcard_description,
                                                            'num_carte' =>  $this->account_number_reload_card,
                                                            'name' => $this->name_reloadcard,
                                                            'deposit_cost_id' => 2,
                                                            'costs_amount' =>  $amount_user->amount,
                                                            'created_by' => Auth::guard('web')->user()->id,
                                                            'provider_id' => $provider->id,
                                                            'status' => 'PENDING',
                                                        ]
                                                    );



                                                    if ($walletUser->country->code == 'CI') {
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

                                                        $encode = substr(Auth::guard('web')->user()->phone, -10);

                                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                                    }

                                                    if ($walletUser->is_under_distributer == 1) {

                                                        $commission =  $walletReloadCard->amount;

                                                        $walletUser->wallet->update([
                                                            'reload_by' => Auth::guard('web')->user()->firstname .
                                                                ' ' . Auth::guard('web')->user()->lastname,
                                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        // Commission distributer
                                                        $valuer_deposit =  $amount_user->comission_distributer;

                                                        //  Send valeur in Admin
                                                        if ($admin_civ->count() > 0) {

                                                            $count_admin = $admin_civ->count();

                                                            $valeur_single = $valuer_deposit / $count_admin;

                                                            foreach ($admin_civ as $user) {
                                                                $admin_single = SidUser::find($user->id);

                                                                $valeur_deposit =  AdminsCom::create([
                                                                    'amount' =>   $valeur_single,
                                                                    'sid_user_id' =>   $admin_single->id,
                                                                    'wallet_transaction_id' => null,
                                                                    'wallet_bank_id' => null,
                                                                    'wallet_deposit_id' => $walletReloadCard->id,
                                                                    'wallet_inter_id' => null,
                                                                    'wallet_payment_id' => null,
                                                                    'wallet_phone_credit_id' => null,
                                                                    'wallet_reload_id' => null,
                                                                    'bank_card_id' => null,
                                                                    'description' => 'Paiement commission sous-distributer',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                                ]);
                                                            }
                                                        }

                                                        $walletReloadCard->update([
                                                            'commissions' => DB::raw('commissions+'  . $valeur_deposit->amount),
                                                        ]);

                                                        $msg_title = 'Rechargement de carte';
                                                        $msg = 'Demande de rechargement de carte est en cours de validation ';
                                                        $walletUser->notify(
                                                            new UserNotification([
                                                                'title' => $msg_title,
                                                                'message' => $msg,
                                                            ])
                                                        );
                                                        $notifs = $walletUser->unreadNotifications;
                                                        event(new CreateWalletReloadCardEvent($walletReloadCard));
                                                        Notification::send(
                                                            $admins,
                                                            new AdminNotification(
                                                                'Demande de rechargement de carte de ' .
                                                                    ' ' .
                                                                    $walletReloadCard->wallet
                                                                    ->sidUser->firstname
                                                            )
                                                        );
                                                        if ($admins->count() > 0) {


                                                            foreach ($admins  as $user) {

                                                                $admin_single = SidUser::find($user->id);

                                                                $notifs = $admin_single->unreadNotifications;
                                                                event(new AdminNotifEvent($notifs));
                                                            }


                                                            $this->resetReloadCardField();
                                                            $this->emit('modal-hide-reloadCard');
                                                            $this->emit(
                                                                'success',
                                                                'Rechargement en-cours !'
                                                            );

                                                            if ($walletUser->email != null) {
                                                                $notify = new MailController();
                                                                $notify->newReload(
                                                                    $walletReloadCard
                                                                );
                                                            }
                                                        } else {


                                                            $this->resetReloadCardField();
                                                            $this->emit('modal-hide-reloadCard');
                                                            $this->emit(
                                                                'success',
                                                                'Rechargement en-cours !'
                                                            );

                                                            if ($walletUser->email != null) {
                                                                $notify = new MailController();
                                                                $notify->newReload(
                                                                    $walletReloadCard
                                                                );
                                                            }
                                                        }
                                                    } else {


                                                        $commission =  $walletReloadCard->amount + $amount_user->amount;

                                                        $walletUser->wallet->update([
                                                            'reload_by' => Auth::guard('web')->user()->firstname .
                                                                ' ' . Auth::guard('web')->user()->lastname,
                                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        // Commission admin
                                                        $admin_valeur =  $amount_user->amount;

                                                        //  Send valeur in Admin
                                                        if ($admin_civ->count() > 0) {

                                                            $count_admin = $admin_civ->count();

                                                            $valeur_single = $admin_valeur / $count_admin;

                                                            foreach ($admin_civ as $user) {
                                                                $admin_single = SidUser::find($user->id);
                                                                AdminsCom::create([
                                                                    'amount' =>   $valeur_single,
                                                                    'sid_user_id' =>   $admin_single->id,
                                                                    'wallet_transaction_id' => null,
                                                                    'wallet_bank_id' => null,
                                                                    'wallet_deposit_id' => $walletReloadCard->id,
                                                                    'wallet_inter_id' => null,
                                                                    'wallet_payment_id' => null,
                                                                    'wallet_phone_credit_id' => null,
                                                                    'wallet_reload_id' => null,
                                                                    'bank_card_id' => null,
                                                                    'description' => 'Commission',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                                ]);
                                                            }
                                                        }

                                                        $msg_title = 'Rechargement de carte';
                                                        $msg = 'Demande de rechargement de carte est en cours de validation ';
                                                        $walletUser->notify(
                                                            new UserNotification([
                                                                'title' => $msg_title,
                                                                'message' => $msg,
                                                            ])
                                                        );
                                                        $notifs = $walletUser->unreadNotifications;
                                                        event(new CreateWalletReloadCardEvent($walletReloadCard));
                                                        Notification::send(
                                                            $admins,
                                                            new AdminNotification(
                                                                'Demande de rechargement de carte de ' .
                                                                    ' ' .
                                                                    $walletReloadCard->wallet
                                                                    ->sidUser->firstname
                                                            )
                                                        );
                                                        if ($admins->count() > 0) {


                                                            foreach ($admins  as $user) {

                                                                $admin_single = SidUser::find($user->id);

                                                                $notifs = $admin_single->unreadNotifications;
                                                                event(new AdminNotifEvent($notifs));
                                                            }

                                                            $this->resetReloadCardField();
                                                            $this->emit('modal-hide-reloadCard');
                                                            $this->emit(
                                                                'success',
                                                                'Rechargement en-cours !'
                                                            );

                                                            if ($walletUser->email != null) {
                                                                $notify = new MailController();
                                                                $notify->newReload(
                                                                    $walletReloadCard
                                                                );
                                                            }
                                                        } else {


                                                            $this->resetReloadCardField();
                                                            $this->emit('modal-hide-reloadCard');
                                                            $this->emit(
                                                                'success',
                                                                'Rechargement en-cours !'
                                                            );

                                                            if ($walletUser->email != null) {
                                                                $notify = new MailController();
                                                                $notify->newReload(
                                                                    $walletReloadCard
                                                                );
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($this->provider_reloadcard == 13 ||  $this->provider_reloadcard == 16 || $this->provider_reloadcard == 17) {
                        // If provider  Bni ; Uba ; Uba
                        $this->validate(
                            [
                                'amount_reloadcard' => 'required',
                                'account_number_reload_card' => 'required',
                                'name_reloadcard' => 'required',
                            ],
                            [
                                'amount_reloadcard.required' => 'Votre montant est obligatoire',
                                'name_reloadcard.required' => 'Nom  est obligatoire',
                                'account_number_reload_card.required' => 'Numero est obligatoire',
                            ]
                        );


                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit(
                                'warning',
                                'Votre montant journier est atteint !'
                            );
                        } else {

                            if ($this->amount_reloadcard < 2000) {
                                $this->resetReloadCardField();
                                $this->emit('modal-hide-reloadCard');
                                $this->emit(
                                    'error',
                                    'Verifier le montant minimum est de 2000 !'
                                );
                            } else {

                                foreach ($percent_robus->levelDepositIvories->where('is_ivoires', true)  as $amount_user) {
                                    if ($this->amount_reloadcard >= $amount_user->amount_begin &&  $this->amount_reloadcard <= $amount_user->amount_end) {
                                        //  If amount is Null
                                        if ($amount_user->amount == null) {
                                            if ($walletUser->is_under_distributer == 1) {
                                                $amount_valeur = $this->amount_reloadcard;
                                            } else {

                                                $amount_valeur = $this->amount_reloadcard + ($amount_user->percent * $this->amount_reloadcard);
                                            }
                                            $soldeWallet = $walletUser->wallet->amount;


                                            if ($amount_valeur > $soldeWallet) {

                                                $this->resetReloadCardField();
                                                $this->emit('modal-hide-reloadCard');
                                                $this->emit(
                                                    'error',
                                                    'Verifier votre montant  svp ou le montant minimum !'
                                                );
                                            } else {

                                                $amount_solde =  $walletUser->wallet->amount -  $amount_valeur;

                                                $walletReloadCard = WalletDeposit::create(
                                                    [
                                                        'amount_solde'  => $amount_solde ?? null,
                                                        'wallet_id' => $walletUser->wallet->id,
                                                        'amount' => $this->amount_reloadcard,
                                                        'description' => $this->reloadcard_description,
                                                        'num_carte' =>  $this->account_number_reload_card,
                                                        'name' => $this->name_reloadcard,
                                                        'deposit_cost_id' => 2,
                                                        'costs_amount' =>  $amount_user->percent  *  $this->amount_reloadcard,
                                                        'created_by' => Auth::guard('web')->user()->id,
                                                        'provider_id' => $provider->id,
                                                        'status' => 'PENDING',
                                                    ]
                                                );



                                                if ($walletUser->country->code == 'CI') {
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

                                                    $encode = substr(Auth::guard('web')->user()->phone, -10);

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                                }

                                                if ($walletUser->is_under_distributer == 1) {

                                                    $commission =  $walletReloadCard->amount;

                                                    $walletUser->wallet->update([
                                                        'reload_by' => Auth::guard('web')->user()->firstname .
                                                            ' ' . Auth::guard('web')->user()->lastname,
                                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                                        'amount' => DB::raw('amount-' . $commission),
                                                    ]);


                                                    // Commission distributer
                                                    $valuer_deposit = $amount_user->comission_distributer *   $this->amount_reloadcard;

                                                    //  Send valeur in Admin
                                                    if ($admin_civ->count() > 0) {

                                                        $count_admin = $admin_civ->count();

                                                        $valeur_single = $valuer_deposit / $count_admin;

                                                        foreach ($admin_civ as $user) {
                                                            $admin_single = SidUser::find($user->id);

                                                            $valeur_deposit =  AdminsCom::create([
                                                                'amount' =>   $valeur_single,
                                                                'sid_user_id' =>   $admin_single->id,
                                                                'wallet_transaction_id' => null,
                                                                'wallet_bank_id' => null,
                                                                'wallet_deposit_id' => $walletReloadCard->id,
                                                                'wallet_inter_id' => null,
                                                                'wallet_payment_id' => null,
                                                                'wallet_phone_credit_id' => null,
                                                                'wallet_reload_id' => null,
                                                                'bank_card_id' => null,
                                                                'description' => 'Paiement commission sous-distributer',
                                                            ]);
                                                            $admin_single->wallet->update([
                                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                                'amount' => DB::raw('amount-' . $valeur_single),
                                                            ]);
                                                        }
                                                    }


                                                    $walletReloadCard->update([
                                                        'commissions' => DB::raw('commissions+'  . $valeur_deposit->amount),
                                                    ]);
                                                } else {


                                                    $commission =  $walletReloadCard->amount + ($amount_user->percent * $this->amount_reloadcard);

                                                    $walletUser->wallet->update([
                                                        'reload_by' => Auth::guard('web')->user()->firstname .
                                                            ' ' . Auth::guard('web')->user()->lastname,
                                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                                        'amount' => DB::raw('amount-' . $commission),
                                                    ]);


                                                    // Admin Percent valuer 

                                                    $admin_percent =  $amount_user->admin_percent *  $this->amount_reloadcard;

                                                    //  Send valeur in Admin
                                                    if ($admin_civ->count() > 0) {

                                                        $count_admin = $admin_civ->count();

                                                        $valeur_single = $admin_percent / $count_admin;

                                                        foreach ($admin_civ as $user) {
                                                            $admin_single = SidUser::find($user->id);
                                                            AdminsCom::create([
                                                                'amount' =>   $valeur_single,
                                                                'sid_user_id' =>   $admin_single->id,
                                                                'wallet_transaction_id' => null,
                                                                'wallet_bank_id' => null,
                                                                'wallet_deposit_id' => $walletReloadCard->id,
                                                                'wallet_inter_id' => null,
                                                                'wallet_payment_id' => null,
                                                                'wallet_phone_credit_id' => null,
                                                                'wallet_reload_id' => null,
                                                                'bank_card_id' => null,
                                                                'description' => 'Commission',
                                                            ]);
                                                            $admin_single->wallet->update([
                                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                                'amount' => DB::raw('amount+' . $valeur_single),
                                                            ]);
                                                        }
                                                    }
                                                }

                                                $msg_title = 'Rechargement de carte';
                                                $msg = 'Demande de rechargement de carte est en cours de validation ';
                                                $walletUser->notify(
                                                    new UserNotification([
                                                        'title' => $msg_title,
                                                        'message' => $msg,
                                                    ])
                                                );
                                                $notifs = $walletUser->unreadNotifications;
                                                event(new CreateWalletReloadCardEvent($walletReloadCard));
                                                Notification::send(
                                                    $admins,
                                                    new AdminNotification(
                                                        'Demande de rechargement de carte de ' .
                                                            ' ' .
                                                            $walletReloadCard->wallet
                                                            ->sidUser->firstname
                                                    )
                                                );
                                                if ($admins->count() > 0) {


                                                    foreach ($admins  as $user) {

                                                        $admin_single = SidUser::find($user->id);

                                                        $notifs = $admin_single->unreadNotifications;
                                                        event(new AdminNotifEvent($notifs));
                                                    }



                                                    $this->resetReloadCardField();
                                                    $this->emit('modal-hide-reloadCard');
                                                    $this->emit(
                                                        'success',
                                                        'Rechargement en-cours !'
                                                    );

                                                    if ($walletUser->email != null) {
                                                        $notify = new MailController();
                                                        $notify->newReload(
                                                            $walletReloadCard
                                                        );
                                                    }
                                                } else {


                                                    $this->resetReloadCardField();
                                                    $this->emit('modal-hide-reloadCard');
                                                    $this->emit(
                                                        'success',
                                                        'Rechargement en-cours !'
                                                    );

                                                    if ($walletUser->email != null) {
                                                        $notify = new MailController();
                                                        $notify->newReload(
                                                            $walletReloadCard
                                                        );
                                                    }
                                                }
                                            }
                                        } else {

                                            if ($walletUser->is_under_distributer == 1) {
                                                $amount_valeur = $this->amount_reloadcard;
                                            } else {

                                                $amount_valeur = $this->amount_reloadcard +  $amount_user->amount;
                                            }
                                            $soldeWallet = $walletUser->wallet->amount;


                                            if ($amount_valeur > $soldeWallet) {

                                                $this->resetReloadCardField();
                                                $this->emit('modal-hide-reloadCard');
                                                $this->emit(
                                                    'error',
                                                    'Verifier votre montant  svp ou le montant minimum !'
                                                );
                                            } else {

                                                $amount_solde =  $walletUser->wallet->amount -  $amount_valeur;

                                                $walletReloadCard = WalletDeposit::create(
                                                    [
                                                        'amount_solde'  => $amount_solde ?? null,
                                                        'wallet_id' => $walletUser->wallet->id,
                                                        'amount' => $this->amount_reloadcard,
                                                        'description' => $this->reloadcard_description,
                                                        'num_carte' =>  $this->account_number_reload_card,
                                                        'name' => $this->name_reloadcard,
                                                        'deposit_cost_id' => 2,
                                                        'costs_amount' =>  $amount_user->amount,
                                                        'created_by' => Auth::guard('web')->user()->id,
                                                        'provider_id' => $provider->id,
                                                        'status' => 'PENDING',
                                                    ]
                                                );



                                                if ($walletUser->country->code == 'CI') {
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

                                                    $encode = substr(Auth::guard('web')->user()->phone, -10);

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                                }

                                                if ($walletUser->is_under_distributer == 1) {

                                                    $commission =  $walletReloadCard->amount;

                                                    $walletUser->wallet->update([
                                                        'reload_by' => Auth::guard('web')->user()->firstname .
                                                            ' ' . Auth::guard('web')->user()->lastname,
                                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                                        'amount' => DB::raw('amount-' . $commission),
                                                    ]);

                                                    // Commission distributer
                                                    $valuer_deposit = $amount_user->comission_distributer;

                                                    //  Send valeur in Admin
                                                    if ($admin_civ->count() > 0) {

                                                        $count_admin = $admin_civ->count();

                                                        $valeur_single = $valuer_deposit / $count_admin;

                                                        foreach ($admin_civ as $user) {
                                                            $admin_single = SidUser::find($user->id);

                                                            $valeur_deposit =  AdminsCom::create([
                                                                'amount' =>   $valeur_single,
                                                                'sid_user_id' =>   $admin_single->id,
                                                                'wallet_transaction_id' => null,
                                                                'wallet_bank_id' => null,
                                                                'wallet_deposit_id' => $walletReloadCard->id,
                                                                'wallet_inter_id' => null,
                                                                'wallet_payment_id' => null,
                                                                'wallet_phone_credit_id' => null,
                                                                'wallet_reload_id' => null,
                                                                'description' => 'Paiement commission sous-distributer',
                                                            ]);
                                                            $admin_single->wallet->update([
                                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                                'amount' => DB::raw('amount-' . $valeur_single),
                                                            ]);
                                                        }
                                                    }


                                                    $walletReloadCard->update([
                                                        'commissions' => DB::raw('commissions+'  . $valeur_deposit->amount),

                                                    ]);
                                                } else {

                                                    $commission =  $walletReloadCard->amount +  $amount_user->amount;

                                                    $walletUser->wallet->update([
                                                        'reload_by' => Auth::guard('web')->user()->firstname .
                                                            ' ' . Auth::guard('web')->user()->lastname,
                                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                                        'amount' => DB::raw('amount-' . $commission),
                                                    ]);


                                                    // Commission distributer
                                                    $valuer_deposit =  $amount_user->amount;

                                                    //  Send valeur in Admin
                                                    if ($admin_civ->count() > 0) {

                                                        $count_admin = $admin_civ->count();

                                                        $valeur_single = $valuer_deposit / $count_admin;

                                                        foreach ($admin_civ as $user) {
                                                            $admin_single = SidUser::find($user->id);
                                                            AdminsCom::create([
                                                                'amount' =>   $valeur_single,
                                                                'sid_user_id' =>   $admin_single->id,
                                                                'wallet_transaction_id' => null,
                                                                'wallet_bank_id' => null,
                                                                'wallet_deposit_id' => $walletReloadCard->id,
                                                                'wallet_inter_id' => null,
                                                                'wallet_payment_id' => null,
                                                                'wallet_phone_credit_id' => null,
                                                                'wallet_reload_id' => null,
                                                                'bank_card_id' => null,
                                                                'description' => 'Commission',
                                                            ]);
                                                            $admin_single->wallet->update([
                                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                                'amount' => DB::raw('amount+' . $valeur_single),
                                                            ]);
                                                        }
                                                    }
                                                }

                                                $msg_title = 'Rechargement de carte';
                                                $msg = 'Demande de rechargement de carte est en cours de validation ';
                                                $walletUser->notify(
                                                    new UserNotification([
                                                        'title' => $msg_title,
                                                        'message' => $msg,
                                                    ])
                                                );
                                                $notifs = $walletUser->unreadNotifications;
                                                event(new CreateWalletReloadCardEvent($walletReloadCard));
                                                Notification::send(
                                                    $admins,
                                                    new AdminNotification(
                                                        'Demande de rechargement de carte de ' .
                                                            ' ' .
                                                            $walletReloadCard->wallet
                                                            ->sidUser->firstname
                                                    )
                                                );
                                                if ($admins->count() > 0) {


                                                    foreach ($admins  as $user) {

                                                        $admin_single = SidUser::find($user->id);

                                                        $notifs = $admin_single->unreadNotifications;
                                                        event(new AdminNotifEvent($notifs));
                                                    }

                                                    $this->resetReloadCardField();
                                                    $this->emit('modal-hide-reloadCard');
                                                    $this->emit(
                                                        'success',
                                                        'Rechargement en-cours !'
                                                    );

                                                    if ($walletUser->email != null) {
                                                        $notify = new MailController();
                                                        $notify->newReload(
                                                            $walletReloadCard
                                                        );
                                                    }
                                                } else {

                                                    $this->resetReloadCardField();
                                                    $this->emit('modal-hide-reloadCard');
                                                    $this->emit(
                                                        'success',
                                                        'Rechargement en-cours !'
                                                    );

                                                    if ($walletUser->email != null) {
                                                        $notify = new MailController();
                                                        $notify->newReload(
                                                            $walletReloadCard
                                                        );
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $this->emit('error', 'Aucune selection !');
                    }
                } else {
                    $this->emit('error', 'Rechargement que en CI !');
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

        $check = WalletPhoneCredit::whereId($this->phoneCreditId)->first();
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_phone_credit)->first();
        // Verifier plafond days
        $valeur_ceiling = $walletUser->silver_ceiling;
        //  Verifier transfers Som days
        $admins = $this->getAdminValeurProperty();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        try {

            if (!$check) {

                // Providers MTN , MOOV , ORANGE
                if (
                    $this->provider_phone_credit == 18 ||
                    $this->provider_phone_credit == 19 ||
                    $this->provider_phone_credit == 20
                ) {

                    $percent_robus = PhoneCreditCost::whereId(1)->first();

                    if ($walletUser->country->code == 'CI') {

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

                        $encode = substr($this->number_phone_credit, 0, 2);

                        if ($this->provider_phone_credit == 18) {

                            //   Mtn
                            if ($encode != 05) {
                                return $this->errorNumberPhoneValidated();
                            }
                        } elseif ($this->provider_phone_credit == 19) {
                            // Orange
                            if ($encode != 07) {
                                return $this->errorNumberPhoneValidated();
                            }
                        } else {

                            // Moov
                            if ($encode != 01) {
                                return $this->errorNumberPhoneValidated();
                            }
                        }

                        $percent_robus = PhoneCreditCost::whereStatus(true)->first();

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-phoneCredit');
                            $this->emit(
                                'warning',
                                'Votre montant journier est atteint !'
                            );
                        } else {

                            $val_amount =  $this->amount_phone_credit + $percent_robus->percent * $this->amount_phone_credit;

                            if ($val_amount > $walletUser->wallet->amount) {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit(
                                    'error',
                                    'Verifier votre montant  svp !'
                                );
                            } else {

                                $amount_solde =  $walletUser->wallet->amount -  $val_amount;

                                $walletPhone = WalletPhoneCredit::create([
                                    'wallet_id' => $walletUser->wallet->id,
                                    'amount' => $this->amount_phone_credit,
                                    'description' => 'Demande de credit',
                                    'phone_credit_cost_id' => 1,
                                    'created_by' => Auth::guard('web')->user()->id,
                                    'provider_id' => $provider->id,
                                    'costs_amount' => $percent_robus->percent  *  $this->amount_phone_credit,
                                    'phone' => Auth::guard('web')->user()->country->indecative .  $this->number_phone_credit,
                                    'status' => 'PENDING',
                                    'amount_solde'  => $amount_solde ?? null
                                ]);

                                $commission =  $walletPhone->amount + ($walletPhone->amount *  $percent_robus->percent);

                                $walletUser->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount-' . $commission),
                                ]);

                                $admin_valeur = $walletPhone->amount *  $percent_robus->percent;

                                if ($admin_civ->count() > 0) {

                                    $nmbre_admin = $admin_civ->count();

                                    $valeur_single = $admin_valeur / $nmbre_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);

                                        AdminsCom::create([
                                            'amount' =>   $valeur_single,
                                            'sid_user_id' =>   $admin_single->id,
                                            'wallet_transaction_id' => null,
                                            'wallet_bank_id' => null,
                                            'wallet_deposit_id' => null,
                                            'wallet_inter_id' => null,
                                            'wallet_payment_id' => null,
                                            'wallet_phone_credit_id' =>  $walletPhone->id,
                                            'wallet_reload_id' => null,
                                            'bank_card_id' => null,
                                            'description' => 'Commission',
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname .
                                                ' ' .
                                                Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                                if ($walletUser->country->code == 'CI') {

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

                                    $encode = substr(Auth::guard('web')->user()->phone, -10);

                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                }

                                $msg_title = 'Credit téléphonique';
                                $msg = 'Deemande de paiement de credit  est en cours de validation ';
                                $walletUser->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );
                                $notifs = $walletUser->unreadNotifications;
                                event(new CreateWalletPhoneCreditEvent($walletPhone));
                                Notification::send(
                                    $admins,
                                    new AdminNotification(
                                        'Demande de paiement de credit de ' .
                                            ' ' .
                                            $walletPhone->wallet->sidUser->firstname
                                    )
                                );

                                if ($admins->count() > 0) {


                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;
                                        event(new AdminNotifEvent($notifs));
                                    }


                                    $this->resetPhoneCreditField();
                                    $this->emit('modal-hide-phoneCredit');
                                    $this->emit('success', 'Rechargement effectué !');

                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newPhoneCredit($walletPhone);
                                    }
                                } else {


                                    event(new AdminNotifEvent($notifs));
                                    $this->resetPhoneCreditField();
                                    $this->emit('modal-hide-phoneCredit');
                                    $this->emit('success', 'Rechargement effectué !');
                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newPhoneCredit($walletPhone);
                                    }
                                }
                            }
                        }
                    } else {


                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-phoneCredit');
                            $this->emit(
                                'warning',
                                'Votre montant journier est atteint !'
                            );
                        } else {

                            $val_amount = $this->amount_phone_credit +  $percent_robus->percent *  $this->amount_phone_credit;

                            if ($val_amount > $walletUser->wallet->amount) {
                                $this->resetPaiementField();
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit(
                                    'error',
                                    'Verifier votre montant  svp !'
                                );
                            } else {

                                $amount_solde =  $walletUser->wallet->amount -  $val_amount;

                                $walletPhone = WalletPhoneCredit::create([

                                    'wallet_id' => $walletUser->wallet->id,
                                    'amount' => $this->amount_phone_credit,
                                    'description' => 'Demande de credit',
                                    'phone_credit_cost_id' => 1,
                                    'created_by' => Auth::guard('web')->user()->id,
                                    'provider_id' => $provider->id,
                                    'costs_amount' => $percent_robus->percent  *  $this->amount_phone_credit,
                                    'phone' => Auth::guard('web')->user()->country->indecative .  $this->number_phone_credit,
                                    'status' => 'PENDING',
                                    'amount_solde'  => $amount_solde ?? null

                                ]);

                                $commission =  $walletPhone->amount + ($walletPhone->amount *  $percent_robus->percent);

                                $walletUser->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount-' . $commission),
                                ]);

                                $admin_valeur = $walletPhone->amount *  $percent_robus->percent;

                                if ($admin_civ->count() > 0) {

                                    $nmbre_admin = $admin_civ->count();

                                    $valeur_single = $admin_valeur / $nmbre_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);

                                        AdminsCom::create([
                                            'amount' =>   $valeur_single,
                                            'sid_user_id' =>   $admin_single->id,
                                            'wallet_transaction_id' => null,
                                            'wallet_bank_id' => null,
                                            'wallet_deposit_id' => null,
                                            'wallet_inter_id' => null,
                                            'wallet_payment_id' => null,
                                            'wallet_phone_credit_id' =>  $walletPhone->id,
                                            'wallet_reload_id' => null,
                                            'bank_card_id' => null,
                                            'description' => 'Commission',
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname .
                                                ' ' .
                                                Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                                if ($walletUser->country->code == 'CI') {

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

                                    $encode = substr(Auth::guard('web')->user()->phone, -10);

                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                }

                                $msg_title = 'Credit téléphonique';
                                $msg = 'Demande de paiement de credit est en cours de validation ';
                                $walletUser->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );
                                $notifs = $walletUser->unreadNotifications;
                                event(new CreateWalletPhoneCreditEvent($walletPhone));
                                Notification::send(
                                    $admins,
                                    new AdminNotification(
                                        'Demande de paiement de credit de ' .
                                            ' ' . $walletPhone->wallet->sidUser->firstname
                                    )
                                );
                                if ($admins->count() > 0) {


                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;
                                        event(new AdminNotifEvent($notifs));
                                    }


                                    $this->resetPhoneCreditField();
                                    $this->emit('modal-hide-phoneCredit');
                                    $this->emit('success', 'Rechargement effectué !');

                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newPhoneCredit($walletPhone);
                                    }
                                } else {


                                    $this->resetPhoneCreditField();
                                    $this->emit('modal-hide-phoneCredit');
                                    $this->emit('success', 'Rechargement effectué !');
                                    if ($walletUser->email != null) {
                                        $notify = new MailController();
                                        $notify->newPhoneCredit($walletPhone);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $this->emit('error', 'Selectionnez une demande !');
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

    // Edit Reload Card
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

    public function editXbet($id, $state)
    {
        $check = WalletXbet::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->xbet_id = $id;
        $this->provider_xbet_edit = $provider->id;
        $this->provider_name_xbet_edit = $provider->name;
        $this->code_xbet_edit = $check->code_drawal;
        $this->number_xbet_edit = $check->num_acompte ?? null;
        $this->fullname_xbet_edit = $check->full_name ?? null;
        $this->amount_xbet_edit = $check->amount ?? null;
        $this->xbetDescription = $check->description ?? null;
        $this->status_xbet_edit = null;
        $this->choose_xbet_edit = $check->choose_type_transaction ?? null;
        $this->countrie_xbet_edit = $check->countrie ?? null;
        $this->citie_xbet_edit = $check->citie ?? null;
        $this->devise_user =   $check->wallet->currencie->code;
        $this->emit($state);
        $this->emit('menu-xbet-active');
    }

    public function editWave($id, $state)
    {
        $check = WalletWave::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->wave_id = $id;
        $this->provider_wave_edit = $provider->id;
        $this->provider_name_wave_edit = $provider->name;
        $this->number_wave_edit = $check->phone ?? null;
        $this->fullname_wave_edit = $check->full_name ?? null;
        $this->amount_wave_edit = $check->amount ?? null;
        $this->waveDescription = $check->description ?? null;
        $this->status_wave_edit = null;
        $this->countrie_wave_edit = $check->countrie ?? null;
        $this->citie_wave_edit = $check->citie ?? null;
        $this->devise_user =   $check->wallet->currencie->code;
        $this->emit($state);
        $this->emit('menu-wave-active');
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

    // Update Phone

    public function updatePhoneCredit()
    {
        $check = WalletPhoneCredit::whereId($this->phoneCreditId)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = Auth::guard('web')->user();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $percent_robus = PhoneCreditCost::whereId(1)->first();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

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

                                $paymentPhoneCredit = PaymentPhoneCredit::create([

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


                                InvoicePhoneCredit::create([
                                    'title' => 'Achat de credit telephonique',
                                    'wallet_phone_credit_id' => $check->id,
                                    'payment_credit_id' => $paymentPhoneCredit->id,
                                    'company_id' => Auth::guard('web')->user()->company->id,
                                    'description' => $check->description ?? null,
                                    'status' => $check->status,
                                    'created_by' => Auth::guard('web')->user()->id,
                                ]);

                                $commission_transaction = $check->amount;

                                if (Auth::guard('web')->user()->is_distributer == 1) {


                                    Auth::guard('web')->user()->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname .
                                            ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $commission_transaction),
                                    ]);

                                    // Send valeur historique distributer
                                    $valeur_decrement = $check->adminCom->update([
                                        // 'amount' => $valeur_single,
                                        'amount_distributer' => $commission_transaction,
                                        'sid_user_id' => Auth::guard('web')->user()->id,
                                        'description_distributer' => 'Validation distributeur',
                                    ]);
                                } else {

                                    Auth::guard('web')->user()->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname .
                                            ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $commission_transaction),
                                    ]);
                                }

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                                if ($check->wallet->sidUser->is_under_distributer == 1) {

                                    $msg_title = 'Credit téléphonique';
                                    $msg = 'Demande de paiement de crédit a été validé';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    // event(new UpdatePhoneCreditEvent($check));
                                    // event(new UserNotifEvent($notifs));
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de paiement de credit validée'
                                        )
                                    );

                                    $this->resetPhoneCreditField();
                                    $this->emit('modal-hide-phoneCredit-validated');
                                    $this->emit('modal-hide-phoneCredit');
                                    $this->emit('success', 'Achat de credit accepté !');

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }

                                        $notify = new MailController;
                                        $notify->newPhoneCredit($check);
                                    } else {


                                        $notify = new MailController;
                                        $notify->newPhoneCredit($check);
                                    }
                                } else {

                                    if ($check->wallet->sidUser->country->code == 'CI') {

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

                                        $encode = substr($check->wallet->sidUser->phone, -10);

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");

                                        $msg_title = 'Credit téléphonique';
                                        $msg = 'Demande de paiement de crédit a été validé';
                                        $check->wallet->sidUser->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = $check->wallet->sidUser->unreadNotifications;
                                        //    event(new UpdatePhoneCreditEvent($check));
                                        //    event(new UserNotifEvent($notifs));
                                        Notification::send(
                                            $admins,
                                            new AdminNotification(
                                                'Demande de paiement de crédit validé'
                                            )
                                        );
                                        $this->resetPhoneCreditField();
                                        $this->emit('modal-hide-phoneCredit-validated');
                                        $this->emit('modal-hide-phoneCredit');
                                        $this->emit('success', 'Achat de credit effectué !');
                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            $notify = new MailController;
                                            $notify->newPhoneCredit($check);
                                        } else {



                                            $notify = new MailController;
                                            $notify->newPhoneCredit($check);
                                        }
                                    } else {
                                        $msg_title = 'Credit téléphonique';
                                        $msg = 'Demande de paiement de crédit a été validé';
                                        $check->wallet->sidUser->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = $check->wallet->sidUser->unreadNotifications;
                                        //    event(new UpdatePhoneCreditEvent($check));
                                        //    event(new UserNotifEvent($notifs));
                                        Notification::send(
                                            $admins,
                                            new AdminNotification(
                                                'Demande de paiement de crédit validé'
                                            )
                                        );

                                        $this->resetPhoneCreditField();
                                        $this->emit('modal-hide-phoneCredit-validated');
                                        $this->emit('modal-hide-phoneCredit');
                                        $this->emit('success', 'Achat de credit accepté !');

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            $notify = new MailController;
                                            $notify->newPhoneCredit($check);
                                        } else {


                                            $notify = new MailController;
                                            $notify->newPhoneCredit($check);
                                        }
                                    }
                                }
                            } else {

                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('error', 'Pas vous le valideur !!');
                            }
                        } elseif ($check->status == 'ERROR') {

                            if ($check->updated_by == Auth::guard('web')->user()->id) {

                                PaymentPhoneCredit::create([
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

                                $commission = $check->amount + $check->costs_amount;

                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                $wallet_received =  $check->amount_solde;

                                $amount_solde = (float)$wallet_received  +   (float)$commission;

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                    'amount_solde' => $amount_solde,
                                ]);
                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   0,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Remboursement de la commission client',
                                            'status'  => "ERROR",
                                        ]);
                                        // $admin_single->wallet->update([
                                        //     'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        //     'sid_user_relod' => Auth::guard('web')->user()->id,
                                        //     'amount' => DB::raw('amount-' . $valeur_single),
                                        // ]);
                                    }
                                }

                                if ($check->wallet->sidUser->is_under_distributer == 1) {
                                    $msg_title = 'Credit téléphonique';
                                    $msg = 'Demande de paiement de crédit a été refusé';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );

                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    // event(new UpdatePhoneCreditEvent($check));
                                    // event(new UserNotifEvent($notifs));
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de paiement de crédit est refusée'
                                        )
                                    );

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }


                                        $notify = new MailController;
                                        $notify->newPhoneCredit($check);
                                    } else {

                                        $notify = new MailController;
                                        $notify->newPhoneCredit($check);
                                    }
                                } else {

                                    if ($check->wallet->sidUser->country->code == 'CI') {

                                        $message =
                                            'Votre demande de crédit de ' .
                                            $check->amount .
                                            $check->wallet->currencie->code .
                                            ' ' .
                                            'par ' .
                                            $check->provider->name .
                                            ' le ' .
                                            $check->updated_at .
                                            ' a été refusé ' .
                                            'Robus vous remercie';


                                        $encode = substr($check->wallet->sidUser->phone, -10);

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");

                                        $msg_title = 'Credit téléphonique';
                                        $msg = 'Demande de paiement de crédit a été refusée';
                                        $check->wallet->sidUser->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = $check->wallet->sidUser->unreadNotifications;
                                        // event(new UpdatePhoneCreditEvent($check));
                                        // event(new UserNotifEvent($notifs));
                                        Notification::send(
                                            $admins,
                                            new AdminNotification('Demande de paiement de credit est refusée')
                                        );

                                        $this->resetPhoneCreditField();
                                        $this->emit('modal-hide-phoneCredit-validated');
                                        $this->emit('modal-hide-phoneCredit');
                                        $this->emit('error', 'Achat de credit refusé !');

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }


                                            $notify = new MailController;
                                            $notify->newPhoneCredit($check);
                                        } else {


                                            $notify = new MailController;
                                            $notify->newPhoneCredit($check);
                                        }
                                    } else {

                                        $msg_title = 'Credit téléphonique';
                                        $msg = 'Demande de paiement de credit a été refusée';
                                        $check->wallet->sidUser->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );

                                        $notifs = $check->wallet->sidUser->unreadNotifications;
                                        // event(new UpdatePhoneCreditEvent($check));
                                        // event(new UserNotifEvent($notifs));
                                        Notification::send(
                                            $admins,
                                            new AdminNotification(
                                                'Demande de paiement de credit est refusée'
                                            )
                                        );

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }


                                            $notify = new MailController;
                                            $notify->newPhoneCredit($check);
                                        } else {

                                            $notify = new MailController;
                                            $notify->newPhoneCredit($check);
                                        }
                                    }
                                }
                            } else {
                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('error', 'Pas de modification !');
                            }
                        } else {
                            $this->resetPhoneCreditField();
                            $this->emit('modal-hide-phoneCredit-validated');
                            $this->emit('modal-hide-phoneCredit');
                            $this->emit('error', 'Selectionnez le choix du traitement de la demande SVP !');
                        }
                    } else {
                        $this->resetPhoneCreditField();
                        $this->emit('modal-hide-phoneCredit');
                        $this->emit('modal-hide-phoneCredit-validated');
                        $this->emit('error', 'Pas le valideur !');
                    }
                } else {
                    $this->resetPhoneCreditField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit('modal-hide-phoneCredit-validated');
                    $this->emit('error', 'Mot de passe Incorrect !');
                }
            } elseif ($this->password_transaction = "" ||  $this->phoneCreditStatus = "") {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit-validated');
                $this->emit('modal-hide-phoneCredit');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
            } else {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit');
                $this->emit('modal-hide-phoneCredit-validated');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
            }
        } catch (\Exception $exception) {
            $this->resetPhoneCreditField();
            $this->emit('modal-hide-phoneCredit-validated');
            $this->emit('modal-hide-phoneCredit');
            $this->emit('error', 'Vous avez cliquez deux fois ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function ChooseUpdatePhoneCredit($id)
    {
        $walletUpdate = WalletPhoneCredit::findOrFail($id);

        $sidAuth = Auth::guard('web')->user();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (Auth::guard('web')->user()->is_distributer == 1) {
                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    event(new ChoosePhoneCreditEvent($walletUpdate));
                    // $this->emit('modal-hide-phoneCredit');
                    $this->emit('menu-phoneCredit-active');
                    $this->emit('info', 'Vous avez choisi credit téléphonique');
                } else {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    event(new ChoosePhoneCreditEvent($walletUpdate));
                    // $this->emit('modal-hide-phoneCredit');
                    $this->emit('menu-phoneCredit-active');
                    $this->emit('info', 'Vous avez choisi credit téléphonique');
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

    public function updateReloadCard()
    {
        $check = WalletDeposit::whereId($this->reloadcardId)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = Auth::guard('web')->user();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();
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

                            $depositWallet = Deposit::create([

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

                            InvoiceDeposit::create([
                                'title' => 'Rechargement de carte',
                                'wallet_deposit_id' => $check->id,
                                'deposit_id' => $depositWallet->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            // Validated account Update Admin
                            $commission_transaction =  $check->amount_received;


                            if (Auth::guard('web')->user()->is_distributer == 1) {


                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);

                                // Send valeur historique distributer
                                $valeur_decrement = $check->adminCom->update([
                                    // 'amount' => $valeur_single,
                                    'amount_distributer' => $commission_transaction,
                                    'sid_user_id' => Auth::guard('web')->user()->id,
                                    'description_distributer' => 'Validation distributeur',
                                ]);
                            } else {

                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }

                            $valeur_amount_com = $check->adminCom->amount;


                            if ($check->wallet->sidUser->is_under_distributer == true) {


                                if ($valeur_amount_com != null) {
                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $check->adminCom->update([
                                                'amount' =>   $valeur_amount_com,
                                                'sid_user_id' =>   $admin_single->id,
                                                'description' => 'Paiement commission sous-distributer',
                                                'description_underdistributer' => 'Paiement commission sous-distributer',
                                                'status'  => "SUCCESS",
                                            ]);

                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount-' . $valeur_amount_com),
                                            ]);
                                        }
                                    }
                                } else {
                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('success', 'Rechargement Acceptée sans commission sous-distibuteur !');
                                }
                            } else {


                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                            }

                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                $msg_title = 'Rechargement de carte';
                                $msg =
                                    'Votre demande de rechargement de carte a été validée';
                                $check->wallet->sidUser->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );
                                $notifs = $check->wallet->sidUser->unreadNotifications;
                                // event(new UpdateReloadCardEvent($check));
                                // event(new UserNotifEvent($notifs));
                                Notification::send(
                                    $admins,
                                    new AdminNotification(
                                        'Demande de commande de carte a été validée'
                                    )
                                );


                                $this->resetReloadCardField();
                                $this->emit('modal-hide-reloadCard');
                                $this->emit('success', 'Rechargement Acceptée !');

                                if ($admins->count() > 0) {

                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;

                                        event(new AdminNotifEvent($notifs));
                                    }



                                    $notify = new MailController;
                                    $notify->newReload($check);
                                } else {


                                    $notify = new MailController;
                                    $notify->newReload($check);
                                }
                            } else {

                                if ($check->wallet->sidUser->country->code == 'CI') {

                                    $message =
                                        'Votre demande de rechargement de carte de ' .
                                        $check->amount .
                                        $check->wallet->currencie->code .
                                        ' ' .
                                        'par ' .
                                        $check->provider->name .
                                        ' le ' .
                                        $check->updated_at .
                                        ' a été acceptée avec succès ' .
                                        'Robus vous remercie';

                                    $encode = substr($check->wallet->sidUser->phone, -10);

                                    Http::get(
                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                    );

                                    $msg_title = 'Rechargement de carte';
                                    $msg = 'Votre demande de rechargement de carte a été validée';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    // event(new UpdateReloadCardEvent($check));
                                    // event(new UserNotifEvent($notifs));
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de rechargement de carte a été validée'
                                        )
                                    );

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('success', 'Rechargement acceptée !');

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    } else {


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    }
                                } else {

                                    $msg_title = 'Rechargement de carte';
                                    $msg =
                                        'Votre demande de rechargement de carte a été validée';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    // event(new UpdateReloadCardEvent($check));
                                    // event(new UserNotifEvent($notifs));
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de commande de carte a été validée'
                                        )
                                    );


                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('success', 'Rechargement acceptée !');

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }



                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    } else {


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    }
                                }
                            }
                        } elseif ($check->status == 'ERROR') {


                            if ($check->wallet->sidUser->is_under_distributer == 1) {


                                $commission =  $check->amount;

                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                ]);

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                if ($valeur_amount_com != null) {
                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;


                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $valeur_decrement = $check->adminCom->update([
                                                'amount' =>   0,
                                                'sid_user_id' =>   $admin_single->id,
                                                'description' => 'Remboursement de la commission sous distributeur',
                                                'status'  => "ERROR",
                                            ]);
                                        }
                                    }
                                }

                                Deposit::create([
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

                                $msg_title = 'Rechargement de carte';
                                $msg = 'Votre demande de commande de carte a été refusée';
                                $check->wallet->sidUser->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );
                                $notifs = $check->wallet->sidUser->unreadNotifications;
                                Notification::send(
                                    $admins,
                                    new AdminNotification(
                                        'Demande de commande de carte a été refusée'
                                    )
                                );

                                if ($admins->count() > 0) {

                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;

                                        event(new AdminNotifEvent($notifs));
                                    }

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('error', 'Rechargement refusé !');
                                    $notify = new MailController;
                                    $notify->newReload($check);
                                } else {


                                    $notify = new MailController;
                                    $notify->newReload($check);
                                }
                            } else {

                                $commission =  $check->amount + $check->costs_amount;

                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                ]);

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                if ($valeur_amount_com != null) {
                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $valeur_decrement = $check->adminCom->update([
                                                'amount' =>   0,
                                                'sid_user_id' =>   $admin_single->id,
                                                'status'  => "ERROR",
                                                'description' => 'Remboursement de la commission client',
                                            ]);
                                        }
                                    }
                                }

                                Deposit::create([
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

                                if ($check->wallet->sidUser->country->code == 'CI') {

                                    $message =
                                        'Votre demande de rechargement de carte de ' .
                                        $check->amount .
                                        $check->wallet->currencie->code .
                                        ' ' .
                                        'par ' .
                                        $check->provider->name .
                                        ' le ' .
                                        $check->updated_at .
                                        ' a été refusée ' .
                                        'Robus vous remercie';

                                    $encode = substr($check->wallet->sidUser->phone, -10);

                                    Http::get(
                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                    );

                                    $msg_title = 'Rechargement de carte';
                                    $msg =
                                        'Votre demande de commande de carte a été refusé';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de commande de carte a été refusé'
                                        )
                                    );

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('error', 'Rechargement refusé !');

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    } else {


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    }
                                } else {

                                    $msg_title = 'Rechargement de carte';
                                    $msg = 'Votre demande de commande de carte a été refusée';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de commande de carte a été refusée'
                                        )
                                    );

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('error', 'Rechargement refusé !');

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    } else {


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    }
                                }
                            }
                        } else {
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard-validated');
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('error', 'Selectionnez le choix de la demande SVP !');
                        }
                    } else {
                        $this->resetReloadCardField();
                        $this->emit('modal-hide-reloadCard-validated');
                        $this->emit('modal-hide-reloadCard');
                        $this->emit('error', 'Pas le valideur !');
                    }
                } else {
                    $this->resetReloadCardField();
                    $this->emit('modal-hide-reloadCard-validated');
                    $this->emit('modal-hide-reloadCard');
                    $this->emit('info', 'Vérifier votre mot de passe !');
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

    public function ChooseUpdateReloadCard($id)
    {
        $walletUpdate = WalletDeposit::findOrFail($id);
        $sidAuth = Auth::guard('web')->user();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (Auth::guard('web')->user()->is_distributer == 1) {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    $this->emit('menu-reloadCarte-active');
                    $this->emit('info', 'Vous avez choisi rechargement de carte');
                    event(new ChooseReloadCardEvent($walletUpdate));
                } else {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    $this->emit('menu-reloadCarte-active');
                    $this->emit('info', 'Vous avez choisi rechargement de carte');
                    event(new ChooseReloadCardEvent($walletUpdate));
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

    public function updateTransaction()
    {

        $check = WalletTransaction::whereId($this->transactionId)->first();
        $user_auth = Auth::guard('web')->user();
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

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

                            $transaction = Transaction::create([
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

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $check->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);


                            if (Auth::guard('web')->user()->is_distributer == 1) {


                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);

                                // Send valeur historique distributer
                                $check->adminCom->update([
                                    // 'amount' => $valeur_single,
                                    'amount_distributer' => $commission_transaction,
                                    'sid_user_id' => Auth::guard('web')->user()->id,
                                    'description_distributer' => 'Validation distributeur',
                                ]);
                            } else {

                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }

                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $message =
                                    'Vous demande de transaction de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' le ' .
                                    $check->updated_at .
                                    ' a été acceptée avec succès ' .
                                    'Robus vous remercie';

                                $encode = substr($check->wallet->sidUser->phone, -10);

                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->wallet->sidUser->is_under_distributer == true) {

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

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
                                            'amount' => DB::raw('amount-' . $valeur_single),
                                        ]);
                                    }
                                }
                            } else {

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                            }

                            $msg_title = 'Transfers';
                            $msg = 'Demande de transfert a été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            Notification::send(
                                $admins,
                                new AdminNotification('Demande de transfers validé')
                            );
                            $this->cancelTransactionField();
                            $this->emit('modal-hide-transfers-validated');
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers accepte !');

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }

                                $notify = new MailController;
                                $notify->newTransaction($check);
                            }

                            $notify = new MailController;
                            $notify->newTransaction($check);
                        } elseif ($check->status == 'ERROR') {


                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                if ($check->provider->id != 9) {


                                    $commission =  $check->amount;


                                    $check->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;
                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);


                                    // Valeur amount commission distributeur
                                    $valeur_amount_com = $check->adminCom->amount;
                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $check->adminCom->update([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'status'  => "ERROR",
                                                'description' =>  'Remboursement des commissions sous distributeur',
                                            ]);
                                        }
                                    }
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;

                                    $check->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +  (float)$commission;
                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);

                                    // Valeur amount commission distributeur
                                    $valeur_amount_com = $check->adminCom->amount;

                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

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
                            } else {

                                if ($check->provider->id != 9) {
                                    $commission =  $check->amount;
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;
                                }

                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                $wallet_received =  $check->amount_solde;

                                $amount_solde = (float)$wallet_received  +   (float)$commission;

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                    'amount_solde' => $amount_solde,
                                ]);

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   0,
                                            'sid_user_id' =>   $admin_single->id,
                                            'status'  => "ERROR",
                                            'description' => 'Remboursement de la commission client',
                                        ]);
                                    }
                                }

                                if ($check->wallet->sidUser->country->code == 'CI') {

                                    $message =
                                        'Vous demande de transaction de ' .
                                        $check->amount .
                                        $check->wallet->currencie->code .
                                        ' ' .
                                        'par ' .
                                        $check->provider->name .
                                        ' le ' .
                                        $check->updated_at .
                                        ' a été refusée ' .
                                        'Robus vous remercie';

                                    $encode = substr($check->wallet->sidUser->phone, -10);

                                    Http::get(
                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                    );
                                }
                            }

                            Transaction::create([
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

                            $msg_title = 'Transfers';
                            $msg = 'Demande de transfers a été refusé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de transfers a été refusé'
                                )
                            );

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers-validated');
                            $this->emit('modal-hide-transfers');
                            $this->emit('warning', 'Transfers Refusé !');
                            $notify = new MailController;
                            $notify->newTransaction($check);
                        } else {
                            $this->cancelTransactionField();
                            $this->emit('modal-hide-transfers-validated');
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Vous devez faire un choix de traitement !');
                        }
                    } else {
                        $this->cancelTransactionField();
                        $this->emit('modal-hide-transfers-validated');
                        $this->emit('modal-hide-transfers');
                        $this->emit('error', 'Aucune donnée !');
                    }
                } else {
                    $this->cancelTransactionField();
                    $this->emit('modal-hide-transfers-validated');
                    $this->emit('modal-hide-transfers');
                    $this->emit('error', 'Vérifier votre mot de passe !');
                }
            } elseif ($this->password_transaction = "") {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers-validated');
                $this->emit('modal-hide-transfers');
                $this->emit('info', 'Vous avez cliquez dexu fois le boutton !!');
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

    // Choose User adminto validated transactions
    public function ChooseUpdateTransaction($id)
    {
        $walletUpdate = WalletTransaction::findOrFail($id);
        $sidAuth = Auth::guard('web')->user();
        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (Auth::guard('web')->user()->is_distributer == 1) {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);

                    event(new ChooseTransactionEvent($walletUpdate));
                    // $this->emit('modal-hide-transfers');
                    $this->emit('menu-transaction-active');
                    $this->emit('info', 'Vous avez choisi transaction');
                } else {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);

                    event(new ChooseTransactionEvent($walletUpdate));
                    // $this->emit('modal-hide-transfers');
                    $this->emit('menu-transaction-active');
                    $this->emit('info', 'Vous avez choisi transaction');
                }
            } else {
                // $this->emit('modal-hide-transfers');
                $this->emit('menu-transaction-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-transaction-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function updatePaiement()
    {
        $check = WalletPayment::whereId($this->paiementId)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = Auth::guard('web')->user();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

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

                            $payment = Payment::create([
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


                            InvoicePayment::create([
                                'title' => 'Paiement de facture ',
                                'wallet_payment_id' => $check->id,
                                'payment_id' => $payment->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            $commission_transaction =  $check->amount;


                            if (Auth::guard('web')->user()->is_distributer == 1) {


                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);


                                // Send valeur historique distributer
                                $valeur_decrement = $check->adminCom->update([
                                    // 'amount' => $valeur_single,
                                    'amount_distributer' => $commission_transaction,
                                    'sid_user_id' => Auth::guard('web')->user()->id,
                                    'description_distributer' => 'Validation distributeur',
                                ]);
                            } else {

                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }


                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $message =
                                    'Votre demande de paiement de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' le ' .
                                    $check->updated_at .
                                    ' a été acceptée avec succès ' .
                                    'Robus vous remercie';

                                $encode = substr($check->wallet->sidUser->phone, -10);

                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $msg_title = 'Paiement de facture';
                            $msg = 'Demande de paiement a été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );

                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            // event(new UpdatePaiementEvent($check));
                            // event(new UserNotifEvent($notifs));
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de paiaement a été Validé'
                                )
                            );

                            // Valeur amount commission distributeur
                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->wallet->sidUser->is_under_distributer == true) {

                                $under_amount_com = $check->adminCom->amount_under;

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

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
                                        // Update amount Unde distributer
                                        // $admin_single->wallet->update([
                                        //     'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        //     'sid_user_relod' => Auth::guard('web')->user()->id,
                                        //     'amount' => DB::raw('amount-' . $under_amount_com),
                                        // ]);

                                        // Update amount admin
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_amount_com),
                                        ]);
                                    }
                                }
                            } else {

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                            }

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('success', 'Paiement Accepté !');
                                }
                            }
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('success', 'Paiement Accepté !');
                            $notify = new MailController;
                            $notify->newPaiement($check);
                        } elseif ($check->status == 'ERROR') {


                            $commission =  $check->amount + $check->costs_amount;

                            $check->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+' . $commission),
                            ]);

                            $wallet_received =  $check->amount_solde;

                            $amount_solde = (float)$wallet_received  +   (float)$commission;

                            // Update commission
                            $check->update([
                                'commissions' => 0,
                                'commissions_admin' => 0,
                                'amount_solde' => $amount_solde,
                            ]);

                            // Valeur amount commission distributeur
                            $valeur_amount_com = $check->adminCom->amount;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $valeur_amount_com / $count_admin;

                                foreach ($admin_civ as $user) {
                                    $admin_single = SidUser::find($user->id);
                                    $check->adminCom->update([
                                        'amount' =>   0,
                                        'sid_user_id' =>   $admin_single->id,
                                        'description' => 'Remboursement de la commission client',
                                        'status'  => "ERROR",
                                    ]);
                                }
                            }

                            Payment::create([
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

                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $message = 'Votre demande de paiement de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' le ' .
                                    $check->updated_at .
                                    ' a été refusée' .
                                    ' Robus vous remercie';

                                $encode = substr($check->wallet->sidUser->phone, -10);
                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $msg_title = 'Paiement de facture';
                            $msg = 'Demande de paiement a été refusé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            Notification::send(
                                $admins,
                                new AdminNotification('Demande de transfers refusé')
                            );

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement-validated');
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('error', 'Payment Refusé !');
                                }
                            }

                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement-validated');
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Payment Refusé !');
                            $notify = new MailController;
                            $notify->newPaiement($check);
                        } else {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('modal-hide-paiement-validated');
                            $this->emit('error', 'Vous devez faire un choix de traitement de la demande !');
                        }
                    } else {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('modal-hide-paiement-validated');
                        $this->emit('success', 'Pas vous le valideur !');
                    }
                } else {

                    $this->resetPaiementField();
                    $this->emit('modal-hide-paiement-validated');
                    $this->emit('modal-hide-paiement');
                    $this->emit('info', 'Vérifier votre mot de passe !');
                }
            } elseif ($this->password_transaction = "") {
                $this->resetPaiementField();
                $this->emit('modal-hide-paiement');
                $this->emit('modal-hide-paiement-validated');
                $this->emit('info', 'Vous avez cliquez deux fois de suite le boutton !!');
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

    public function ChooseUpdatePayment($id)
    {
        $walletUpdate = WalletPayment::findOrFail($id);

        $sidAuth = Auth::guard('web')->user();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (Auth::guard('web')->user()->is_distributer == 1) {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);

                    event(new ChoosePaymentEvent($walletUpdate));
                    $this->emit('modal-hide-paiement');
                    $this->emit('menu-paiement-active');
                    $this->emit('info', 'Vous avez choisi paiement');
                } else {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);

                    event(new ChoosePaymentEvent($walletUpdate));
                    $this->emit('modal-hide-paiement');
                    $this->emit('menu-paiement-active');
                    $this->emit('info', 'Vous avez choisi paiement');
                }
            } else {
                // $this->emit('modal-hide-paiement');
                $this->emit('menu-paiement-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-paiement-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function updateVirement()
    {
        $check = WalletBank::whereId($this->virementId)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = Auth::guard('web')->user();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin = SidUser::where('status', true)
            ->where('is_admin', true)
            ->first();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();
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

                            $paymentBank = PaymentBank::create([
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

                            InvoiceBank::create([
                                'title' => 'Virement bancaire',
                                'wallet_bank_id' => $check->id,
                                'payment_bank_id' => $paymentBank->id,
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'status' => $check->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            $commission_transaction = $check->amount_received;

                            if (Auth::guard('web')->user()->is_distributer == 1) {


                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);

                                // Send valeur historique distributer
                                $check->adminCom->update([
                                    // 'amount' => $valeur_single,
                                    'amount_distributer' => $commission_transaction,
                                    'sid_user_id' => Auth::guard('web')->user()->id,
                                    'description_distributer' => 'Validation distributeur',
                                ]);
                            } else {

                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }


                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $message =
                                    'Votre demande de paiement de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' a ' .
                                    $check->updated_at .
                                    ' a été validée avec succès ' .
                                    'Robus vous remercie';

                                $encode = substr($check->wallet->sidUser->phone, -10);

                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $msg_title = 'Versement banquaire';
                            $msg = 'Votre versement a été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            // $notifs = $check->wallet->sidUser->unreadNotifications;
                            // event(new UserNotifEvent($notifs));
                            // event(new UpdateBankEvent($check));
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de virement a été validée'
                                )
                            );

                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->wallet->sidUser->is_under_distributer == true) {
                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

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
                                            'amount' => DB::raw('amount-' . $valeur_single),
                                        ]);
                                    }
                                }
                            } else {

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                            }

                            if ($admins->count() > 0) {


                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }
                            $this->resetVirementField();
                            $this->emit('modal-hide-virement');
                            $this->emit('modal-hide-virement-validated');
                            $this->emit('success', 'Virement Accepté !');
                            $notify = new MailController;
                            $notify->newVirement($check);
                        } elseif ($check->status == 'ERROR') {

                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                $commission =  $check->amount;

                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                $wallet_received =  $check->amount_solde;

                                $amount_solde = (float)$wallet_received  +   (float)$commission;

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                    'amount_solde' => $amount_solde,
                                ]);
                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;
                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $valeur_decrement = $check->adminCom->update([
                                            'amount' =>   0,
                                            'sid_user_id' =>   $admin_single->id,
                                            'status'  => "ERROR",
                                            'description' => 'Remboursement de la commission sous distributeur',
                                        ]);
                                    }
                                }
                            } else {

                                $commission =  $check->amount + $check->costs_amount;

                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                $wallet_received =  $check->amount_solde;

                                $amount_solde = (float)$wallet_received  +   (float)$commission;

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                    'amount_solde' => $amount_solde,
                                ]);

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   0,
                                            'sid_user_id' =>   $admin_single->id,
                                            'status'  => "ERROR",
                                            'description' => 'Remboursement de la commission client',
                                        ]);
                                    }
                                }


                                if ($check->wallet->sidUser->country->code == 'CI') {
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

                                    $encode = substr($check->wallet->sidUser->phone, -10);

                                    Http::get(
                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                    );
                                }
                            }

                            PaymentBank::create([
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

                            $msg_title = 'Versement banquaire';
                            $msg = 'Demande de virement a été réfusée ';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de virement a été refusée'
                                )
                            );
                            if ($admins->count() > 0) {


                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }

                            $this->resetVirementField();
                            $this->emit('modal-hide-virement-validated');
                            $this->emit('modal-hide-virement');
                            $this->emit('error', 'Virement refusée !');
                            $notify = new MailController;
                            $notify->newVirement($check);
                        } else {
                            $this->resetVirementField();
                            $this->emit('modal-hide-virement-validated');
                            $this->emit('modal-hide-virement');
                            $this->emit('error', 'Selectionner le choix de traitement de la demande SVP !');
                        }
                    } else {
                        $this->resetVirementField();
                        $this->emit('modal-hide-virement');
                        $this->emit('modal-hide-virement-validated');
                        $this->emit('error', 'Pas vous le valideur !');
                    }
                } else {
                    $this->resetVirementField();
                    $this->emit('modal-hide-virement-validated');
                    $this->emit('modal-hide-virement');
                    $this->emit('error', 'Mot de passe Incorrect !');
                }
            } elseif ($this->password_transaction = "" ||  $this->virementStatus = "") {
                $this->resetVirementField();
                $this->emit('modal-hide-virement-validated');
                $this->emit('modal-hide-virement');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
            } else {
                $this->resetVirementField();
                $this->emit('modal-hide-virement-validated');
                $this->emit('modal-hide-virement');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
            }
        } catch (\Exception $exception) {
            $this->resetVirementField();
            $this->emit('modal-hide-virement');
            $this->emit('modal-hide-virement-validated');
            $this->emit('error', 'Vous avez cliquez deux fois ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function updateXbet()
    {
        $check = WalletXbet::whereId($this->xbet_id)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = Auth::guard('web')->user();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();
        try {

            if ($check) {

                $this->validate([
                    'status_xbet_edit' => 'required',
                    'password_transaction' => 'required',
                ]);


                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description' => $this->xbetDescription ?? null,
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status' => $this->status_xbet_edit,
                    ]);

                    $this->password_transaction = "";
                    $this->status_xbet_edit = "";
                    $this->xbet_id = "";

                    if ($check->updated_by == Auth::guard('web')->user()->id) {

                        if ($check->status == 'SUCCESS') {

                            $commission_transaction = $check->amount_received;


                            if ($check->choose_type_transaction == 1) {

                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            } else {

                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount-' . $commission_transaction),
                                ]);

                                // solde custumers
                                $amount_solde = (float)  $check->wallet->amount + (float) $commission_transaction;

                                $check->update([
                                    'amount_solde'  => $amount_solde,
                                ]);

                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }


                            $valeur_amount_com = $check->adminCom->amount;

                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $valeur_amount_com / $count_admin;

                                foreach ($admin_civ as $user) {
                                    $admin_single = SidUser::find($user->id);
                                    $check->adminCom->update([
                                        'amount' =>   $valeur_amount_com,
                                        'sid_user_id' =>   $admin_single->id,
                                        'description' => 'Commission administrateur',
                                        'status'  => "SUCCESS",
                                    ]);
                                    $admin_single->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $valeur_single),
                                    ]);
                                }
                            }


                            $transaction = OneBetTransaction::create([
                                'wallet_xbet_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' =>  $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            OneBetInvoice::create([
                                'title' => "Depot 1xbet",
                                'wallet_xbet_id' => $check->id,
                                'one_bet_transaction_id' => $transaction->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);


                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $messageType = $check->choose_type_transaction == 1 ? 'demande de dépôt de ' : 'demande de retrait de ';
                                $amount = $check->amount . ' ' . $check->wallet->currencie->code;
                                $provider = $check->provider->name;
                                $username = $check->firstname . ' ' . $check->firstname;
                                $date = $check->updated_at;

                                $message = 'Votre ' . $messageType . $amount . ' par ' . $provider . ' a été validée avec succès le ' . $date . '. Robus vous remercie.';


                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $msg_title = 'Service Xbet';
                            $msg = 'Votre xbet a été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );

                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de virement a été validée'
                                )
                            );


                            if ($admins->count() > 0) {


                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }
                            $this->resetXbetField();
                            $this->emit('menu-xbet-active');
                            $this->emit('modal-hide-xbet-validated');
                            $this->emit('success', 'xbet Accepté !');
                            $notify = new MailController;
                            $notify->newVirement($check);
                        } elseif ($check->status == 'ERROR') {

                            $commission =  $check->amount + $check->costs_amount;

                            if ($check->choose_type_transaction == 1) {
                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                $wallet_received =  $check->amount_solde;

                                $amount_solde = (float)$wallet_received  +   (float)$commission;

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                    'amount_solde' => $amount_solde,
                                    'commission_masters' => 0,
                                ]);
                            }


                            // Valeur amount commission distributeur
                            $valeur_amount_com = $check->adminCom->amount;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $valeur_amount_com / $count_admin;

                                foreach ($admin_civ as $user) {
                                    $admin_single = SidUser::find($user->id);
                                    $check->adminCom->update([
                                        'amount' =>   0,
                                        'sid_user_id' =>   $admin_single->id,
                                        'status'  => "ERROR",
                                        'description' => 'Remboursement de la commission client',
                                    ]);
                                }
                            }


                            if ($check->wallet->sidUser->country->code == 'CI') {

                                $messageType = $check->choose_type_transaction == 1 ? 'demande de dépôt de ' : 'demande de retrait de ';
                                $amount = $check->amount . ' ' . $check->wallet->currencie->code;
                                $provider = $check->provider->name;
                                $username = $check->firstname . ' ' . $check->firstname;
                                $date = $check->updated_at;

                                $message = 'Votre ' . $messageType . $amount . ' par ' . $provider . ' a été refusée le ' . $date . '. Robus vous remercie.';

                                $encode = substr($check->wallet->sidUser->phone, -10);

                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }


                            $transaction = OneBetTransaction::create([
                                'wallet_xbet_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' =>  $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            OneBetInvoice::create([
                                'title' => "Depot 1xbet",
                                'wallet_xbet_id' => $check->id,
                                'one_bet_transaction_id' => $transaction->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);


                            $msg_title = 'Service Xbet';
                            $msg = 'Demande de Xbet a été réfusée ';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );

                            $notifs = $check->wallet->sidUser->unreadNotifications;

                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de xbet a été refusée'
                                )
                            );
                            if ($admins->count() > 0) {


                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }

                            $this->resetXbetField();
                            $this->emit('modal-hide-xbet-validated');
                            $this->emit('menu-xbet-active');

                            $this->emit('error', 'xbet refusée !');

                            $notify = new MailController;
                            $notify->newVirement($check);
                        } else {
                            $this->resetXbetField();
                            $this->emit('modal-hide-xbet-validated');
                            $this->emit('menu-xbet-active');
                            $this->emit('success', 'xbet modifié !');
                        }
                    } else {
                        $this->resetXbetField();
                        $this->emit('menu-xbet-active');
                        $this->emit('modal-hide-xbet-validated');
                        $this->emit('error', 'Pas vous le valideur !');
                    }
                } else {
                    $this->resetXbetField();
                    $this->emit('modal-hide-xbet-validated');
                    $this->emit('menu-xbet-active');
                    $this->emit('error', 'Mot de passe Incorrect !');
                }
            } elseif ($this->password_transaction = "" ||  $this->status_xbet_edit = "") {
                $this->resetXbetField();
                $this->emit('modal-hide-xbet-validated');
                $this->emit('menu-xbet-active');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
            } else {
                $this->resetXbetField();
                $this->emit('modal-hide-xbet-validated');
                $this->emit('menu-xbet-active');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
            }
        } catch (\Exception $exception) {
            $this->resetXbetField();
            $this->emit('menu-xbet-active');
            $this->emit('modal-hide-xbet-validated');
            $this->emit('error', 'Vous avez cliquez deux fois ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }


    public function updateWave()
    {
        $check = WalletWave::whereId($this->wave_id)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = Auth::guard('web')->user();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();
        try {

            if ($check) {

                $this->validate([
                    'status_wave_edit' => 'required',
                    'password_transaction' => 'required',
                ]);


                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description' => $this->waveDescription ?? null,
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status' => $this->status_wave_edit,
                    ]);

                    $this->password_transaction = "";
                    $this->status_wave_edit = "";
                    $this->wave_id = "";

                    if ($check->updated_by == Auth::guard('web')->user()->id) {

                        if ($check->status == 'SUCCESS') {

                            $commission_transaction = $check->amount_received;


                            Auth::guard('web')->user()->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname .
                                    ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-' . $commission_transaction),
                            ]);

                            // solde custumers
                            $amount_solde = (float)  $check->wallet->amount + (float) $commission_transaction;

                            $check->update([
                                'amount_solde'  => $amount_solde,
                            ]);

                            $check->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+' . $commission_transaction),
                            ]);


                            $valeur_amount_com = $check->adminCom->amount;

                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $valeur_amount_com / $count_admin;

                                foreach ($admin_civ as $user) {
                                    $admin_single = SidUser::find($user->id);
                                    $check->adminCom->update([
                                        'amount' =>   $valeur_amount_com,
                                        'sid_user_id' =>   $admin_single->id,
                                        'description' => 'Commission administrateur',
                                        'status'  => "SUCCESS",
                                    ]);
                                    $admin_single->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $valeur_single),
                                    ]);
                                }
                            }


                            $transaction = PaymentWave::create([
                                'wallet_wave_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'WV_' . $check->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' =>  $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            InvoiceWave::create([
                                'title' => "Achat UV par Wave",
                                'wallet_wave_id' => $check->id,
                                'payment_wave_id' => $transaction->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);


                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $messageType = 'demande achat UV par WAVE ';
                                $amount = $check->amount . ' ' . $check->wallet->currencie->code;
                                $provider = $check->provider->name;
                                $username = $check->firstname . ' ' . $check->firstname;
                                $date = $check->updated_at;

                                $message = 'Votre ' . $messageType . $amount . ' par ' . $provider . ' a été validée avec succès le ' . $date . '. Robus vous remercie.';


                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $msg_title = 'Service achat UV par WAVE';
                            $msg = 'Votre achat Wave a été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );

                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de achat wave a été validée'
                                )
                            );


                            if ($admins->count() > 0) {


                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }
                            $this->resetWaveField();
                            $this->emit('menu-wave-active');
                            $this->emit('modal-hide-wave-validated');
                            $this->emit('success', 'wave Accepté !');
                            $notify = new MailController;
                            $notify->newVirement($check);
                        } elseif ($check->status == 'ERROR') {

                            $commission =  $check->amount + $check->costs_amount;

                            if ($check->choose_type_transaction == 1) {
                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                $wallet_received =  $check->amount_solde;

                                $amount_solde = (float)$wallet_received  +   (float)$commission;

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                    'amount_solde' => $amount_solde,
                                    'commission_masters' => 0,
                                ]);
                            }


                            // Valeur amount commission distributeur
                            $valeur_amount_com = $check->adminCom->amount;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $valeur_amount_com / $count_admin;

                                foreach ($admin_civ as $user) {
                                    $admin_single = SidUser::find($user->id);
                                    $check->adminCom->update([
                                        'amount' =>   0,
                                        'sid_user_id' =>   $admin_single->id,
                                        'status'  => "ERROR",
                                        'description' => 'Remboursement de la commission client',
                                    ]);
                                }
                            }


                            if ($check->wallet->sidUser->country->code == 'CI') {

                                $messageType = 'demande achat UV par Wave de ';
                                $amount = $check->amount . ' ' . $check->wallet->currencie->code;
                                $provider = $check->provider->name;
                                $username = $check->firstname . ' ' . $check->firstname;
                                $date = $check->updated_at;

                                $message = 'Votre ' . $messageType . $amount . ' par ' . $provider . ' a été refusée le ' . $date . '. Robus vous remercie.';

                                $encode = substr($check->wallet->sidUser->phone, -10);

                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }


                            $transaction = PaymentWave::create([
                                'wallet_wave_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'WV_' . $check->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' =>  $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            InvoiceWave::create([
                                'title' => "Achat UV par Wave",
                                'wallet_wave_id' => $check->id,
                                'payment_wave_id' => $transaction->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);

                            $msg_title = 'Service Achat UV WAVE ';
                            $msg = 'Demande achat a été réfusée ';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );

                            $notifs = $check->wallet->sidUser->unreadNotifications;

                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de xbet a été refusée'
                                )
                            );
                            if ($admins->count() > 0) {


                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }

                            $this->resetWaveField();
                            $this->emit('modal-hide-wave-validated');
                            $this->emit('menu-wave-active');

                            $this->emit('error', 'wave refusée !');

                            $notify = new MailController;
                            $notify->newVirement($check);
                        } else {
                            $this->resetWaveField();
                            $this->emit('modal-hide-wave-validated');
                            $this->emit('menu-wave-active');
                            $this->emit('success', 'wave modifié !');
                        }
                    } else {
                        $this->resetWaveField();
                        $this->emit('menu-wave-active');
                        $this->emit('modal-hide-wave-validated');
                        $this->emit('error', 'Pas vous le valideur !');
                    }
                } else {
                    $this->resetWaveField();
                    $this->emit('modal-hide-wave-validated');
                    $this->emit('menu-wave-active');
                    $this->emit('error', 'Mot de passe Incorrect !');
                }
            } elseif ($this->password_transaction = "" ||  $this->status_wave_edit = "") {
                $this->resetWaveField();
                $this->emit('modal-hide-wave-validated');
                $this->emit('menu-wave-active');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
            } else {
                $this->resetWaveField();
                $this->emit('modal-hide-wave-validated');
                $this->emit('menu-wave-active');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
            }
        } catch (\Exception $exception) {
            $this->resetWaveField();
            $this->emit('menu-wave-active');
            $this->emit('modal-hide-wave-validated');
            $this->emit('error', 'Vous avez cliquez deux fois ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }
    // Choose Update Virement
    public function ChooseUpdateVirement($id)
    {
        $walletUpdate = WalletBank::findOrFail($id);
        $sidAuth = Auth::guard('web')->user();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (Auth::guard('web')->user()->is_distributer == 1) {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    event(new ChooseVirementEvent($walletUpdate));
                    // $this->emit('modal-hide-virement');
                    $this->emit('menu-virement-active');
                    $this->emit('info', 'Vous avez choisi virement');
                } else {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    event(new ChooseVirementEvent($walletUpdate));
                    // $this->emit('modal-hide-virement');
                    $this->emit('menu-virement-active');
                    $this->emit('info', 'Vous avez choisi virement');
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
    // Choose 1xbets

    public function ChooseUpdateXbet($id)
    {
        $walletUpdate = WalletXbet::findOrFail($id);
        $sidAuth = Auth::guard('web')->user();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (Auth::guard('web')->user()->is_distributer == 1) {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    event(new ChooseXbetEvent($walletUpdate));
                    $this->emit('menu-xbet-active');
                    $this->emit('info', 'Vous avez choisi Xbet');
                } else {

                    event(new ChooseXbetEvent($walletUpdate));
                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    // $this->emit('modal-hide-xbet');
                    $this->emit('menu-xbet-active');
                    $this->emit('info', 'Vous avez choisi Xbet');
                }
            } else {
                //  $this->emit('modal-hide-xbet');
                $this->emit('menu-xbet-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-xbet-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }


    public function ChooseUpdateWave($id)
    {
        $walletUpdate = WalletWave::findOrFail($id);
        $sidAuth = Auth::guard('web')->user();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (Auth::guard('web')->user()->is_distributer == 1) {

                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    event(new ChooseWaveEvent($walletUpdate));
                    $this->emit('menu-wave-active');
                    $this->emit('info', 'Vous avez choisi Wave');
                } else {

                    event(new ChooseWaveEvent($walletUpdate));
                    $walletUpdate->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                    ]);
                    // $this->emit('modal-hide-wave');
                    $this->emit('menu-wave-active');
                    $this->emit('info', 'Vous avez choisi Wave');
                }
            } else {
                //  $this->emit('modal-hide-wave');
                $this->emit('menu-wave-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-xbet-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }
    public function updateInter()
    {
        $check = WalletInter::whereId($this->interId)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = Auth::guard('web')->user();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

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

                            $transaction_inter = TransactionInter::create([
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

                            InvoiceInter::create([
                                'title' => "Transfers d'argent",
                                // 'transaction_inter_id' => $check->id,
                                'transaction_inter_id' => $transaction_inter->id,
                                // 'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);

                            if (sabdy()->country->currencie->value > $check->country->currencie->value) {
                                //   Convertion  in CFA
                                $valeur_amount_receveid = (float) $check->amount_received * (float) $check->country->currencie->value;

                                $valeur_amount_send = (float) $check->amount * (float) $check->country->currencie->value;
                            } elseif (sabdy()->country->currencie->value == $check->country->currencie->value) {
                                $valeur_amount_receveid = $check->amount_received;
                                $valeur_amount_send = (float) $check->amount;
                            } elseif (sabdy()->country->currencie->value < $check->country->currencie->value) {
                                //   Convertion in Country send
                                $valeur_amount_receveid = (float) $check->amount_received  * (float) $check->country->currencie->value;
                                $valeur_amount_send = (float) $check->amount * (float) $check->country->currencie->value;
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
                                || $check->provider->id == 49
                                || $check->provider->id == 50
                                || $check->provider->id == 55
                            ) {

                                $commission_transaction = $valeur_amount_receveid;
                            } else {

                                $commission_transaction = $valeur_amount_receveid;
                            }



                            if (Auth::guard('web')->user()->is_distributer == 1) {


                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);

                                // Send valeur historique distributer
                                $check->adminCom->update([
                                    // 'amount' => $valeur_single,
                                    'amount_distributer' => $commission_transaction,
                                    'sid_user_id' => Auth::guard('web')->user()->id,
                                    'description_distributer' => 'Validation distributeur',
                                ]);
                            } else {

                                Auth::guard('web')->user()->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname .
                                        ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }


                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

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

                                $encode = substr($check->wallet->sidUser->phone, -10);


                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->provider_type == 7 || $check->provider_type == 8) {

                                if ($check->wallet->sidUser->is_under_distributer == true) {


                                    if ($valeur_amount_com != null) {
                                        //  Send valeur in Admin
                                        if ($admin_civ->count() > 0) {

                                            $count_admin = $admin_civ->count();

                                            $valeur_single = $valeur_amount_com / $count_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                $check->adminCom->update([
                                                    'amount' =>   $valeur_amount_com,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'description' => 'Commission administrateur',
                                                    'description_underdistributer' => 'Paiement commission sous-distributer',
                                                    'status'  => "SUCCESS",
                                                ]);

                                                $admin_single->wallet->update([
                                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_amount_com),
                                                ]);
                                            }
                                        }
                                    } else {
                                        $this->resetReloadCardField();
                                        $this->emit('modal-hide-reloadCard');
                                        $this->emit('success', 'Rechargement Acceptée sans commission sous-distibuteur !');
                                    }
                                    //  Send valeur in Admin
                                } else {

                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $check->adminCom->update([
                                                'amount' =>   $valeur_amount_com,
                                                'sid_user_id' =>   $admin_single->id,
                                                'description' => 'Commission administrateur',
                                                'status'  => "SUCCESS",
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                }
                            } else {

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                            }
                            $msg_title = 'Paiement internationnal';
                            $msg = 'Demande de transfers inter été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );

                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            // event(new UpdateInterEvent($check));
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de tarnsfers inter Validé'
                                )
                            );

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }

                            $this->resetInterField();
                            $this->emit('modal-hide-inters-validated');
                            $this->emit('modal-hide-inters');
                            $this->emit('success', 'Transfers Accepte !');
                            $notify = new MailController;
                            $notify->newPaiementInter($check);
                        } elseif ($check->status == 'ERROR') {

                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                if (
                                    $check->provider->id == 61
                                    || $check->provider->id == 62
                                    || $check->provider->id == 63
                                    || $check->provider->id == 64
                                    || $check->provider->id == 65
                                    || $check->provider->id == 59
                                    || $check->provider->id == 60
                                    || $check->provider->id == 48
                                    || $check->provider->id == 49
                                    || $check->provider->id == 50
                                    || $check->provider->id == 55
                                    || $check->provider->id == 71
                                    || $check->provider->id == 72
                                    || $check->provider->id == 73
                                    || $check->provider->id == 74
                                    || $check->provider->id == 75
                                    || $check->provider->id == 76
                                    || $check->provider->id == 77
                                    || $check->provider->id == 78
                                ) {

                                    $commission =  $check->amount;

                                    $check->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    // Update commission
                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;

                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;

                                    $check->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;

                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);
                                }
                            } else {

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
                                    || $check->provider->id == 49
                                    || $check->provider->id == 50
                                    || $check->provider->id == 55
                                ) {

                                    $commission =  $check->amount;

                                    $check->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;

                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;

                                    $check->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;

                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);
                                }
                            }

                            // Valeur amount commission distributeur
                            $valeur_amount_com = $check->adminCom->amount;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $valeur_amount_com / $count_admin;

                                foreach ($admin_civ as $user) {
                                    $admin_single = SidUser::find($user->id);
                                    $check->adminCom->update([
                                        'amount' =>   0,
                                        'sid_user_id' =>   $admin_single->id,
                                        'status'  => "ERROR",
                                        'description' => 'Remboursement de la commission client',
                                    ]);
                                }
                            }
                            $transaction_inter = TransactionInter::create([
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

                            InvoiceInter::create([
                                'title' => "Transfers d'argent",
                                // 'transaction_inter_id' => $check->id,
                                'transaction_inter_id' => $transaction_inter->id,
                                // 'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => Auth::guard('web')->user()->id,
                            ]);



                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

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

                                $encode = substr(
                                    $check->wallet->sidUser->phone,
                                    -10
                                );


                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $msg_title = 'Paiement internationnal';
                            $msg = 'Demande de transfers inter été refusé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            // $user_search->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            // $notifs = $user_search->unreadNotifications;
                            // event(new UpdateInterEvent($check));
                            // event(new UserNotifEvent($notifs));
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de transfers refusé'
                                )
                            );

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }
                            $this->resetInterField();
                            $this->emit('modal-hide-inters-validated');
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Transfers refusé !');
                            $notify = new MailController;
                            $notify->newPaiementInter($check);
                        } else {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters-validated');
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Selectionnez le choix du traitement de la demande SVP !');
                        }
                    } else {
                        $this->resetInterField();
                        $this->emit('modal-hide-inters-validated');
                        $this->emit('modal-hide-inters');
                        $this->emit('error', 'Pas le valideur !');
                    }
                } else {
                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('modal-hide-inters-validated');
                    $this->emit('error', 'Mot de passe Incorrect !');
                }
            } elseif ($this->password_transaction = "" ||  $this->interStatus = "") {
                $this->resetInterField();
                $this->emit('modal-hide-inters-validated');
                $this->emit('modal-hide-inters');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
            }
        } catch (\Exception $exception) {
            $this->resetInterField();
            $this->emit('modal-hide-inters-validated');
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Vous avez cliquez deux fois Ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function ChooseUpdateInter($id)
    {
        $walletUpdate = WalletInter::findOrFail($id);

        try {

            if (Auth::guard('web')->user()->is_distributer == 1) {

                $walletUpdate->update([
                    'updated_by' => Auth::guard('web')->user()->id,
                ]);
                event(new ChooseInterEvent($walletUpdate));
                // $this->emit('modal-hide-inters');
                $this->emit('menu-inters-active');
                $this->emit('info', 'Vous avez choisi transfert international');
            } else {

                $walletUpdate->update([
                    'updated_by' => Auth::guard('web')->user()->id,
                ]);
                event(new ChooseInterEvent($walletUpdate));
                // $this->emit('modal-hide-inters');
                $this->emit('menu-inters-active');
                $this->emit('info', 'Vous avez choisi transfert international');
            }
        } catch (\Exception $exception) {
            $this->emit('menu-inters-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

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
        $this->emit('modal-hide-phoneCredit-validated');
        $this->emit('menu-phoneCredit-active');
        // $this->emit('modal-hide-phoneCredit');
    }

    public function cancelPhoneCreditField()
    {
        $this->provider_phone_credit = null;
        $this->number_phone_credit = '';
        $this->amount_phone_credit = '';

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


    public function resetXbetField()
    {
        $this->xbetDescription = '';
        $this->xbet_id = '';
        $this->status_xbet_edit = '';
        $this->provider_xbet_edit = null;
        $this->fullname_xbet_edit = '';
        $this->amount_xbet_edit = '';
        $this->citie_xbet_edit = '';
        $this->countrie_xbet_edit = '';
        $this->account_number_user = '';
        $this->emit('modal-hide-xbet');
    }


    public function resetWaveField()
    {
        $this->waveDescription = '';
        $this->wave_id = '';
        $this->status_wave_edit = '';
        $this->provider_wave_edit = null;
        $this->fullname_wave_edit = '';
        $this->amount_wave_edit = '';
        $this->account_number_user = '';
        $this->emit('modal-hide-wave');
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
        $this->emit('modal-hide-virement');
        $this->emit('modal-hide-virement-validated');
    }

    public function cancelXbetField()
    {
        $this->xbetDescription = '';
        $this->xbet_id = '';
        $this->status_xbet_edit = '';
        $this->provider_xbet_edit = null;
        $this->fullname_xbet_edit = '';
        $this->amount_xbet_edit = '';
        $this->citie_xbet_edit = '';
        $this->countrie_xbet_edit = '';
        $this->account_number_user = '';
        $this->emit('modal-hide-xbet-validated');
        $this->emit('menu-xbet-active');
    }

    public function cancelWaveField()
    {
        $this->waveDescription = '';
        $this->wave_id = '';
        $this->status_wave_edit = '';
        $this->provider_wave_edit = null;
        $this->fullname_wave_edit = '';
        $this->amount_wave_edit = '';
        $this->citie_wave_edit = '';
        $this->countrie_wave_edit = '';
        $this->account_number_user = '';
        // $this->emit('modal-hide-wave');
        $this->emit('modal-hide-wave-validated');
        $this->emit('menu-wave-active');
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


        $transaction = WalletTransaction::query();

        $paiement = WalletPayment::query();

        $wallet = Wallet::query();

        $sid_search = SidUser::query();

        $virement_bank = WalletBank::query();

        $inter_search = WalletInter::query();

        $reload_card = WalletDeposit::query();

        $xbet = WalletXbet::query();

        $wave = WalletWave::query();

        $phone_credit = WalletPhoneCredit::query();

        $walletUser = Auth::guard('web')->user();

        $country_robus = Country::whereId($this->country_inter_out_civ)
            // ->whereStatus(true)
            ->first();

        $default_valeur = 2000000;
        $commission_reload_val_inter = null;
        $commission_reload_val_nat_pay = null;
        $commission_reload_val_nat_receive = null;
        $commission_reload_val_nat_true_pay = null;
        $commission_reload_val_nat_true_receive = null;
        $commission_reload_inter_val_inter_pay = null;
        $commission_reload_inter_val_inter_receive = null;
        $commission_reload_inter_val_inter_true_pay = null;
        $commission_reload_inter_val_inter_true_receive = null;
        $commission_reload_inter_val_nat = null;
        $commission_paiement_invoice_val_nat_pay = null;
        $commission_paiement_invoice_val_nat_receive = null;
        $commission_paiement_invoice_canal_val_nat = null;
        $commission_paiement_invoice_canal_val_inter = null;
        $commission_paiement_invoice_val_inter_pay = null;
        $commission_paiement_invoice_val_inter_receive = null;
        $commission_transaction_val_nat_pay = null;
        $commission_transaction_val_nat_receive = null;
        $commission_transaction_val_inter_pay = null;
        $commission_transaction_val_inter_receive = null;
        $commission_transaction_val_inter_mooney_pay = null;
        $commission_transaction_val_inter_mooney_receive = null;
        $commission_virement_val_nat_pay  = null;
        $commission_virement_val_nat_receive  = null;
        $commission_virement_val_inter_pay  = null;
        $commission_virement_val_inter_receive  = null;
        $commission_phone_credit_val_inter_pay  = null;
        $commission_phone_credit_val_inter_receive  = null;
        $commission_phone_credit_val_nat_pay  = null;
        $commission_phone_credit_val_nat_receive  = null;
        $commission_transaction_val_nal_pay = null;
        $commission_transaction_val_nal_receive = null;
        $code_validated_val_nat = null;
        $code_validated_val_inter = null;
        $amount_received_canal = null;
        $reload_card_valeur = $this->ReloadTransaction;


        if ($walletUser->wallet->sidUser->is_under_distributer) {
            //UnderDistributer party
            abort(403);
        } else {
            //Admin info

            if ($this->statusFilterTransaction != '') {
                $transaction->where(
                    'wallet_transactions.status',
                    $this->statusFilterTransaction
                );
                $this->emit('menu-transaction-active');
            }

            if ($this->statusFilterPaiement != '') {
                $paiement->where(
                    'wallet_payments.status',
                    $this->statusFilterPaiement
                );
                $this->emit('menu-paiement-active');
            }

            if ($this->statusFilterVirement != '') {
                $virement_bank->where(
                    'wallet_banks.status',
                    $this->statusFilterVirement
                );
                $this->emit('menu-virement-active');
            }

            if ($this->statusFilterInter != '') {
                $inter_search->where(
                    'wallet_inters.status',
                    $this->statusFilterInter
                );
                $this->emit('menu-inters-active');
            }

            if ($this->statusFilterReloadCard != '') {
                $reload_card->where(
                    'wallet_deposits.status',
                    $this->statusFilterReloadCard
                );
                $this->emit('menu-reloadCarte-active');
            }

            if ($this->statusFilterPhoneCredit != '') {
                $phone_credit->where(
                    'wallet_phone_credits.status',
                    $this->statusFilterPhoneCredit
                );
                $this->emit('menu-phoneCredit-active');
            }

            if ($this->searchTransaction != '') {
                $this->resetPage();
                $walletUser = Auth::guard('web')->user();

                $transaction
                    ->orwhereHas('transaction', function ($query) {
                        $query
                            ->join('wallets', 'wallet_id', 'wallets.id')
                            ->where(
                                'wallets.country_id',
                                Auth::guard('web')->user()->country->id
                            )
                            ->where(
                                'operator_transaction_name',
                                'like',
                                '%' . $this->searchTransaction . '%'
                            )
                            ->orWhere(
                                'transaction_id',
                                'like',
                                '%' . $this->searchTransaction . '%'
                            )
                            ->orWhere(
                                'trans_comp_id',
                                'like',
                                '%' . $this->searchTransaction . '%'
                            );
                    })
                    ->orWhere(
                        'wallet_transactions.amount',
                        'like',
                        '%' . $this->searchTransaction . '%'
                    )
                    ->orWhere(
                        'wallet_transactions.num_wallet',
                        'like',
                        '%' . $this->searchTransaction . '%'
                    )
                    ->orWhere(
                        'wallet_transactions.phone',
                        'like',
                        '%' . $this->searchTransaction . '%'
                    )
                    ->where('wallet_transactions.status', 'PENDING');

                $this->emit('menu-transaction-active');
            }

            if ($this->searchPaiment != '') {
                $this->resetPage();

                $paiement
                    ->orwhereHas('payment', function ($query) {
                        $query
                            ->join('wallets', 'wallet_id', 'wallets.id')
                            ->where(
                                'wallets.country_id',
                                Auth::guard('web')->user()->country->id
                            )
                            ->where(
                                'operator_transaction_name',
                                'like',
                                '%' . $this->searchPaiment . '%'
                            )
                            ->orWhere(
                                'transaction_id',
                                'like',
                                '%' . $this->searchPaiment . '%'
                            )
                            ->orWhere(
                                'trans_comp_id',
                                'like',
                                '%' . $this->searchPaiment . '%'
                            );
                    })
                    ->orWhere(
                        'wallet_payments.amount',
                        'like',
                        '%' . $this->searchPaiment . '%'
                    )
                    ->orWhere(
                        'wallet_payments.number_phone',
                        'like',
                        '%' . $this->searchPaiment . '%'
                    )
                    ->orWhere(
                        'wallet_payments.name',
                        'like',
                        '%' . $this->searchPaiment . '%'
                    )
                    ->orWhere(
                        'wallet_payments.number_decoder',
                        'like',
                        '%' . $this->searchPaiment . '%'
                    )
                    ->orWhere(
                        'wallet_payments.id_invoice',
                        'like',
                        '%' . $this->searchPaiment . '%'
                    )
                    ->where('wallet_payments.status', 'PENDING');

                $this->emit('menu-paiement-active');
            }

            if ($this->searchVirement != '') {
                $this->resetPage();

                $virement_bank
                    ->orwhereHas('paymentBank', function ($query) {
                        $query
                            ->join('wallets', 'wallet_id', 'wallets.id')
                            ->where(
                                'wallets.country_id',
                                Auth::guard('web')->user()->country->id
                            )
                            ->where(
                                'operator_transaction_name',
                                'like',
                                '%' . $this->searchVirement . '%'
                            )
                            ->orWhere(
                                'transaction_id',
                                'like',
                                '%' . $this->searchVirement . '%'
                            )
                            ->orWhere(
                                'trans_comp_id',
                                'like',
                                '%' . $this->searchVirement . '%'
                            );
                    })
                    ->orWhere(
                        'wallet_banks.amount',
                        'like',
                        '%' . $this->searchVirement . '%'
                    )
                    ->orWhere(
                        'wallet_banks.phone',
                        'like',
                        '%' . $this->searchVirement . '%'
                    )
                    ->orWhere(
                        'wallet_banks.number_compte',
                        'like',
                        '%' . $this->searchVirement . '%'
                    )
                    ->orWhere(
                        'wallet_banks.firstname',
                        'like',
                        '%' . $this->searchVirement . '%'
                    )
                    ->orWhere(
                        'wallet_banks.lastname',
                        'like',
                        '%' . $this->searchVirement . '%'
                    )
                    ->where('wallet_banks.status', 'PENDING');

                $this->emit('menu-virement-active');
            }

            if ($this->seachXbet != '') {
                $this->resetPage();

                $xbet
                    ->orwhereHas('one_bet_transactions', function ($query) {
                        $query
                            ->join('wallets', 'wallet_id', 'wallets.id')
                            ->where(
                                'wallets.country_id',
                                Auth::guard('web')->user()->country->id
                            )
                            ->where(
                                'operator_transaction_name',
                                'like',
                                '%' . $this->seachXbet . '%'
                            )
                            ->orWhere(
                                'transaction_id',
                                'like',
                                '%' . $this->seachXbet . '%'
                            )
                            ->orWhere(
                                'trans_comp_id',
                                'like',
                                '%' . $this->seachXbet . '%'
                            );
                    })
                    ->orWhere(
                        'wallet_xbets.amount',
                        'like',
                        '%' . $this->seachXbet . '%'
                    )
                    ->orWhere(
                        'wallet_xbets.code_drawal',
                        'like',
                        '%' . $this->seachXbet . '%'
                    )
                    ->orWhere(
                        'wallet_xbets.number_compte',
                        'like',
                        '%' . $this->seachXbet . '%'
                    )
                    ->orWhere(
                        'wallet_xbets.full_name',
                        'like',
                        '%' . $this->seachXbet . '%'
                    )
                    ->where('wallet_xbets.status', 'PENDING');

                $this->emit('menu-xbet-active');
            }

            if ($this->searchInter != '') {
                $this->resetPage();

                $inter_search
                    ->orwhereHas('transactionInter', function ($query) {
                        $query
                            ->where(
                                'wallet_inters.country_id',
                                Auth::guard('web')->user()->country->id
                            )
                            ->where(
                                'operator_transaction_name',
                                'like',
                                '%' . $this->searchInter . '%'
                            )
                            ->orWhere(
                                'transaction_id',
                                'like',
                                '%' . $this->searchInter . '%'
                            )
                            ->orWhere(
                                'trans_comp_id',
                                'like',
                                '%' . $this->searchInter . '%'
                            );
                    })
                    ->orWhere(
                        'wallet_inters.amount',
                        'like',
                        '%' . $this->searchInter . '%'
                    )
                    ->orWhere(
                        'wallet_inters.number_compte',
                        'like',
                        '%' . $this->searchInter . '%'
                    )
                    ->orWhere(
                        'wallet_inters.phone',
                        'like',
                        '%' . $this->searchInter . '%'
                    )
                    ->where('wallet_inters.status', 'PENDING');

                $this->emit('menu-inters-active');
            }

            if ($this->searchReloadCard != '') {
                $this->resetPage();

                $reload_card
                    ->orwhereHas('deposit', function ($query) {
                        $query
                            ->join('wallets', 'wallet_id', 'wallets.id')
                            ->where(
                                'wallets.country_id',
                                Auth::guard('web')->user()->country->id
                            )
                            ->where(
                                'operator_transaction_name',
                                'like',
                                '%' . $this->searchReloadCard . '%'
                            )
                            ->orWhere(
                                'transaction_id',
                                'like',
                                '%' . $this->searchReloadCard . '%'
                            )
                            ->orWhere(
                                'trans_comp_id',
                                'like',
                                '%' . $this->searchReloadCard . '%'
                            );
                    })
                    ->orWhere(
                        'wallet_deposits.amount',
                        'like',
                        '%' . $this->searchReloadCard . '%'
                    )
                    ->orWhere(
                        'wallet_deposits.num_carte',
                        'like',
                        '%' . $this->searchReloadCard . '%'
                    )
                    ->orWhere(
                        'wallet_deposits.name',
                        'like',
                        '%' . $this->searchReloadCard . '%'
                    )
                    ->where('wallet_deposits.status', 'PENDING');

                $this->emit('menu-reloadCarte-active');
            }

            if ($this->searchPhoneCredit != '') {
                $this->resetPage();

                $phone_credit
                    ->orwhereHas('paymentPhone', function ($query) {
                        $query
                            ->join('wallets', 'wallet_id', 'wallets.id')
                            ->where(
                                'wallets.country_id',
                                Auth::guard('web')->user()->country->id
                            )
                            ->where(
                                'operator_transaction_name',
                                'like',
                                '%' . $this->searchPhoneCredit . '%'
                            )
                            ->orWhere(
                                'transaction_id',
                                'like',
                                '%' . $this->searchPhoneCredit . '%'
                            )
                            ->orWhere(
                                'trans_comp_id',
                                'like',
                                '%' . $this->searchPhoneCredit . '%'
                            );
                    })
                    ->orWhere(
                        'wallet_phone_credits.amount',
                        'like',
                        '%' . $this->searchPhoneCredit . '%'
                    )
                    ->orWhere(
                        'wallet_phone_credits.phone',
                        'like',
                        '%' . $this->searchPhoneCredit . '%'
                    )
                    ->where('wallet_phone_credits.status', 'PENDING');

                $this->emit('menu-phoneCredit-active');
            }

            //  Code verifier

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


            // Inter Out CIV

            //Reload card commission

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

                        if ($this->provider_reloadcard == 14 || $this->provider_reloadcard == 15) {

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
                        } elseif ($this->provider_reloadcard == 13 || $this->provider_reloadcard == 16 || $this->provider_reloadcard == 17) {

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

                        if ($this->provider_reloadcard  == 14 || $this->provider_reloadcard  == 15) {

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
                        } elseif ($this->provider_reloadcard  == 13 || $this->provider_reloadcard  == 16 || $this->provider_reloadcard  == 17) {

                            foreach ($commission_reload->levelDepositIvories->where('is_inter_bank', false)  as $amount_comission) {
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
            }
            //  Commission valuer paiement Invoice auther

            // Invoice auther

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


                if (
                    $walletUser->wallet->currencie->code == 'EUR' ||
                    $walletUser->wallet->currencie->code == 'DH' ||
                    $walletUser->wallet->currencie->code == 'Dt' ||
                    $walletUser->wallet->currencie->code == 'CEDI' ||
                    $walletUser->wallet->currencie->code == 'USD'
                ) {

                    $commission_invoice = PaymentCost::whereId(1)->first();

                    foreach ($offers  as $offer) {

                        $offer_amount = $offer->whereId($this->offer_paiement)->first();
                        $offer_amount = $offer->whereId($this->offer_paiement)->first();

                        $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                        $amount_received_canal =  $commission_invoice->comission_canal / $walletUser->wallet->currencie->value;


                        $commission_paiement_invoice_canal_val_inter = number_format($valeur_offer / $walletUser->wallet->currencie->value, 2, ',', ' ');
                    }
                } elseif ($walletUser->wallet->currencie->code == 'GNF') {

                    $commission_invoice = PaymentCost::whereId(1)->first();

                    foreach ($offers  as $offer) {

                        $offer_amount = $offer->whereId($this->offer_paiement)->first();

                        $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                        $amount_received_canal = $commission_invoice->comission_canal * $walletUser->wallet->currencie->value;

                        $commission_paiement_invoice_canal_val_inter =  number_format($valeur_offer * $walletUser->wallet->currencie->value, 2, ',', ' ');
                    }
                } elseif ($walletUser->wallet->currencie->code == 'CFA') {



                    if ($walletUser->wallet->country->code = 'CI') {

                        $commission_invoice = PaymentCost::whereId(2)->first();

                        foreach ($offers  as $offer) {

                            $offer_amount = $offer->whereId($this->offer_paiement)->first();

                            $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                            $amount_received_canal = $commission_invoice->comission_canal;

                            $commission_paiement_invoice_canal_val_inter =  number_format($valeur_offer, 2, ',', ' ');
                        }
                    } else {

                        $commission_invoice = PaymentCost::whereId(1)->first();

                        foreach ($offers  as $offer) {

                            $offer_amount = $offer->whereId($this->offer_paiement)->first();

                            $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                            $amount_received_canal = $commission_invoice->comission_canal;

                            $commission_paiement_invoice_canal_val_inter =  number_format($valeur_offer, 2, ',', ' ');
                        }
                    }
                }
            }


            // Virement Banks
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

            // Phone credit national

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


            // Account valeurs transactions User
            $payment_error = $this->getTransactionCountValidated(WalletPayment::class, 'ERROR', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');
            $payment_padding = $this->getTransactionCountValidated(WalletPayment::class, 'PENDING', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');
            $payment_success = $this->getTransactionCountValidated(WalletPayment::class, 'SUCCESS', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');

            $transaction_error = $this->getTransactionCountValidated(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
            $transaction_padding = $this->getTransactionCountValidated(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
            $transaction_success = $this->getTransactionCountValidated(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');

            $bank_error = $this->getTransactionCountValidated(WalletBank::class, 'ERROR', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');
            $bank_padding = $this->getTransactionCountValidated(WalletBank::class, 'PENDING', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');
            $bank_success = $this->getTransactionCountValidated(WalletBank::class, 'SUCCESS', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');

            $deposit_error = $this->getTransactionCountValidated(WalletDeposit::class, 'ERROR', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');
            $deposit_padding = $this->getTransactionCountValidated(WalletDeposit::class, 'PENDING', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');
            $deposit_success = $this->getTransactionCountValidated(WalletDeposit::class, 'SUCCESS', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');

            $phone_credit_error = $this->getTransactionCountValidated(WalletPhoneCredit::class, 'ERROR',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');
            $phone_credit_padding = $this->getTransactionCountValidated(WalletPhoneCredit::class, 'PENDING', 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');
            $phone_credit_success = $this->getTransactionCountValidated(WalletPhoneCredit::class, 'SUCCESS',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');

            $inter_error = $this->getTransactionCountValidated(WalletInter::class, 'ERROR',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');
            $inter_padding = $this->getTransactionCountValidated(WalletInter::class, 'PENDING', 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');
            $inter_success = $this->getTransactionCountValidated(WalletInter::class, 'SUCCESS',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');

            $xbet_error = $this->getTransactionCountValidated(WalletXbet::class, 'ERROR',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');
            $xbet_padding = $this->getTransactionCountValidated(WalletXbet::class, 'PENDING', 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');
            $xbet_success = $this->getTransactionCountValidated(WalletXbet::class, 'SUCCESS',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');

            $wave_error = $this->getTransactionCountValidated(WalletWave::class, 'ERROR',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status');
            $wave_padding = $this->getTransactionCountValidated(WalletWave::class, 'PENDING', 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status');
            $wave_success = $this->getTransactionCountValidated(WalletWave::class, 'SUCCESS',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status');

            // Amount transactions days Error
            $payement_amount_error = $this->getTodayTransactionAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at',  'wallet_payments.status', 'ERROR', 'wallet_payments.updated_by', 'wallet_payments.amount');
            $transaction_amount_error  = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at',  'wallet_transactions.status', 'ERROR', 'wallet_transactions.updated_by',  'wallet_transactions.amount');
            $phonecredit_amount_error  = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at',  'wallet_phone_credits.status', 'ERROR', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.amount');
            $inter_amount_error  = $this->getTodayTransactionAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at',  'wallet_inters.status', 'ERROR', 'wallet_inters.updated_by',  'wallet_inters.amount');
            $bank_amount_error  = $this->getTodayTransactionAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at',  'wallet_banks.status', 'ERROR', 'wallet_banks.updated_by',  'wallet_banks.amount');
            $deposit_amount_error  = $this->getTodayTransactionAmountTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at',  'wallet_deposits.status', 'ERROR', 'wallet_deposits.updated_by',  'wallet_deposits.amount');
            $xbet_amount_error  = $this->getTodayTransactionAmountTotal(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at',  'wallet_xbets.status', 'ERROR', 'wallet_xbets.updated_by',  'wallet_xbets.amount');

            // Amount transactions days Success
            $payement_amount_success = $this->getTodayTransactionAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at',  'wallet_payments.status', 'SUCCESS', 'wallet_payments.updated_by', 'wallet_payments.amount');
            $transaction_amount_success  = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at',  'wallet_transactions.status', 'SUCCESS', 'wallet_transactions.updated_by',  'wallet_transactions.amount');
            $phonecredit_amount_success  = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at',  'wallet_phone_credits.status', 'SUCCESS', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.amount');
            $inter_amount_success  = $this->getTodayTransactionAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at',  'wallet_inters.status', 'SUCCESS', 'wallet_inters.updated_by',  'wallet_inters.amount');
            $bank_amount_success  = $this->getTodayTransactionAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at',  'wallet_banks.status', 'SUCCESS', 'wallet_banks.updated_by',  'wallet_banks.amount');
            $deposit_amount_success  = $this->getTodayTransactionAmountTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at',  'wallet_deposits.status', 'SUCCESS', 'wallet_deposits.updated_by',  'wallet_deposits.amount');
            $xbet_amount_success  = $this->getTodayTransactionAmountTotal(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at',  'wallet_xbets.status', 'SUCCESS', 'wallet_xbets.updated_by',  'wallet_xbets.amount');
            $wave_amount_success  = $this->getTodayTransactionAmountTotal(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at',  'wallet_waves.status', 'SUCCESS', 'wallet_waves.updated_by',  'wallet_waves.amount');


            $search_ = $wallet->where('wallet_id', 'like', '%' . $this->account_number_inter . '%')
                // ->where('status', true)
                ->get();

            return view('livewire.transfers', [
                'walletSolde' => Wallet::where(
                    'sid_user_id',
                    $walletUser->id
                )->sum('amount'),
                'transactions' => $transaction
                    ->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)
                    ->where('wallet_transactions.status', 'PENDING')
                    // ->where('wallet_transactions.type_transaction' , 'Depot')
                    ->select('wallet_transactions.*')
                    ->orderBy('wallet_transactions.created_at', 'desc')
                    ->paginate(15),
                'paiements' => $paiement
                    ->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)
                    ->where('wallet_payments.status', 'PENDING')
                    ->select('wallet_payments.*')
                    ->orderBy('wallet_payments.created_at', 'desc')
                    ->paginate(15),
                'virements' => $virement_bank
                    ->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)
                    ->where('wallet_banks.status', 'PENDING')
                    ->select('wallet_banks.*')
                    ->orderBy('wallet_banks.created_at', 'desc')
                    ->paginate(15),
                'Internationals' => $inter_search
                    ->join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                    // ->where('wallet_inters.country_id', '=', $walletUser->country->id)
                    ->where('wallet_inters.status', 'PENDING')
                    ->select('wallet_inters.*')
                    ->orderBy('wallet_inters.created_at', 'desc')
                    ->paginate(15),
                'reloadCards' => $reload_card
                    ->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)
                    ->where('wallet_deposits.status', 'PENDING')
                    ->select('wallet_deposits.*')
                    ->orderBy('wallet_deposits.created_at', 'desc')
                    ->paginate(15),
                'xbets' => $xbet
                    ->join('wallets', 'wallet_xbets.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)
                    ->where('wallet_xbets.status', 'PENDING')
                    ->select('wallet_xbets.*')
                    ->orderBy('wallet_xbets.created_at', 'desc')
                    ->paginate(15),
                'waves' => $wave
                    ->join('wallets', 'wallet_waves.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)
                    ->where('wallet_waves.status', 'PENDING')
                    ->select('wallet_waves.*')
                    ->orderBy('wallet_waves.created_at', 'desc')
                    ->paginate(15),
                'phoneCredits' => $phone_credit
                    ->join(
                        'wallets',
                        'wallet_phone_credits.wallet_id',
                        'wallets.id'
                    )
                    ->where('wallets.country_id', $walletUser->country->id)
                    ->where('wallet_phone_credits.status', 'PENDING')
                    ->select('wallet_phone_credits.*')
                    ->orderBy('wallet_phone_credits.created_at', 'desc')
                    ->paginate(15),
                'countrie_number_local' => Auth::guard('web')->user()->country
                    ->indecative,

                'wallet_search' => $wallet
                    ->where(
                        'wallet_id',
                        'like',
                        '%' . $this->compte_number_robus . '%'
                    )
                    // ->where('country_id', $walletUser->country->id)
                    // ->where('status', true)
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
                'wallet_search_inter' => $search_,
                'number_search_inter' => $sid_search
                    ->where('phone', 'like', '%' . $this->phone_inter . '%')
                    ->where('is_custumers', true)
                    ->get(),
                'amount_percent' => TransactionCost::where(
                    'status',
                    true
                )->first(),
                'amount_invoice' => PaymentCost::where('status', true)->first(),
                'amount_bank' => BankCost::whereId(2)->first(),
                // Valuer Commissions begin
                'amount_deposit_nation_pay' => $commission_reload_val_nat_true_pay,
                'amount_deposit_nation_receive' => $commission_reload_val_nat_true_receive,
                'amount_deposit_nation_true_pay' => $commission_reload_val_nat_true_pay,
                'amount_deposit_nation_true_receive' => $commission_reload_val_nat_true_receive,
                'amount_deposit_inter' => $commission_reload_val_inter,
                'amount_deposit_inter_nation' => $commission_reload_inter_val_nat,
                'amount_deposit_inter_inter_pay' => $commission_reload_inter_val_inter_pay,
                'amount_deposit_inter_inter_receive' => $commission_reload_inter_val_inter_receive,
                'amount_deposit_inter_inter_true_pay' => $commission_reload_inter_val_inter_true_pay,
                'amount_deposit_inter_inter_true_receive' => $commission_reload_inter_val_inter_true_receive,
                'amount_paiement_invoice_auther_pay' => $commission_paiement_invoice_val_nat_pay,
                'amount_paiement_invoice_auther_receive' => $commission_paiement_invoice_val_nat_receive,
                'amount_paiement_invoice_auther_inter_pay' => $commission_paiement_invoice_val_inter_pay,
                'amount_paiement_invoice_auther_inter_receive' => $commission_paiement_invoice_val_inter_receive,
                'amount_paiement_invoice_canal' => $commission_paiement_invoice_canal_val_nat,
                'amount_paiement_invoice_canal_inter' => $commission_paiement_invoice_canal_val_inter,
                'amount_transaction_under_pay' => $commission_transaction_val_nat_pay,
                'amount_transaction_under_receive' => $commission_transaction_val_nat_receive,
                'amount_transaction_mooney_inter_pay' => $commission_transaction_val_inter_mooney_pay,
                'amount_transaction_mooney_inter_receive' => $commission_transaction_val_inter_mooney_receive,
                'amount_transaction_under_inter_pay' => $commission_transaction_val_inter_pay,
                'amount_transaction_under_inter_receive' => $commission_transaction_val_inter_receive,
                'amount_virement_nation_pay' => $commission_virement_val_nat_pay,
                'amount_virement_nation_receive' => $commission_virement_val_nat_receive,
                'amount_phone_credit_val_pay' => $commission_phone_credit_val_inter_pay,
                'amount_phone_credit_val_receive' => $commission_phone_credit_val_inter_receive,
                'amount_virement_inter_val_pay' => $commission_virement_val_inter_pay,
                'amount_virement_inter_val_receive' => $commission_virement_val_inter_receive,
                'amount_transaction_nat_val_receive' => $commission_transaction_val_nal_receive,
                'amount_transaction_nat_val_pay' => $commission_transaction_val_nal_pay,
                'amount_phone_credit_val_nat_pay' => $commission_phone_credit_val_nat_pay,
                'amount_phone_credit_val_nat_receive' => $commission_phone_credit_val_nat_receive,
                // New val
                'amount_received_canal' => $amount_received_canal,
                // End valeur comissions
                // Code validated
                'code_valided' => $code_validated_val_nat,
                'code_valided_inter' => $code_validated_val_nat,
                'withdrawal_robus' => Provider::whereStatus(true)
                    ->whereId(9)
                    ->get(),
                'withdrawal_robus_inter' => Provider::whereStatus(true)
                    ->whereId(51)
                    ->get(),
                'provide_came_guine' => Provider::whereIn('id', array(49, 48))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_con_bn_gh_lib' => Provider::whereId(48)
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_gha' => Provider::whereIn('id', array(48, 51))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_gabon' => Provider::whereIn('id', array(71, 72))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_mali' => Provider::whereIn('id', array(49, 50, 55))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_west_africa' => Provider::whereIn('id', array(1, 3, 4, 6, 9))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_civ' => Provider::whereIn('id', array(49, 50, 55, 48))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_civ_out' => Provider::whereIn('id', array(49, 50, 48))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_ng_rd_tchad' => Provider::whereId(71)
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_senegal' => Provider::whereId(55)
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_togo' => Provider::whereId(50)
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_burki' => Provider::whereIn('id', array(49, 55))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_ethopie' => Provider::whereIn('id', array(48, 73))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_ouganda' => Provider::whereIn('id', array(71, 48))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_seria' => Provider::whereIn('id', array(49, 74, 75))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_rwanda' => Provider::whereIn('id', array(48, 71))
                    ->orderBy('name', 'asc')
                    ->get(),
                'provide_kenya' => Provider::whereIn('id', array(76, 71, 77))
                    ->orderBy('name', 'asc')
                    ->get(),
                'countrie_ivoires' => Country::whereRobus(true)
                    ->whereIn('id', array(146, 42, 83, 74, 218, 39, 42, 43, 50, 57, 79, 122, 69, 223, 191, 180, 112, 157, 133, 91))
                    ->orderBy('name', 'asc')
                    ->get(),
                'payment_error' => $payment_error,
                'payment_success' => $payment_success,
                'payment_pendding' => $payment_padding,
                'transaction_error' => $transaction_error,
                'transaction_success' => $transaction_success,
                'transaction_pendding' => $transaction_padding,
                'bank_error' => $bank_error,
                'bank_success' => $bank_success,
                'bank_pendding' => $bank_padding,
                'inter_error' => $inter_error,
                'inter_success' => $inter_success,
                'inter_pendding' => $inter_padding,
                'card_error' => $deposit_error,
                'card_success' => $deposit_success,
                'card_pendding' => $deposit_padding,
                'xbets_error' => $xbet_error,
                'xbets_success' => $xbet_success,
                'xbets_pendding' => $xbet_padding,
                'waves_error' => $wave_error,
                'waves_success' => $wave_success,
                'waves_pendding' => $wave_padding,
                'phone_error' => $phone_credit_error,
                'phone_success' => $phone_credit_success,
                'phone_pendding' => $phone_credit_padding,
                'WalletBanks' => $bank_amount_success,
                'WalletDeposits' => $deposit_amount_success,
                'WalletInters' => $inter_amount_success,
                'WalletPayments' => $payement_amount_success,
                'WalletTransactions' => $transaction_amount_success,
                'WalletPhoneCredits' => $phonecredit_amount_success,
                'walletsXbets' => $xbet_amount_success,
                'walletwaves' => $wave_amount_success,
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
                'provider_paiements_inter' => $this->PaymentInterTransaction,
                'provider_virements' => $this->VirementTransaction,
                'provider_inters' => $this->ProviderInterTransaction,
                'provider_inter_virements' => $this->VirmentTransactionInter,
                'provider_reloads' => $this->ReloadTransaction,
                'provider_reload_inters' => $this->RelaodCardInter,
                'provider_credits' => $this->PhoneCreditTransaction,
                'provider_credits_inter' => $this->PhoneCreditTransactionInter,
                'usersCountry' => Auth::guard('web')->user()->wallet->currencie->code,
                'user_auth' => $walletUser,
                'user_send' => Auth::guard('web')->user(),
            ]);
        }
    }

    // Verify ceilling

    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
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
            as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {
        $data = [];

        foreach (WalletDeposit::join(
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
            as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (WalletInter::join(
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
            as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (WalletPayment::join(
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
            as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (WalletTransaction::join(
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
            as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];

        foreach (WalletPhoneCredit::join(
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
            as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }




    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (BankCard::where('created_by', Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
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
            as $reload) {
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

    public function getPaymentInterTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $payment_inter = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 7)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $payment_inter;
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

    public function getProviderInterTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $provider_inter = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 8)
            ->whereIn('providers.provider_type_id', array(8))
            ->distinct()
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
        return $provider_inter;
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

    public function getRelaodCardInterProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $reload_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 11)
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

    public function getPhoneCreditTransactionInterProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $phone_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 9)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $phone_transaction;
    }

    public function getVirmentTransactionInterProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $phone_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 10)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $phone_transaction;
    }

    // Sum Seilver Ceiling
    public function getWalletBanksSeilverCeilingProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
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
            as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    // Transactions All

    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $transaction_status, $status, $walletMy, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '!=', sabdy()->id)
                        ->orWhere($walletMy, '=', sabdy()->id);
                }
            })
            ->whereDate($transaction_days, Carbon::today())
            ->where($transaction_status, $status)
            ->sum($wallet_amount);
    }




    // Transaction account

    public function getTransactionCountValidated($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {
        return $walletModel::join('wallets', $wallet_transactions, '=', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '!=', sabdy()->id)
                        ->orWhere($walletMy, '=', sabdy()->id);
                }
            })
            ->whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status)
            ->count();
    }
}
