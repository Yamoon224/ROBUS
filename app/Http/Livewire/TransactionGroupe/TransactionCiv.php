<?php

namespace App\Http\Livewire\TransactionGroupe;

use App\Mail\CodeValidated;
use App\Models\Custumers\Country;
use App\Models\Invoices\InvoiceTransaction;
use App\Models\OperatorService;
use App\Models\Payments\Transaction;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use App\Http\Services\CommissionServicePercentBackend;
use App\Http\Services\TotalAmoutUserServiveBackend;
use App\Http\Services\NotificationServiceBackend;
use App\Http\Services\TransfertServiceBackend;
use App\Http\Services\CommissionServiceBackend;

class TransactionCiv extends Component
{

    public $step = 1;
    public $transaction_amount_robus;
    public $name_custumers;
    public $provider_transaction;
    public $choose_type_transaction;
    public $choose_transaction;
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

    public $need_identity_number = false;
    public $identity_number;


    public function mount()
    {

        $this->operators = OperatorService::getOperatorsWithCommissions(sabdy()->id);
        $this->resetValeurReload();
        $this->updateProviderTransaction();
        $this->operatorsTransaction = OperatorService::getOperatorsWithTranactions(sabdy()->id);

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
    }

    public function updatedTransactionAmountRobus()
    {
        // Si la valeur change, mettez à jour les montants
        $this->calculateAmounts();
    }

    public function updated($property, $value)
    {
        if ($property === 'transaction_amount_money') {
            $amount = (int) $value;
            $this->need_identity_number = $amount >= 100000 && $amount <= 200000;
        }
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
                $commission_transaction = app(CommissionServicePercentBackend::class)->getRobusPercentageSDistributers();

                // Calculs
                $this->commission_transaction_val_admin_receive = (float) $commission_transaction->percent * $reel_amount;
                $this->commission_transaction_val_nat_pay = (float) $commission_transaction->percent_deposit * $reel_amount;
                $this->commission_transaction_val_nat_receive = (float) $reel_amount - $this->commission_transaction_val_admin_receive;
            }
        }

        if ($this->transaction_amount_money) {
            // Calcul pour les transactions Money
            $reel_amount_money = $this->search_amount($this->transaction_amount_money);

            if ($reel_amount_money != '') {
                if (sabdy()->country->name_groupe == 'CIV') {
                    $commission_transfert_phone = app(CommissionServicePercentBackend::class)->getRobusPercentageMobile();
                } elseif (sabdy()->country->name_groupe == 'MAG') {
                    $commission_transfert_phone = app(CommissionServicePercentBackend::class)->getRobusPercentageMobileMaroc();
                }

                // Calculs
                if ($this->choose_transaction == 1) {
                    $this->commission_transaction_money_val_admin_receive = $commission_transfert_phone->percent * $reel_amount_money;
                    $this->commission_transaction_money_val_nat_pay = (float) $reel_amount_money * (float) $commission_transfert_phone->percent_deposit;
                    $valeur_receive = (float) $reel_amount_money;

                    if ($valeur_receive < 0) {
                        $this->commission_transaction_money_val_nat_receive = "Montant négatif";
                    } else {
                        $this->commission_transaction_money_val_nat_receive = $valeur_receive;
                    }
                } else {

                    $this->commission_transaction_money_val_admin_receive = $commission_transfert_phone->percent_custum_drawal * $reel_amount_money;
                    $this->commission_transaction_money_val_nat_pay = (float) $reel_amount_money * (float) $commission_transfert_phone->percent_with_drawal;
                    $valeur_receive = (float) $reel_amount_money;

                    if ($valeur_receive < 0) {
                        $this->commission_transaction_money_val_nat_receive = "Montant négatif";
                    } else {
                        $this->commission_transaction_money_val_nat_receive = $valeur_receive;
                    }
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
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();

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

                    if ($this->choose_transaction == 1) {
                        $this->validate([
                            'transaction_amount_money' => 'required',
                            'transaction_phone' => 'required',
                        ], [
                            'transaction_amount_money.required' => 'Votre montant est obligatoire',
                            'transaction_phone.required' => 'le numero est obligatoire',

                        ]);
                    } else {

                        $this->validate([
                            'transaction_amount_money' => 'required',
                            'transaction_phone' => 'required',
                            'name_custumers' => 'required',
                        ], [
                            'transaction_amount_money.required' => 'Votre montant est obligatoire',
                            'transaction_phone.required' => 'le numero est obligatoire',
                            'name_custumers.required' => 'le nom  est obligatoire',
                        ]);
                    }

                    /**
                     * 🔥 Ajouter automatiquement l'identité si besoin
                     */
                    if ($this->need_identity_number) {
                        $rules['identity_number'] = 'required';
                        $messages['identity_number.required'] = 'Le numéro de la pièce est obligatoire';
                    }


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

        // Verifier plafond days
        $valeur_ceiling = sabdy()->silver_ceiling;
        $valeur_max_send  =  sabdy()->country->amount_max_send;
        $provider = Provider::whereId($this->provider_transaction)->first();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();

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
                        ->first();

                    $reel_amount =  $this->search_amount($this->transaction_amount_robus);


                    if (sabdy()->is_under_distributer) {

                        $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageSDistributers();
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

                                            $walletTransaction =  app(TransfertServiceBackend::class)->createRobusWalletTransaction($reel_amount, $percent_robus, $provider, $this->compte_number_robus, $amount_solde_drawal, $this->choose_type_transaction , $walletAmountSend->amount);

                                            if ($walletTransaction != null) {

                                                if ($this->updateAmountRobusReceived($this->compte_number_robus, $walletTransaction, $valeur_received)) {

                                                    $message = 'Vous venez de recevoir un montant de ' . $reel_amount . ' de la part de ' . sabdy()->firstname . ' ' . sabdy()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal . $wallet_search->currencie->name . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                                    if ($wallet_search->sidUser->country->name_groupe == 'CIV') {
                                                        sendTryCashMessage($wallet_search->sidUser->phone, $message);
                                                    }

                                                    if ($wallet_search->sidUser->email != null) {

                                                        Mail::to($wallet_search->sidUser->email)->queue(new CodeValidated('Message dépot : '  . $message));
                                                    }

                                                    if ($this->processCommissionsDistributerRobus($reel_amount, $percent_robus, $walletTransaction, $admin_civ)) {
                                                        app(NotificationServiceBackend::class)->sendCivGroupeNotification($walletTransaction);
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
                                        $this->emit('error', 'Veuillez verifier votre solde');
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


                                        $walletTransaction =  app(TransfertServiceBackend::class)->createRobusWalletTransaction($reel_amount, $percent_robus, $provider, $this->compte_number_robus, $amount_solde_drawal, $this->choose_type_transaction, $walletAmountSend->amount);

                                        if ($walletTransaction != null) {

                                            $message = 'Vous venez de recevoir un montant de ' . $this->compte_number_robus . ' de la part de ' . sabdy()->firstname . ' ' . sabdy()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal . $wallet_search->currencie->name . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                            if ($wallet_search->sidUser->country->name_groupe == 'CIV') {
                                                sendTryCashMessage($wallet_search->sidUser->phone, $message);
                                            }

                                            if ($wallet_search->sidUser->email != null) {
                                                Mail::to($wallet_search->sidUser->email)->queue(new CodeValidated('Message dépot : '  . $message));
                                            }

                                            if ($this->updateAmountRobusReceivedDistributer($this->compte_number_robus, $walletTransaction)) {
                                                if ($this->processCommissionsRobusDistributer($reel_amount)) {
                                                    app(NotificationServiceBackend::class)->sendCivGroupeNotification($walletTransaction);
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
                                    $this->emit('error', 'Veuillez verifier votre solde');
                                }
                            } else {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers');
                                $this->emit('error', 'Votre montant journalier est atteint  !!');
                            }
                        }
                    } else {

                        $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageCustumers();

                        $isCustomer = $wallet_search->sidUser->is_custumers;

                        // Is custumers
                        if ($percent_robus !== null) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount()) {

                                    $valeur_received = $reel_amount - ($percent_robus->percent * $reel_amount);

                                    // Update Info valeurs
                                    $walletAmountSend = Wallet::where('wallet_id',  $this->compte_number_robus)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                    if ($walletAmountSend) {

                                        $walletTransaction = app(TransfertServiceBackend::class)->createRobusWalletTransaction($reel_amount, $percent_robus, $provider, $this->compte_number_robus, $amount_solde_drawal, $this->choose_type_transaction, $walletAmountSend->amount);

                                        if ($walletTransaction != null) {

                                            $message = 'Vous venez de recevoir un montant de ' . $this->compte_number_robus . ' de la part de ' . sabdy()->firstname . ' ' . sabdy()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal . $wallet_search->currencie->name . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                            if ($wallet_search->sidUser->country->name_groupe == 'CIV') {
                                                sendTryCashMessage($wallet_search->sidUser->phone, $message);
                                            }


                                            if ($wallet_search->sidUser->email != null) {

                                                Mail::to($wallet_search->sidUser->email)->queue(new CodeValidated('Message dépot : '  . $message));
                                            }
                                            if ($this->updateAmountRobusReceived($this->compte_number_robus, $walletTransaction, $valeur_received)) {
                                                if ($this->processCommissionsCustumerRobus($reel_amount, $percent_robus, $walletTransaction, $admin_civ)) {
                                                    app(NotificationServiceBackend::class)->sendTransactionGroupeNotification($walletTransaction);
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


                        if ($this->choose_transaction == 1) {
                            $this->validate([
                                'transaction_amount_money' => 'required',
                                'transaction_phone' => 'required'
                            ], [
                                'transaction_amount_money.required' => 'Votre montant est obligatoire',
                                'transaction_phone.required' => 'le numero est obligatoire',

                            ]);
                        } else {

                            $this->validate([
                                'transaction_amount_money' => 'required',
                                'transaction_phone' => 'required',
                                'name_custumers' => 'required',
                            ], [
                                'transaction_amount_money.required' => 'Votre montant est obligatoire',
                                'transaction_phone.required' => 'le numero est obligatoire',
                                'name_custumers.required' => 'le nom  est obligatoire',
                            ]);
                        }

                        /**
                         * 🔥 Ajouter automatiquement l'identité si besoin
                         */
                        if ($this->need_identity_number) {
                            $rules['identity_number'] = 'required';
                            $messages['identity_number.required'] = 'Le numéro de la pièce est obligatoire';
                        }


                        if (sabdy()->country->name_groupe == 'CIV') {
                            $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageMobile();
                        } elseif (sabdy()->country->name_groupe == 'MAG') {

                            $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageMobileMaroc();
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

                            $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageMobileTwo();
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

                                        $percent = ($this->choose_transaction == 1)
                                            ? $percent_robus->percent
                                            : $percent_robus->percent_with_drawal;

                                        $valeur_received = $reel_amount - $percent;

                                        $amount_payment = $reel_amount + $percent;
                                    }

                                    if ($this->choose_transaction == 1) {
                                        $cost_payment = $percent_robus->percent *  $reel_amount;
                                    } else {
                                        $cost_payment = $percent_robus->percent_with_drawal *  $reel_amount;
                                    }
                                } else {

                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('error', 'Vous devez etre un SD pour faire des transactions !');
                                }

                                if ($this->choose_transaction == 1 && !$this->validateVerifyAmount($amount_payment)) {
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('error', 'Verifié votre solde SVP !');
                                }

                                $walletTransaction = app(TransfertServiceBackend::class)->createWalletTransactionCiv($percent_robus, $provider, $reel_amount, $cost_payment, $this->transaction_phone, $this->choose_transaction, $this->name_custumers,  $this->identity_number);

                                if ($walletTransaction != null) {

                                    if ($this->updateAmountRobusReceivedMobile($walletTransaction, $valeur_received)) {

                                        $processCommission = $this->processCommissionsMobileDistributer($reel_amount, $percent_robus, $walletTransaction, $admin_civ, $this->choose_transaction);


                                        if ($processCommission) {

                                            app(NotificationServiceBackend::class)->sendNotificationMobile($walletTransaction);

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




    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
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


        app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $admin_valeur_convertion);


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
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
                $admin_valeur_convertion_drawal = $valuer_with_drawal;
                $admin_valeur_convertion = (float)$valeur_admin;
            } else {
                $admin_valeur_convertion_drawal = $valuer_with_drawal * (float)sabdy()->country->currencie->value;
                $admin_valeur_convertion = (float)$valeur_admin * (float)sabdy()->country->currencie->value;
            }


            if ($walletTransaction->type_transaction == "Depot") {



                if ($this->showRemonterDepotButton) {

                    $walletTransaction->increment('commissions', $valuer_deposit);

                    $account_accepted = sabdy()->is_accepted_companie ?? false;

                    $valuer_companie = $percent_robus->percent_companie * $valeur_percent_admin;

                    $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                    $adminAmount = (float) $valeur_percent_admin - (float) $cumul_valeurs;

                    if ($account_accepted) {
                        $walletTransaction->increment('commission_companie', $valuer_companie);
                        $totalCommission =  $valuer_companie + $valuer_deposit;
                        app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $totalCommission, $adminAmount);
                    } else {
                        app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $adminAmount, $valuer_deposit);
                    }
                }
            } elseif ($walletTransaction->type_transaction == "Retrait") {

                if ($this->showRemonterRetraitButton) {
                    $walletTransaction->increment('commissions', $valuer_deposit);

                    $account_accepted = sabdy()->is_accepted_companie ?? false;

                    $valuer_companie = $percent_robus->percent_companie * $valeur_percent_admin;

                    $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                    $adminAmount = (float) $valeur_percent_admin - (float) $cumul_valeurs;

                    if ($account_accepted) {
                        $valuer_companie = $percent_robus->percent_companie * $valeur_percent_admin;
                        $walletTransaction->increment('commission_companie', $valuer_companie);
                        $totalCommission =  $valuer_companie + $valuer_deposit;

                        app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $totalCommission, $adminAmount);
                    } else {
                        app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $adminAmount, $valuer_deposit);
                    }
                }
            } else {


                app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $valeur_percent_admin, $valuer_deposit);
            }
        } else {

            $valeur_percent_admin = $valeur_percent_admin;
            $admin_valeur_convertion = $this->convertAdminCommission($valeur_percent_admin);

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $admin_valeur_convertion);
        }

        return true;
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

    public function processCommissionsMobileDistributer($transaction_amount_money, $percent_robus, $walletTransaction, $admin_civ, $choose_transaction)
    {

        $commission =  $transaction_amount_money;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        if ($choose_transaction == 1) {
            $updatedValues = [
                'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                'sid_user_relod' => sabdy()->id,
                'amount' => DB::raw('amount - ' . $commission),
            ];

            $wallet->update($updatedValues);
        }


        $valeur_percent_valeur  = $choose_transaction == 1 ?  $percent_robus->percent * $transaction_amount_money : $percent_robus->percent_with_drawal * $transaction_amount_money;

        $valuer_deposit = $percent_robus->percent_deposit * $valeur_percent_valeur;
        $valuer_companie = $percent_robus->percent_companie * $commission;

        if ($country_civ->currencie->value > sabdy()->country->currencie->value) {
            $admin_valeur_convertion =   (float)  $valuer_deposit *   (float) sabdy()->country->currencie->value;
            $percent_valeur_compagnie =   (float)  $valuer_companie *   (float) sabdy()->country->currencie->value;
        } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
            $admin_valeur_convertion =   (float)  $valuer_deposit;
            $percent_valeur_compagnie =   (float)  $valuer_companie;
        } else {
            $admin_valeur_convertion =   (float)  $valuer_deposit *   (float) sabdy()->country->currencie->value;
            $percent_valeur_compagnie =   (float)  $valuer_companie *   (float) sabdy()->country->currencie->value;
        }

        if ($this->showRemonterMoneyButton) {

            $walletTransaction->update([
                'commissions' => DB::raw('commissions+'  . $admin_valeur_convertion),
            ]);

            $account_accepted = sabdy()->is_accepted_companie ?? false;
            $valuer_companie = $percent_robus->percent_companie * $valeur_percent_valeur;

            if ($account_accepted) {
                $walletTransaction->increment('commission_companie', $percent_valeur_compagnie);
                app(CommissionServiceBackend::class)->distributeUnderCommissionAcceptedCompanies($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $percent_valeur_compagnie, $admin_valeur_convertion);
            } else {
                app(CommissionServiceBackend::class)->distributeAdminCommissionUnders($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $admin_valeur_convertion);
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
            $valuer_companie = $percent_robus->percent_companie * $valuer_deposit;
            $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;
            $adminAmount = (float) $valuer_admin - (float) $cumul_valeurs;

            if ($account_accepted) {
                $valuer_companie = $percent_robus->percent_companie * $valeur_percent_admin;
                $totalCommission =  $valuer_companie + $valuer_deposit;

                $walletTransaction->increment('commission_companie', $valuer_companie);
                app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $totalCommission, $adminAmount);
            } else {
                app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $adminAmount, $valuer_deposit);
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

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $admin_valeur_convertion);
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
}
