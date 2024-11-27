<?php

namespace App\Http\Livewire\TransactionGroupe;

use App\Events\AdminNotifEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Http\Controllers\Admin\MailController;
use App\Mail\CodeValidated;
use App\Models\AdminsCom;
use App\Models\Banks\BankCard;
use App\Models\Costs\TransactionCost;
use App\Models\Custumers\Country;
use App\Models\Invoices\InvoiceTransaction;
use App\Models\Moneys\LevelMoney;
use App\Models\OperatorService;
use App\Models\Payments\Transaction;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class TransactionCiv extends Component
{

    public $step = 1;
    public $transaction_amount_robus;
    public $provider_transaction;
    public $choose_type_transaction;
    public $compte_number_robus;
    public $transaction_amount_money;
    public $transactionId;
    public $transaction_phone;
    public $name_custumer;
    public $num_piece_custumer;
    public $date_piece;
    public $providerCountries = [];
    public $amount_to_pay;
    public $amount_to_receive;
    public $commission;
    public $commission_transaction_val_nat_pay;
    public $commission_transaction_val_admin_receive;
    public $commission_transaction_val_nat_receive;
    public $commission_transaction_money_val_nat_pay;
    public $commission_transaction_money_val_admin_receive;
    public $commission_transaction_money_val_nat_receive;
    public $password_validated;
    public $showPasswordField = 1;
    public $isPasswordValid = false;
    public $transaction_gain_empl;
    public $showRemonterTransButton = false;
    public $showRemonterCreditButton = false;
    public $showRemonterPaiementButton = false;
    public $showRemonterInterButton = false;
    public $showRemonterRechargementButton = false;
    public $showRemonterVirementButton = false;
    public $showRemonterDepotButton = false;
    public $showRemonterRetraitButton = false;
    public $showRemonterMoneyButton = false;
    public $operators;
    public $operatorsTransaction;

    public $showTransButton = false;
    public $showCreditButton = false;
    public $showPaiementButton = false;
    public $showInterButton = false;
    public $showRechargementButton = false;
    public $showVirementButton = false;
    public $showTelInterButton = false;
    public $showRetraitButton = false;
    public $showDepotButton = false;
    public $showMobileButton = false;
    public $showTransacInterButton = false;
    public $showMoneyButton = false;
    public $showBancInterButton = false;
    public $showCarteInterButton = false;
    public $showFacInterButton = false;
    // Compagnies
    public $showRemonterTransCButton = false;
    public $showRemonterCreditCButton = false;
    public $showRemonterPaiementCButton = false;
    public $showRemonterInterCButton = false;
    public $showRemonterRechargementCButton = false;
    public $showRemonterVirementCButton = false;
    public $showRemonterDepotCButton = false;
    public $showRemonterRetraitCButton = false;
    public $showRemonterMoneyCButton = false;
    public $operatorsCompagnie;


    public function mount()
    {

        $this->operators = OperatorService::getOperatorsWithCommissions(sabdy()->id);
        $this->resetValeurReload();
        $this->updateProviderTransaction();
        $this->operatorsTransaction = OperatorService::getOperatorsWithTranactions(sabdy()->id);
        $account_accepted = sabdy()->is_accepted_companie ?? false;
        $company_id = sabdy()->company_id ?? null;
        if ($company_id && $company_id != 0 && $company_id != 1) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }
        if ($sidUserId) {
            $this->operatorsCompagnie = OperatorService::getOperatorsWithCommissions($sidUserId);
        } else {
            $this->operatorsCompagnie = [];
        }

        $restrictionMap = [
            'Active Transactions' => 'showRemonterTransButton',
            'Active Crédits' => 'showRemonterCreditButton',
            'Active Paiements' => 'showRemonterPaiementButton',
            'Active International' => 'showRemonterInterButton',
            'Active Rechargements' => 'showRemonterRechargementButton',
            'Active Virements' => 'showRemonterVirementButton',
            'Active Depot' => 'showRemonterDepotButton',
            'Active Retrait' => 'showRemonterRetraitButton',
            'Active Money' => 'showRemonterMoneyButton',
        ];

        $restrictionCompangieMap = [
            'Active Transactions' => 'showRemonterTransCButton',
            'Active Crédits' => 'showRemonterCreditCButton',
            'Active Paiements' => 'showRemonterPaiementCButton',
            'Active International' => 'showRemonterInterCButton',
            'Active Rechargements' => 'showRemonterRechargementCButton',
            'Active Virements' => 'showRemonterVirementCButton',
            'Active Depot' => 'showRemonterDepotCButton',
            'Active Retrait' => 'showRemonterRetraitCButton',
            'Active Money' => 'showRemonterMoneyCButton',
        ];


        $restrictionMapTransaction = [
            'TRANSFERT CREDIT' => 'showCreditButton',
            'PAIEMENT DE FACTURE' => 'showPaiementButton',
            'TRANSFERT INTERNATIONNAL MOBILE MONEY' => 'showInterButton',
            'RECHARGEMENT DE CARTE' => 'showRechargementButton',
            'VERSEMENT BANCAIRE' => 'showVirementButton',
            'RETRAIT' => 'showRetraitButton',
            'DEPOTS' => 'showDepotButton',
            'MOBILE MONEY' => 'showMoneyButton',
            // 'DEPOT ET TRANSFERT MOBILE MONEY' => 'showMoneyButton',
            'TRANSFERT A INTER' => 'showTransacInterButton',
            'CREDIT TELEPHONIQUE A INTER' => 'showTelInterButton',
            'VERSEMENT BANCAIRE A INTER' => 'showBancInterButton',
            'RECHARGEMENT DE CARTE A INTER' => 'showCarteInterButton',
            'PAIMENT FACTURE A INTER' => 'showFacInterButton',
        ];

        foreach ($this->operatorsTransaction as $operator) {
            $restrictions = explode(',', $operator->name);
            foreach ($restrictionMapTransaction as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $this->{$buttonVar} = true;
                }
            }
        }

        foreach ($this->operators as $operator) {
            $restrictions = explode(',', $operator->name);

            foreach ($restrictionMap as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $this->{$buttonVar} = true;
                }
            }
        }

        foreach ($this->operatorsCompagnie as $operator) {
            $restrictions = explode(',', $operator->name);

            foreach ($restrictionCompangieMap as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $this->{$buttonVar} = true;
                }
            }
        }

    }


    public function getTodayTransactionCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTodayTransactionMobileCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTodayTransactionRobusDepotCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Depot')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }



    public function getTodayTransactionRobusRetraitCommissionAmountTotal($walletModel)
    {

        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }
    public function updatedTransactionAmountRobus()
    {
        // Si la valeur change, mettez à jour les montants
        $this->calculateAmounts();
    }

    public function updatedTransactionAmountMoney()
    {
        // Si la valeur change, mettez à jour les montants
        $this->calculateAmounts();
    }

    public function calculateAmounts()
    {
        // Réinitialisez les montants à chaque calcul
        $this->commission_transaction_val_nat_pay = 0;
        $this->commission_transaction_val_nat_receive = 0;
        $this->commission_transaction_val_admin_receive = 0;

        $this->commission_transaction_money_val_nat_pay = 0;
        $this->commission_transaction_money_val_nat_receive = 0;
        $this->commission_transaction_money_val_admin_receive = 0;

        if ($this->transaction_amount_robus) {
            // Calcul pour les transactions Robus
            $reel_amount = $this->search_amount($this->transaction_amount_robus);

            if ($reel_amount != '') {
                $commission_transaction = $this->getRobusPercentageSDistributers();

                // Calculs
                $this->commission_transaction_val_admin_receive = (float) $commission_transaction->percent * $reel_amount;
                $this->commission_transaction_val_nat_pay = (float) $commission_transaction->percent_deposit * $this->commission_transaction_val_admin_receive;
                $this->commission_transaction_val_nat_receive = (float) $reel_amount - $this->commission_transaction_val_admin_receive;
            }
        }

        if ($this->transaction_amount_money) {
            // Calcul pour les transactions Money
            $reel_amount_money = $this->search_amount($this->transaction_amount_money);

            if ($reel_amount_money != '') {
                if (sabdy()->country->name_groupe == 'CIV') {
                    $commission_transfert_phone = $this->getRobusPercentageMobile();
                } elseif (sabdy()->country->name_groupe == 'MAG') {
                    $commission_transfert_phone = $this->getRobusPercentageMobileMaroc();
                }

                // Calculs
                $this->commission_transaction_money_val_admin_receive = $commission_transfert_phone->percent * $reel_amount_money;
                $this->commission_transaction_money_val_nat_pay = (float) $this->commission_transaction_money_val_admin_receive * (float) $commission_transfert_phone->percent_deposit;
                $valeur_receive = (float) $reel_amount_money;

                if ($valeur_receive < 0) {
                    $this->commission_transaction_money_val_nat_receive = "Montant négatif";
                } else {
                    $this->commission_transaction_money_val_nat_receive = $valeur_receive;
                }
            }
        }
    }

    public function checkPassword()
    {

        if (empty($this->password_validated)) {
            return;
        }

        if (Hash::check($this->password_validated, sabdy()->password)) {
            $this->isPasswordValid = true;
        } else {
            $this->isPasswordValid = false;
        }
    }



    public function resetValeurReload()
    {
        $this->compte_number_robus = '';
        $this->choose_type_transaction = null;
        $this->provider_transaction = null;
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_phone = '';
        $this->name_custumer = '';
        $this->num_piece_custumer = '';
        $this->date_piece = '';
        $this->step = 1;
        // $this->emit('modal-hide-transfers-validated');
        // $this->emit('modal-hide-transfers');
    }

    public function resetTransactionField()
    {
        $this->compte_number_robus = '';
        $this->choose_type_transaction = null;
        $this->provider_transaction = null;
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_phone = '';
        $this->name_custumer = '';
        $this->num_piece_custumer = '';
        $this->date_piece = '';
        $this->step = 1;
        $this->emit('modal-hide-transfers-validated');
        $this->emit('modal-hide-transfers');
        $this->emit('transaction_national');
    }


    public function cancelTransactionField()
    {
        $this->compte_number_robus = '';
        $this->choose_type_transaction = null;
        $this->provider_transaction = null;
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_phone = '';
        $this->name_custumer = '';
        $this->num_piece_custumer = '';
        $this->date_piece = '';
        $this->step = 1;
        // $this->emit('transaction_national');
        $this->emit('modal-hide-inters-validated');
        $this->emit('cancelTransactionInTransfers');
        $this->emit('modal-hide-transfers');
    }

    public function addTransaction()
    {
        $provider = Provider::whereId($this->provider_transaction)->first();
        $som_transfers =  $this->getSumValTransactionProperty();
        // Verifier plafond days
        $valeur_ceiling = sabdy()->silver_ceiling;

        try {

            if ($provider) {



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

                    $this->transaction_amount_robus = htmlspecialchars($this->transaction_amount_robus, ENT_QUOTES, 'UTF-8');
                    $this->compte_number_robus = htmlspecialchars($this->compte_number_robus, ENT_QUOTES, 'UTF-8');

                    $this->step = 2;
                } else {

                    $this->validate([
                        'transaction_amount_money' => 'required',
                        'transaction_phone' => 'required',
                    ], [
                        'transaction_amount_money.required' => 'Votre montant est obligatoire',
                        'transaction_phone.required' => 'le numero est obligatoire',

                    ]);


                    $this->step = 2;
                }


                if (!$this->validateAmount()) {
                    return;
                }

                if (!$this->validateDailyLimit($som_transfers, $valeur_ceiling)) {
                    return;
                }
            } else {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers');
                $this->emit('error', 'Erreur de choix !');
            }
        } catch (\Exception $exception) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function validatedPassword()
    {
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();
        // Verifier plafond days
        $valeur_ceiling = sabdy()->silver_ceiling;
        $valeur_max_send  =  sabdy()->country->amount_max_send;
        $provider = Provider::whereId($this->provider_transaction)->first();


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

                $this->password_validated = "";

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


                    $this->transaction_amount_robus = htmlspecialchars($this->transaction_amount_robus, ENT_QUOTES, 'UTF-8');
                    $this->compte_number_robus = htmlspecialchars($this->compte_number_robus, ENT_QUOTES, 'UTF-8');

                    // Search Wallet Send
                    $wallet_search = Wallet::where('wallet_id', $this->compte_number_robus)
                        ->where('country_id', sabdy()->country->id)
                        // ->where('status', true)
                        ->first();

                    $reel_amount =  $this->search_amount($this->transaction_amount_robus);


                    if (sabdy()->is_under_distributer) {

                        $percent_robus = $this->getRobusPercentageSDistributers();
                        $isCustomer = $wallet_search->sidUser->is_custumers;
                        $isNotDistributor = !$wallet_search->sidUser->is_under_distributer;
                        $isNotAdmin = !$wallet_search->sidUser->is_admin;
                        $isNotDistributor = !$wallet_search->sidUser->is_distributer;

                        if ($isCustomer && $isNotDistributor && $isNotAdmin && $isNotDistributor) {


                            if ($percent_robus !== null) {

                                if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                    if ($this->validateAmount()) {

                                        $valeur_received = $reel_amount - ($percent_robus->percent * $reel_amount);

                                        // Update Info valeurs
                                        $walletAmountSend = Wallet::where('wallet_id',   $this->compte_number_robus)->first();

                                        if ($walletAmountSend) {

                                            $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                            $walletTransaction =  $this->createRobusWalletTransaction($reel_amount, $percent_robus, $provider, $this->compte_number_robus, $amount_solde_drawal);

                                            if ($walletTransaction != null) {

                                                if ($this->updateAmountRobusReceived($this->compte_number_robus, $walletTransaction, $valeur_received)) {

                                                    $message = 'Vous venez de recevoir un montant de ' . $reel_amount . ' de la part de ' . sabdy()->firstname . ' ' . sabdy()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal . $wallet_search->currencie->name . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                                    if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

                                                        $encode = substr($wallet_search->sidUser->phone,  -10);

                                                        http::get("//app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                                    }

                                                    if ($wallet_search->sidUser->email != null) {

                                                        Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Message dépot : '  . $message));
                                                    }

                                                    if ($this->processCommissionsDistributerRobus($reel_amount, $percent_robus, $walletTransaction, $admin_civ)) {
                                                        $this->sendNotification($walletTransaction);
                                                        $this->resetTransactionField();
                                                        $this->emit('modal-hide-transfers');
                                                        $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                                    } else {

                                                        $this->resetTransactionField();
                                                        $this->emit('modal-hide-transfers');
                                                        $this->emit('error', 'Error de transferts du montant!');
                                                    }
                                                }
                                            } else {

                                                $this->resetTransactionField();
                                                $this->emit('modal-hide-transfers');
                                                $this->emit('error', 'Error de creation de transfert du montant!');
                                            }
                                        } else {
                                            $this->resetTransactionField();
                                            $this->emit('modal-hide-transfers');
                                            $this->emit('error', 'Error verification du wallet !');
                                        }
                                    } else {

                                        $this->resetTransactionField();
                                        $this->emit('modal-hide-transfers');
                                        $this->emit('error', 'Verifié votre solde !!');
                                    }
                                } else {

                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('error', 'Votre montant journalier est atteint !');
                                }
                            } else {

                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers');
                                $this->emit('error', 'Niveau de pourcentage introuvable !');
                            }
                        } else {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount()) {

                                    $valeur_received = $reel_amount;

                                    // Update Info valeurs
                                    $walletAmountSend = Wallet::where('wallet_id',  $this->compte_number_robus)->first();

                                    if ($walletAmountSend) {

                                        $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;


                                        $walletTransaction =  $this->createRobusWalletTransaction($reel_amount, $percent_robus, $provider, $this->compte_number_robus, $amount_solde_drawal);

                                        if ($walletTransaction != null) {

                                            $message = 'Vous venez de recevoir un montant de ' . $this->compte_number_robus . ' de la part de ' . sabdy()->firstname . ' ' . sabdy()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal . $wallet_search->currencie->name . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                            if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

                                                $encode = substr($wallet_search->sidUser->phone,  -10);

                                                http::get("//app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                            }

                                            if ($wallet_search->sidUser->email != null) {

                                                Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Message dépot : '  . $message));
                                            }
                                            if ($this->updateAmountRobusReceivedDistributer($this->compte_number_robus, $walletTransaction)) {
                                                if ($this->processCommissionsRobusDistributer($reel_amount)) {
                                                    $this->sendNotification($walletTransaction);
                                                    $this->resetTransactionField();
                                                    $this->emit('modal-hide-transfers');
                                                    $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                                } else {

                                                    $this->resetTransactionField();
                                                    $this->emit('modal-hide-transfers');
                                                    $this->emit('error', 'Error de transferts du montant  !!');
                                                }
                                            }
                                        } else {

                                            $this->resetTransactionField();
                                            $this->emit('modal-hide-transfers');
                                            $this->emit('error', 'Error de createtion transferts !!');
                                        }
                                    } else {
                                        $this->resetTransactionField();
                                        $this->emit('modal-hide-transfers');
                                        $this->emit('error', 'Error verification du wallet !!');
                                    }
                                } else {
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('error', 'Verifié votre solde !!');
                                }
                            } else {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers');
                                $this->emit('error', 'Votre montant journalier est atteint  !!');
                            }
                        }
                    } else {

                        $percent_robus = $this->getRobusPercentageCustumers();

                        // Code validated
                        $isWalletSearchNull = $wallet_search === null;
                        $isCustomer = $wallet_search->sidUser->is_custumers;
                        $isCustomerStatusFalse = $wallet_search->sidUser->status === false;
                        $isSameWalletAsUser = $this->compte_number_robus === sabdy()->wallet->wallet_id;
                        $isUnderDistributor = $wallet_search->sidUser->is_under_distributer === true;
                        $isAdmin_all = $wallet_search->sidUser->is_admin === true;

                        // Is custumers
                        if ($percent_robus !== null) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount()) {

                                    $valeur_received = $reel_amount;

                                    // Update Info valeurs
                                    $walletAmountSend = Wallet::where('wallet_id',  $this->compte_number_robus)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                    if ($walletAmountSend) {

                                        $walletTransaction = $this->createRobusWalletTransaction($reel_amount, $percent_robus, $provider, $this->compte_number_robus, $amount_solde_drawal);

                                        if ($walletTransaction != null) {

                                            $message = 'Vous venez de recevoir un montant de ' . $this->compte_number_robus . ' de la part de ' . sabdy()->firstname . ' ' . sabdy()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal . $wallet_search->currencie->name . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                            if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

                                                $encode = substr($wallet_search->sidUser->phone,  -10);

                                                http::get("//app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                            }

                                            if ($wallet_search->sidUser->email != null) {

                                                Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Message dépot : '  . $message));
                                            }
                                            if ($this->updateAmountRobusReceived($this->compte_number_robus, $walletTransaction, $valeur_received)) {
                                                if ($this->processCommissionsCustumerRobus($reel_amount, $percent_robus, $walletTransaction, $admin_civ)) {
                                                    $this->sendNotification($walletTransaction);
                                                    $this->resetTransactionField();
                                                    $this->emit('modal-hide-transfers');
                                                    $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                                } else {
                                                    $this->resetTransactionField();
                                                    $this->emit('modal-hide-transfers');
                                                    $this->emit('error', 'Error de transferts du montant  !!');
                                                }
                                            }
                                        } else {

                                            $this->resetTransactionField();
                                            $this->emit('modal-hide-transfers');
                                            $this->emit('error', 'Error de creation de transfert du montant !!');
                                        }
                                    } else {
                                        $this->resetTransactionField();
                                        $this->emit('modal-hide-transfers');
                                        $this->emit('error', 'Error de verification du wallet !!');
                                    }
                                } else {

                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('error', 'Verifié votre solde SVP  !!');
                                }
                            } else {

                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers');
                                $this->emit('error', 'Votre plafond quotidien est atteint  !!');
                            }
                        } else {

                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Niveau de pourcentage introuvable !!');
                        }
                    }
                } else {

                    if (sabdy()->is_under_distributer) {


                        $this->validate([
                            'transaction_amount_money' => 'required',
                            'transaction_phone' => 'required',
                            // "name_custumer" => 'required',
                            // "num_piece_custumer" => 'required',
                            // "date_piece" => 'required',
                        ], [
                            'transaction_amount_money.required' => 'Votre montant est obligatoire',
                            'transaction_phone.required' => 'le numero est obligatoire',
                            // 'name_custumer.required' => 'Nom est obligatoire',
                            // 'num_piece_custumer.required' => 'Numero piece est obligatoire',
                            // 'date_piece.required' => 'date de validité est obligatoire',

                        ]);



                        if (sabdy()->country->name_groupe == 'CIV') {

                            $percent_robus = $this->getRobusPercentageMobile();
                        } elseif (sabdy()->country->name_groupe == 'MAG') {

                            $percent_robus = $this->getRobusPercentageMobileMaroc();
                        } else {

                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Pas de transfert Mobile Money!');
                        }
                    } else {


                        $this->validate([
                            'transaction_amount_money' => 'required',
                            'transaction_phone' => 'required',
                        ], [
                            'transaction_amount_money.required' => 'Votre montant est obligatoire',
                            'transaction_phone.required' => 'le numero est obligatoire',

                        ]);


                        if (sabdy()->country->name_groupe == 'CIV') {

                            $percent_robus = $this->getRobusPercentageMobileTwo();
                        } else {

                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Pas de Transfert Mobile Money dans la zone !');
                        }
                    }

                    $reel_amount =  $this->search_amount($this->transaction_amount_money);


                    if ($percent_robus !== null) {

                        if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                            if ($this->validateAmountSendLimit($reel_amount, $valeur_max_send)) {

                                if (sabdy()->is_under_distributer) {

                                    if (sabdy()->country->name_groupe == 'CIV') {

                                        $valeur_received = $reel_amount;

                                        $amount_payment = $reel_amount;
                                    } else {

                                        $valeur_received = $reel_amount - ($percent_robus->percent * $reel_amount);

                                        $amount_payment =  $reel_amount + ($percent_robus->percent * $reel_amount);
                                    }

                                    $cost_payment = $percent_robus->percent *  $reel_amount;
                                } else {

                                    $valeur_received = $reel_amount - ($percent_robus->percent * $reel_amount);

                                    $cost_payment = $percent_robus->percent *  $reel_amount;

                                    $amount_payment =  $reel_amount + ($percent_robus->percent * $reel_amount);
                                }

                                if ($this->validateVerifyAmount($amount_payment)) {

                                    $walletTransaction =  $this->createRobusWalletTransactionDistributer($percent_robus, $provider, $reel_amount, $cost_payment, $this->transaction_phone);

                                    if ($walletTransaction != null) {

                                        if ($this->updateAmountRobusReceivedMobile($walletTransaction, $valeur_received)) {

                                            if (sabdy()->is_under_distributer) {
                                                $processCommission = $this->processCommissionsMobileDistributer($reel_amount, $percent_robus, $walletTransaction, $admin_civ);
                                            } else {

                                                $processCommission = $this->processCommissionsMobile($percent_robus, $walletTransaction, $admin_civ);
                                            }

                                            if ($processCommission) {

                                                $this->sendNotificationMobile($walletTransaction);

                                                $this->resetTransactionField();
                                                $this->emit('modal-hide-transfers');
                                                $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                            } else {

                                                $this->resetTransactionField();
                                                $this->emit('modal-hide-transfers');
                                                $this->emit('error', 'Error de transferts du montant!');
                                            }
                                        }
                                    } else {

                                        $this->resetTransactionField();
                                        $this->emit('modal-hide-transfers');
                                        $this->emit('error', 'Error de createtion transferts !');
                                    }
                                } else {

                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('error', 'Verifié votre solde SVP !');
                                }
                            } else {

                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers');
                                $this->emit('error', "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . robus()->country->currencie->code);
                            }
                        } else {
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Votre montant journalier est atteint !');
                        }
                    } else {
                        $this->resetTransactionField();
                        $this->emit('modal-hide-transfers');
                        $this->emit('error', 'Niveau de pourcentage introuvable !');
                    }
                }
            } else {

                $this->resetTransactionField();
                $this->emit('modal-hide-transfers');
                $this->emit('error', 'Merci de bien vouloir vérifier votre mot de passe, s\'il vous plaît.!');
            }
        } catch (\Exception $exception) {
            $this->resetTransactionField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function render()
    {


        $transaction = WalletTransaction::query();


        return view('livewire.transaction-groupe.transaction-civ', [
            'walletSolde' => Wallet::where(
                'sid_user_id',
                sabdy()->id
            )->sum('amount'),
            'transactions' => $transaction
                ->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallet_transactions.status', 'PENDING')
                ->where('wallet_transactions.created_by', sabdy()->id)
                ->select('wallet_transactions.*')
                ->orderBy('wallet_transactions.created_at', 'desc')
                ->paginate(15),
            'amount_transaction_nat_val_pay' => $this->commission_transaction_val_nat_pay,
            'amount_transaction_nat_val_percent' => $this->commission_transaction_val_admin_receive,
            'amount_transaction_nat_val_receive' => $this->commission_transaction_val_nat_receive,
            'amount_transaction_nat_money_val_pay' => $this->commission_transaction_money_val_nat_pay,
            'amount_transaction_nat_money_val_percent' => $this->commission_transaction_money_val_admin_receive,
            'amount_transaction_nat_money_val_receive' => $this->commission_transaction_money_val_nat_receive,
            'usersCountry' => sabdy()->wallet->currencie->code,
            'showRemonterTransButton' => $this->showRemonterTransButton,
            'showRemonterCreditButton' => $this->showRemonterCreditButton,
            'showRemonterPaiementButton' => $this->showRemonterPaiementButton,
            'showRemonterInterButton' => $this->showRemonterInterButton,
            'showRemonterRechargementButton' => $this->showRemonterRechargementButton,
            'showRemonterVirementButton' => $this->showRemonterVirementButton,
            'showRemonterMoneyButton' => $this->showRemonterMoneyButton,
            'showRemonterRetraitButton' => $this->showRemonterRetraitButton,
            'showRemonterDepotButton' => $this->showRemonterDepotButton,
            'showTransButton' => $this->showTransButton,
            'showCreditButton' => $this->showCreditButton,
            'showPaiementButton' => $this->showPaiementButton,
            'showInterButton' => $this->showInterButton,
            'showRechargementButton' => $this->showRechargementButton,
            'showVirementButton' => $this->showVirementButton,
            'showMoneyButton' => $this->showMoneyButton,
            'showRetraitButton' => $this->showRetraitButton,
            'showDepotButton' => $this->showDepotButton,
            'showTransacInterButton' => $this->showTransacInterButton,
            'showTelInterButton' => $this->showTelInterButton,
            'showBancInterButton' => $this->showBancInterButton,
            'showCarteInterButton' => $this->showCarteInterButton,
            'showFacInterButton' => $this->showFacInterButton,
            'providersMoney' =>  $this->getProvidersMoneyByType(new Provider, 1),
            'providersAlls' =>  $this->getProvidersByAllType(new Provider, 1),
            'providersDepot' =>  $this->getProvidersByType(new Provider, 1),
            'countrie_number_local' => sabdy()->country
                ->indecative,

        ]);
    }


    public function getRobusPercentageMobile()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(27)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageMobileMaroc()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'MAG':
                return TransactionCost::whereId(44)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageMobileTwo()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(29)->first();
            default:
                return null;
        }
    }

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }

    public function getRobusPercentageSDistributers()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

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
            case 'ASIE':
                return TransactionCost::whereId(44)->first();
            case 'AFE':
                return TransactionCost::whereId(45)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageCustumers()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

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
            case 'ASIE':
                return TransactionCost::whereId(46)->first();
            case 'AFE':
                return TransactionCost::whereId(48)->first();
            default:
                return null;
        }
    }

    public function updateProviderTransaction()
    {



        $this->providerCountries = $this->getProvidersByType(new Provider, 1);
        $this->compte_number_robus = '';
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_phone = '';
        $this->name_custumer = '';
        $this->num_piece_custumer = '';
        $this->date_piece = '';

        return $this->providerCountries;
    }


    public static function getProvidersByAllType($query, $providerType)
    {

        $providers = $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            // ->where('providers.id' , 9)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();


        return $providers;
    }

    public static function getProvidersByType($query, $providerType)
    {

        $providers = $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            ->where('providers.id', 9)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();


        return $providers;
    }


    public static function getProvidersMoneyByType($query, $providerType)
    {

        $providers = $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            ->where('providers.id', '!=', 9)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();


        return $providers;
    }


    private function sendNotification($walletTransaction)
    {

        if (sabdy()->is_custumers) {
            if (sabdy()->country->code == "CI") {

                $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  sabdy()->wallet->currencie->code . ' ' .   ' par '
                    . $walletTransaction->provider->name  .  ' le '
                    . $walletTransaction->created_at . ' votre transfert effectué avec success ' . 'Robus vous remercie';

                $encode = substr(sabdy()->phone,  -10);

                http::get("//app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
            }
        }
        $msg_title = 'Transfert Notional Robus';
        $msg = 'Vous avez effectué un transfert vers Robus avec succes';
        sabdy()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletTransfersEvent($walletTransaction));
        // $this->emit('transaction_inter_sends', $walletTransaction->id);
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus vers le client ' .
                    ' ' . $walletTransaction->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfert d\'argent Robus vers ' .
            ' ' . $walletTransaction->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (sabdy()->email != null) {
            $notify = new MailController();
            $notify->newTransaction($walletTransaction);
        }

        $this->resetTransactionField();
        $this->emit('modal-hide-transfers');
        $this->emit('success', 'Transfers effectué !');
    }

    private function sendNotificationMobile($walletTransaction)
    {

        if (sabdy()->is_custumers) {
            if (sabdy()->country->code == "CI") {

                $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                    . $walletTransaction->provider->name  .  ' le '
                    . $walletTransaction->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';

                $encode = substr(sabdy()->phone,  -10);

                http::get("//app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
            }
        }

        $msg_title = 'Transfert Mobile money';
        $msg = 'Vous avez effectué un transfert vers Mobile Money ';
        sabdy()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletTransfersEvent($walletTransaction));
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus vers le client ' .
                    ' ' . $walletTransaction->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfert d\'argent Mobile Money vers ' .
            ' ' . $walletTransaction->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (sabdy()->email != null) {
            $notify = new MailController();
            $notify->newTransaction($walletTransaction);
        }

        $this->resetTransactionField();
        $this->emit('modal-hide-transfers');
        $this->emit('success', 'Transfers effectué !');
    }

    private function updateAmountRobusReceivedMobile($walletTransaction, $valeur_received)
    {

        $commission_transaction_val_inter_convertion_send = (float)  $valeur_received;

        if ($commission_transaction_val_inter_convertion_send) {

            $walletTransaction->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            return true;
        } else {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }
    }

    // Wallet Robus
    private function updateAmountRobusReceived($compte_number_robus, $walletTransaction, $valeur_received)
    {

        $wallet_search = Wallet::where('wallet_id', $compte_number_robus)
            ->where('country_id', sabdy()->country->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));


        $commission_transaction_val_inter_convertion_send = (float)  $valeur_received;

        if ($commission_transaction_val_inter_convertion_send) {

            $walletTransaction->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            if ($wallet_search) {

                $wallet_search->update([
                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                    'sid_user_relod' => sabdy()->id,
                    'amount' => DB::raw('amount+' . $commission_transaction_val_inter_convertion_send),
                ]);
            } else {
                return false;
            }

            $transaction = Transaction::create([
                'wallet_transaction_id' => $walletTransaction->id,
                'sid_user_id' => sabdy()->id,
                'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode,
                'operator_transaction_name' => $walletTransaction->provider->name,
                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                'pay_amount' => $walletTransaction->amount,
                'fee_amount' =>  $walletTransaction->costs_amount,
                'received_amount' => $walletTransaction->amount,
                'status' => $walletTransaction->status,
            ]);

            InvoiceTransaction::create([
                'title' => "Transfers d'argent",
                'wallet_transaction_id' => $walletTransaction->id,
                'transactions_id' => $transaction->id,
                'company_id' => sabdy()->company_id,
                'description' => $walletTransaction->description,
                'status' => $walletTransaction->status,
                'created_by' => sabdy()->id,
            ]);

            return true;
        } else {

            return false;
        }
    }


    public function processCommissionsCustumerRobus($transaction_amount_robus, $percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_robus;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        // Percent admin 
        $valeur_percent_admin  = $percent_robus->percent * $transaction_amount_robus;

        if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

            $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
        } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

            $admin_valeur_convertion = (float)   $valeur_percent_admin;
        } else {

            $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
        }

        // if ($admin_civ->count() > 0) {

        //     $nmbre_admin = $admin_civ->count();

        //     $valeur_single = $admin_valeur_convertion / $nmbre_admin;

        //     foreach ($admin_civ as $user) {
        //         $admin_single = SidUser::find($user->id);

        //         AdminsCom::create([
        //             'amount' =>   $valeur_single,
        //             'sid_user_id' =>   $admin_single->id,
        //             'wallet_transaction_id' => $walletTransaction->id,
        //             'amount_with_draw' =>  0,
        //             'description' => 'Commission administrateur',
        //         ]);
        //     }
        // }


        return true;
    }

    public function processCommissionsDistributerRobus($transaction_amount_robus, $percent_robus, $walletTransaction, $admin_civ)
    {
        $commission = $transaction_amount_robus;
        $country_civ = Country::find(107);
        $wallet = sabdy()->wallet;

        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        $valeur_percent_admin = $percent_robus->percent * $transaction_amount_robus;

        if (sabdy()->is_under_distributer) {


            $valeur_admin = $percent_robus->percent_admin * $valeur_percent_admin;
            $valuer_deposit = $percent_robus->percent_deposit * $valeur_percent_admin;
            $valuer_with_drawal = $percent_robus->percent_with_drawal * $valeur_percent_admin;

            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {
                $admin_valeur_convertion_drawal = $valuer_with_drawal * (float)sabdy()->country->currencie->value;
                $admin_valeur_convertion = (float)$valeur_admin * (float)sabdy()->country->currencie->value;
                $admin_convertion = (float)$valeur_percent_admin * (float)sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
                $admin_valeur_convertion_drawal = $valuer_with_drawal;
                $admin_valeur_convertion = (float)$valeur_admin;
                $admin_convertion = (float)$valeur_percent_admin;
            } else {
                $admin_valeur_convertion_drawal = $valuer_with_drawal * (float)sabdy()->country->currencie->value;
                $admin_valeur_convertion = (float)$valeur_admin * (float)sabdy()->country->currencie->value;
                $admin_convertion = (float)$valeur_percent_admin * (float)sabdy()->country->currencie->value;
            }

            if ($walletTransaction->type_transaction == "Depot") {

                if ($this->showRemonterDepotButton) {

                    $walletTransaction->increment('commissions', $valuer_deposit);

                    $account_accepted = sabdy()->is_accepted_companie ?? false;

                    if ($account_accepted && $this->showRemonterDepotCButton) {
                        $valuer_companie = $percent_robus->percent_companie * $valeur_percent_admin;

                        $walletTransaction->increment('commission_companie', $valuer_companie);
                    }

                    if ($admin_civ->count() > 0) {
                        $nmbre_admin = $admin_civ->count();
                        $valeur_single = ($admin_valeur_convertion + $admin_valeur_convertion_drawal) / $nmbre_admin;

                        foreach ($admin_civ as $user) {
                            $this->processAdminCommissionSDistributer($user->id, $walletTransaction->id, $valeur_single, $valuer_deposit);
                        }
                    }
                } else {

                    if ($admin_civ->count() > 0) {
                        $nmbre_admin = $admin_civ->count();
                        $valeur_single = ($admin_convertion) / $nmbre_admin;

                        foreach ($admin_civ as $user) {
                            $this->processAdminCommissionSDistributer($user->id, $walletTransaction->id, $valeur_single, $valuer_deposit);
                        }
                    }
                }
            } elseif ($walletTransaction->type_transaction == "Retrait") {

                if ($this->showRemonterRetraitButton) {
                    $walletTransaction->increment('commissions', $valuer_deposit);

                    $account_accepted = sabdy()->is_accepted_companie ?? false;

                    if ($account_accepted && $this->showRemonterRetraitCButton) {
                        $valuer_companie = $percent_robus->percent_companie * $valeur_percent_admin;
                        $walletTransaction->increment('commission_companie', $valuer_companie);
                    }

                    if ($admin_civ->count() > 0) {
                        $nmbre_admin = $admin_civ->count();
                        $valeur_single = ($admin_valeur_convertion + $admin_valeur_convertion_drawal) / $nmbre_admin;

                        foreach ($admin_civ as $user) {
                            $this->processAdminCommissionSDistributer($user->id, $walletTransaction->id, $valeur_single, $valuer_deposit);
                        }
                    }
                } else {

                    if ($admin_civ->count() > 0) {
                        $nmbre_admin = $admin_civ->count();
                        $valeur_single = ($admin_convertion) / $nmbre_admin;

                        foreach ($admin_civ as $user) {
                            $this->processAdminCommissionSDistributer($user->id, $walletTransaction->id, $valeur_single, $valuer_deposit);
                        }
                    }
                }
            } else {

                if ($admin_civ->count() > 0) {
                    $nmbre_admin = $admin_civ->count();
                    $valeur_single = ($admin_convertion) / $nmbre_admin;

                    foreach ($admin_civ as $user) {
                        $this->processAdminCommissionSDistributer($user->id, $walletTransaction->id, $valeur_single, $valuer_deposit);
                    }
                }
            }
        } else {

            $valeur_percent_admin = $valeur_percent_admin;
            $admin_valeur_convertion = $this->convertAdminCommission($valeur_percent_admin);

            if ($admin_civ->count() > 0) {
                $nmbre_admin = $admin_civ->count();
                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $this->processAdminCommission($user->id, $walletTransaction->id, $valeur_single);
                }
            }
        }

        return true;
    }

    private function processAdminCommissionSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
    {
        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            'amount_under' =>  sabdy()->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $admin_single->wallet->increment('amount', $amount);
    }

    private function processAdminCommission($userId, $walletTransactionId, $amount)
    {
        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'status'  => "SUCCESS",
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
        ]);

        $admin_single->wallet->increment('amount', $amount);
    }

    private function convertAdminCommission($amount)
    {
        $country_civ = Country::find(107);
        $robus_currency = sabdy()->country->currencie->value;

        if ($country_civ->currencie->value > sabdy()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }

    private function updateAmountRobusReceivedDistributer($compte_number_robus, $walletTransaction)
    {


        $wallet_search = Wallet::where('wallet_id', $compte_number_robus)
            ->where('country_id', sabdy()->country->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));

        if ($walletTransaction) {

            $walletTransaction->update(['amount_received' => $walletTransaction->amount_received]);

            if ($wallet_search) {

                $wallet_search->update([
                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                    'sid_user_relod' => sabdy()->id,
                    'amount' => DB::raw('amount+' . $walletTransaction->amount_received),
                ]);
            } else {

                $this->resetTransactionField();
                $this->emit('modal-hide-transfers');
                $this->emit('error', 'Numero Wallet introuvable!');
                return false;
            }

            $transaction = Transaction::create([
                'wallet_transaction_id' => $walletTransaction->id,
                'sid_user_id' => sabdy()->id,
                'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode,
                'operator_transaction_name' => $walletTransaction->provider->name,
                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                'pay_amount' => $walletTransaction->amount,
                'fee_amount' =>  $walletTransaction->costs_amount,
                'received_amount' => $walletTransaction->amount,
                'status' => $walletTransaction->status,
            ]);

            InvoiceTransaction::create([
                'title' => "Transfers d'argent",
                'wallet_transaction_id' => $walletTransaction->id,
                'transactions_id' => $transaction->id,
                'company_id' => sabdy()->company->id,
                'description' => $walletTransaction->description,
                'status' => $walletTransaction->status,
                'created_by' => sabdy()->id,
            ]);

            return true;
        } else {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }
    }
    // Validated wallet Robus

    private function createRobusWalletTransaction($transaction_amount_robus, $percent_robus, $provider, $compte_number_robus, $amount_drawal)
    {
        $val_amount = $this->transaction_amount_robus;
        $amount_solde = (float)sabdy()->wallet->amount - (float)$val_amount;

        return WalletTransaction::create(
            [
                'wallet_id' => sabdy()->wallet->id,
                'amount' => $transaction_amount_robus,
                'description' => "Transfert d'argent vers Robus",
                'transaction_cost_id' =>  $percent_robus->id,
                'phone' =>  null,
                'costs_amount' => $percent_robus->percent * $transaction_amount_robus,
                'amount_received' => $transaction_amount_robus - ($percent_robus->percent * $transaction_amount_robus),
                'num_wallet' => strtoupper($compte_number_robus),
                'name_complet' => null,
                'num_piece' => null,
                'validated_date' => null,
                'created_by' => sabdy()->id,
                'provider_id' => $provider->id,
                'choose_type_transaction' => 1,
                'type_transaction' => 'Depot',
                'type_received' => 'Transfert',
                'status' => 'SUCCESS',
                'amount_solde' => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_drawal ?? null
            ]
        );
    }

    private function createRobusWalletTransactionDistributer($percent_robus, $provider, $transaction_amount_money, $valuer_deposit_com, $transaction_phone)
    {

        $val_amount = $transaction_amount_money;

        $amount_solde = (float)sabdy()->wallet->amount - (float)$val_amount;

        return WalletTransaction::create(
            [
                'wallet_id' =>   sabdy()->wallet->id,
                'amount' => $transaction_amount_money,
                'description'  => $transaction_description ?? null,
                'transaction_cost_id' => $percent_robus->id,
                'phone'   => sabdy()->country->indecative . $transaction_phone,
                'created_by' => sabdy()->id,
                'provider_id'  =>   $provider->id,
                'num_wallet'   =>  null,
                'costs_amount' => $valuer_deposit_com,
                // 'name_complet' => $name_custumers ?? null,
                // 'num_piece' => $num_piece_custumers ?? null,
                // 'validated_date' => $date_piece ?? null,
                'choose_type_transaction' => 1,
                'amount_received'  => 0,
                'type_transaction' => 'Depot',
                'status'  => "PENDING",
                'amount_solde' => $amount_solde ?? null
            ]
        );
    }

    public function processCommissionsMobileDistributer($transaction_amount_money, $percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_money;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        $valeur_percent_valeur  = $percent_robus->percent * $transaction_amount_money;

        // Commissions Inters 
        if (sabdy()->is_under_distributer == 1) {

            // Calcul des valeurs de commissions
            $valuer_admin = $percent_robus->percent_admin * $valeur_percent_valeur;
            $valuer_deposit = $percent_robus->percent_deposit * $valeur_percent_valeur;


            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {
                $admin_valeur_convertion =   (float)  $valuer_deposit *   (float) sabdy()->country->currencie->value;
                $percent_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
                $admin_valeur_convertion =   (float)  $valuer_deposit;
                $percent_valeur_convertion =   (float)  $valuer_admin;
            } else {
                $admin_valeur_convertion =   (float)  $valuer_deposit *   (float) sabdy()->country->currencie->value;
                $percent_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            }

            if ($this->showRemonterMoneyButton) {


                $walletTransaction->update([
                    'commissions' => DB::raw('commissions+'  . $admin_valeur_convertion),
                ]);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted) {
                    $valuer_companie = $percent_robus->percent_companie * $valeur_percent_valeur;
                    $walletTransaction->increment('commission_companie', $valuer_companie);
                }

                if ($admin_civ->count() > 0) {

                    $nmbre_admin = $admin_civ->count();

                    $valeur_single = sabdy()->country->name_groupe == 'MAG' ?  $percent_valeur_convertion / $nmbre_admin : $admin_valeur_convertion / $nmbre_admin;

                    foreach ($admin_civ as $user) {
                        $admin_single = SidUser::find($user->id);

                        AdminsCom::create([
                            'amount' =>   $valeur_single,
                            'sid_user_id' =>   $admin_single->id,
                            'wallet_transaction_id' => $walletTransaction->id,
                            'wallet_bank_id' => null,
                            'wallet_deposit_id' => null,
                            'amount_with_draw' =>  0,
                            'wallet_inter_id' => null,
                            'wallet_payment_id' => null,
                            'wallet_phone_credit_id' => null,
                            'wallet_reload_id' => null,
                            'bank_card_id' => null,
                            'description' => sabdy()->country->name_groupe == 'MAG' ? 'Paiement commission sous-distributer' : 'Commission administrateur',
                            'amount_under' =>  sabdy()->is_under_distributer ? $valuer_deposit : 0,
                            'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                        ]);
                    }
                }
            } else {


                if ($admin_civ->count() > 0) {

                    $nmbre_admin = $admin_civ->count();

                    $valeur_single = sabdy()->country->name_groupe == 'MAG' ?  $percent_valeur_convertion / $nmbre_admin : $percent_valeur_convertion / $nmbre_admin;

                    foreach ($admin_civ as $user) {
                        $admin_single = SidUser::find($user->id);

                        AdminsCom::create([
                            'amount' =>   $valeur_single,
                            'sid_user_id' =>   $admin_single->id,
                            'wallet_transaction_id' => $walletTransaction->id,
                            'wallet_bank_id' => null,
                            'wallet_deposit_id' => null,
                            'amount_with_draw' =>  0,
                            'wallet_inter_id' => null,
                            'wallet_payment_id' => null,
                            'wallet_phone_credit_id' => null,
                            'wallet_reload_id' => null,
                            'bank_card_id' => null,
                            'description' =>  'Commission administrateur',
                            'amount_under' =>   0,
                            'description_underdistributer' =>  null
                        ]);
                    }
                }
            }
        }

        return true;
    }


    private function processCommissionsMobile($percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $this->transaction_amount_money;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        // Commissions Inters 
        if (sabdy()->is_under_distributer == 1) {

            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent * $this->transaction_amount_money;

            // 50 %
            $valuer_admin = $percent_robus->percent_admin * $valeur_percent_admin;

            // 20%
            $valuer_deposit = $percent_robus->percent_deposit *  $valeur_percent_admin;


            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin;
            } else {
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            }


            $walletTransaction->update([
                'commissions' => DB::raw('commissions+'  . $valuer_deposit),
            ]);

            $account_accepted = sabdy()->is_accepted_companie ?? false;

            if ($account_accepted) {
                $valuer_companie = $percent_robus->percent_companie * $valeur_percent_admin;
                $walletTransaction->increment('commission_companie', $valuer_companie);
            }

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => $walletTransaction->id,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'amount_with_draw' =>  0,
                        'wallet_inter_id' => null,
                        'wallet_payment_id' => null,
                        'wallet_phone_credit_id' => null,
                        'wallet_reload_id' => null,
                        'bank_card_id' => null,
                        'description' => 'Commission',
                    ]);
                    $admin_single->wallet->update([
                        'reload_by' => sabdy()->firstname .
                            ' ' .
                            sabdy()->lastname,
                        'sid_user_relod' => sabdy()->id,
                        'amount' => DB::raw('amount+' . $valeur_single),
                    ]);
                }
            }
        } else {



            // Percent admin 
            $valeur_percent_admin  = (float) $percent_robus->percent_admin *    (float) $this->transaction_amount_robus;


            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

                $admin_valeur_convertion = (float)   $valeur_percent_admin;
            } else {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            }

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => $walletTransaction->id,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'amount_with_draw' =>  0,
                        'wallet_inter_id' => null,
                        'wallet_payment_id' => null,
                        'wallet_phone_credit_id' => null,
                        'wallet_reload_id' => null,
                        'bank_card_id' => null,
                        'description' => 'Commission',
                    ]);

                    $admin_single->wallet->update([
                        'reload_by' => sabdy()->firstname .
                            ' ' .
                            sabdy()->lastname,
                        'sid_user_relod' => sabdy()->id,
                        'amount' => DB::raw('amount+' . $valeur_single),
                    ]);
                }
            }
        }

        return true;
    }


    private function processCommissionsRobusDistributer()
    {

        $commission =  $this->transaction_amount_robus;

        $wallet = sabdy()->wallet;

        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        return true;
    }

    // Validated phone
    private function validateAmount()
    {

        $val_amount = $this->transaction_amount_robus;

        if ($val_amount > sabdy()->wallet->amount) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Vérifiez votre solde !');
            return false;
        }

        return true;
    }

    private function validateVerifyAmount($val_amount)
    {

        if ($val_amount > sabdy()->wallet->amount) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Vérifiez votre solde !');
            return false;
        }

        return true;
    }


    // Validated date daily
    private function validateDailyLimit($som_transfers, $valeur_ceiling)
    {
        if ($som_transfers >= $valeur_ceiling) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('warning', 'Votre montant journalier est atteint !');
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

    // Verify ceilling

    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (
            WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )
                ->where(
                    'wallet_banks.created_by',
                    sabdy()->id
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
                    sabdy()->country->id
                )
                ->where(
                    'wallet_deposits.created_by',
                    sabdy()->id
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
                    sabdy()->country->id
                )
                ->where(
                    'wallet_inters.created_by',
                    sabdy()->id
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
                    sabdy()->country->id
                )
                ->where(
                    'wallet_payments.created_by',
                    sabdy()->id
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
                    sabdy()->country->id
                )
                ->where(
                    'wallet_transactions.created_by',
                    sabdy()->id
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
                    sabdy()->country->id
                )
                ->where(
                    'wallet_phone_credits.created_by',
                    sabdy()->id
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
            BankCard::where('created_by', sabdy()->id)
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
                    sabdy()->country->id
                )->where(
                    'wallet_reloads.created_by',
                    sabdy()->id
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


    public function getAdminDrawalCIVProperty()
    {

        $admin_drawal =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->whereId(1)
            ->where('country_id', 107)
            ->first();

        return $admin_drawal;
    }

    public function getAdminValeurProperty()
    {

        $admins = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', sabdy()->country->id)
            ->get();

        return  $admins;
    }

    public function getAdminCIVProperty()
    {

        $admin_civ =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        return  $admin_civ;
    }
}
