<?php

namespace App\Http\Livewire\TransactionInters;

use App\Http\Controllers\Admin\MailController;
use App\Models\Custumers\Country;
use App\Http\Services\CommissionServiceBackend;
use App\Models\OperatorService;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Http\Services\CommissionServicePercentBackend;
use App\Http\Services\NotificationServiceBackend;
use App\Http\Services\TransfertServiceBackend;
use App\Http\Services\TotalAmoutUserServiveBackend;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class TransactionCiv extends Component
{

    public $transaction_amount_robus;
    public $provider_inter_out_civ;
    public $country_inter_out_civ;
    public $phone_inter_out_civ;
    public $interId;
    public $compte_inter_number_robus;
    public $account_number;
    public $providers = [];
    public $transaction_inter_amount_money;
    public $transaction_inter_amount_money_receved;

    public $commission;
    public $commission_transaction_val_inter_pay;
    public $commission_transaction_val_percent_pay;
    public $commission_transaction_val_inter_admin_receive;
    public $commission_transaction_val_inter_receive;
    public $commission_transaction_val_inter_convertion_cfa;
    public $commission_transaction_val_inter_convertion_send;
    public $password_validated;
    public $stepInter = 1;
    public $name_custumer;
    public $num_piece_custumer;
    public $date_piece;
    public $name;
    public $name_sender;
    public $isValidating = false;
    public $isPasswordValid = false;

    // Gain commissions
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

    public function resetValeurReload()
    {

        $this->transaction_amount_robus = '';
        $this->provider_inter_out_civ = null;
        $this->country_inter_out_civ = null;
        $this->phone_inter_out_civ = '';
        $this->compte_inter_number_robus = '';
        $this->transaction_inter_amount_money = '';
        $this->transaction_inter_amount_money_receved = '';
        $this->account_number = '';
        // $this->emit('modal-hide-transfers-validated');
        // $this->emit('modal-hide-transfers');
    }

    public function mount()
    {

        $this->operators = OperatorService::getOperatorsWithCommissions(sabdy()->id);
        $this->resetValeurReload();
        $this->commission_transaction_val_inter_pay = 0;
        $this->commission_transaction_val_inter_convertion_send = 0;
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
            'Active International' => 'showRemonterInterButton'
        ];

        $restrictionCompangieMap = [
            'Active International' => 'showRemonterInterCButton'
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
            'TRANSFERT A INTER' => 'showTransacInterButton',
            'CREDIT TELEPHONIQUE A INTER' => 'showTelInterButton',
            'VERSEMENT BANCAIRE A INTER' => 'showBancInterButton',
            'RECHARGEMENT DE CARTE A INTER' => 'showCarteInterButton',
            'PAIMENT FACTURE A INTER' => 'showFacInterButton',
        ];

        foreach ($this->operators as $operator) {
            $restrictions = explode(',', $operator->name);

            foreach ($restrictionMap as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $this->{$buttonVar} = true;
                }
            }
        }


        foreach ($this->operatorsTransaction as $operator) {
            $restrictions = explode(',', $operator->name);
            foreach ($restrictionMapTransaction as $restriction => $buttonVar) {
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

    public function updateProviderTransaction()
    {

        $this->transaction_amount_robus = '';
        $this->provider_inter_out_civ = null;
        // $this->country_inter_out_civ = null;
        $this->phone_inter_out_civ = '';
        $this->compte_inter_number_robus = '';
        $this->name_custumer = '';
        $this->num_piece_custumer = '';
        $this->date_piece = '';
        $this->name = '';
        $this->name_sender = '';
        $this->transaction_inter_amount_money = '';
        $this->transaction_inter_amount_money_receved = '';
        $this->account_number = '';
        $this->resetCommissionValues();

        return $this->providers;
    }

    public function checkPassword()
    {

        if (Hash::check($this->password_validated, sabdy()->password)) {
            $this->isPasswordValid = true;
        } else {
            $this->isPasswordValid = false;
        }
    }

    public function updatedTransactionAmountRobus()
    {
        $this->calculateAmounts();
    }

    public function updatedTransactionInterAmountMoneyReceved()
    {
        $this->calculateAmountReceiveds();
    }

    public function updatedTransactionInterAmountMoney()
    {
        $this->calculateAmounts();
    }


    public function calculateAmounts()
    {

        $this->resetCommissionValues();

        $countryRobus = Country::find($this->country_inter_out_civ);

        $reelAmount = $this->transaction_inter_amount_money;

        if ($reelAmount !== '') {

            $commissionInter = app(CommissionServicePercentBackend::class)->getRobusPercentageInter($countryRobus, $this->provider_inter_out_civ);

            if ($commissionInter) {

                $this->calculateCommission($commissionInter, $reelAmount);

                // Conversion et validation selon la devise
                $currencyValue = sabdy()->country->currencie->value;
                $countryCurrencyValue = $countryRobus->currencie->value;

                if ($currencyValue > $countryCurrencyValue) {
                    $this->processConversion($reelAmount, $currencyValue, $countryCurrencyValue);
                } elseif ($currencyValue === $countryCurrencyValue) {
                    $this->processEqualConversion($reelAmount);
                } else {
                    $this->processConversion($reelAmount, $currencyValue, $countryCurrencyValue);
                }
            } else {
                $this->setNoGroupValues();
            }
        }
    }


    public function calculateAmountReceiveds()
    {
        // Réinitialise le montant à envoyer avant tout calcul

        $this->resetCommissionValues();

        $countryRobus = Country::find($this->country_inter_out_civ);

        $reelAmount = $this->transaction_inter_amount_money_receved;

        if ($reelAmount !== '') {

            $commissionInter = app(CommissionServicePercentBackend::class)->getRobusPercentageInter($countryRobus, $this->provider_inter_out_civ);

            if ($commissionInter) {

                $currencyValue = sabdy()->country->currencie->value;
                $countryCurrencyValue = $countryRobus->currencie->value;

                $this->calculateCommissionReceved($commissionInter, $reelAmount, $countryRobus);

                if ($currencyValue > $countryCurrencyValue) {
                    $this->processRececevedConversion($reelAmount, $currencyValue, $countryCurrencyValue);
                } elseif ($currencyValue === $countryCurrencyValue) {
                    $this->processEqualRecevedConversion($reelAmount);
                } else {
                    $this->processRececevedConversion($reelAmount, $currencyValue, $countryCurrencyValue);
                }
            } else {
                $this->setNoGroupValues();
            }
        } else {

            $this->transaction_inter_amount_money = null;
        }
    }


    private function resetCommissionValues()
    {
        $this->commission_transaction_val_inter_pay = 0;
        $this->commission_transaction_val_percent_pay = 0;
        $this->commission_transaction_val_inter_admin_receive = 0;
        $this->commission_transaction_val_inter_receive = 0;
        $this->commission_transaction_val_inter_convertion_cfa = 0;
        $this->commission_transaction_val_inter_convertion_send = 0;
    }

    /**
     * Calcule les commissions de base
     */
    private function calculateCommission($commissionInter, $reelAmount)
    {

        if (sabdy()->is_under_distributer || sabdy()->is_distributer) {
            $this->commission_transaction_val_percent_pay = $commissionInter->percent_under_distributer * $reelAmount;
            $this->commission_transaction_val_inter_pay = $commissionInter->percent_deposit_under_distributer * $reelAmount;
        } else {
            $this->commission_transaction_val_percent_pay = $commissionInter->percent * $reelAmount;
            $this->commission_transaction_val_inter_pay = $this->commission_transaction_val_percent_pay;
        }
    }

    private function calculateCommissionReceved($commissionInter, $reelAmount, $countryRobus)
    {
        $currencyValue = sabdy()->country->currencie->value;
        $countryCurrencyValue = $countryRobus->currencie->value;

        if ($currencyValue > $countryCurrencyValue) {
            $amountSimple = $reelAmount * $currencyValue;
            $reelCommissionAmount = $amountSimple * $countryCurrencyValue;
        } elseif ($currencyValue === $countryCurrencyValue) {
            $reelCommissionAmount = $reelAmount;
        } else {
            $amountSimple = $reelAmount * $currencyValue;
            $reelCommissionAmount = $amountSimple * $countryCurrencyValue;
        }

        if (sabdy()->is_under_distributer || sabdy()->is_distributer) {
            $this->commission_transaction_val_percent_pay = (float) $commissionInter->percent_under_distributer * (float) $reelCommissionAmount;
            $this->commission_transaction_val_inter_pay = (float)  $commissionInter->percent_deposit_under_distributer * (float) $reelCommissionAmount;
        } else {
            $this->commission_transaction_val_percent_pay = (float) $commissionInter->percent * (float) $reelCommissionAmount;
            $this->commission_transaction_val_inter_pay = $this->commission_transaction_val_percent_pay;
        }
    }


    /**
     * Gère les conversions pour différentes devises
     */
    private function processConversion($reelAmount, $currencyValue, $countryCurrencyValue)
    {
        $valeurVerify = $reelAmount - $this->commission_transaction_val_percent_pay;

        // Conversion en CFA
        $this->commission_transaction_val_inter_convertion_cfa = $valeurVerify * $currencyValue;

        // Conversion vers la devise du pays de destination
        $this->commission_transaction_val_inter_convertion_send = $this->commission_transaction_val_inter_convertion_cfa / $countryCurrencyValue;

        $this->commission_transaction_val_inter_receive = $valeurVerify < 0
            ? "Montant negatif"
            : $this->commission_transaction_val_inter_convertion_send;
        $this->transaction_inter_amount_money_receved = $this->commission_transaction_val_inter_convertion_send;
    }

    private function processRececevedConversion($reelAmount, $currencyValue, $countryCurrencyValue)
    {

        if ($currencyValue > $countryCurrencyValue) {
            $amountSimple = $this->commission_transaction_val_percent_pay * $currencyValue;
            $reelCommissionAmount = $amountSimple / $countryCurrencyValue;
        } elseif ($currencyValue === $countryCurrencyValue) {
            $reelCommissionAmount = $this->commission_transaction_val_percent_pay;
        } else {
            $amountSimple = $this->commission_transaction_val_percent_pay * $currencyValue;
            $reelCommissionAmount = $amountSimple / $countryCurrencyValue;
        }

        $valeurVerify = $reelAmount + $reelCommissionAmount;

        $this->commission_transaction_val_inter_convertion_cfa = $valeurVerify * $currencyValue;

        $this->commission_transaction_val_inter_convertion_send = $this->commission_transaction_val_inter_convertion_cfa * $countryCurrencyValue;

        $this->commission_transaction_val_inter_receive = $valeurVerify < 0
            ? "Montant negatif"
            : $this->commission_transaction_val_inter_convertion_send;
        $this->transaction_inter_amount_money = $this->commission_transaction_val_inter_convertion_send;
    }

    /**
     * Gère les conversions pour des devises égales
     */
    private function processEqualConversion($reelAmount)
    {
        $valeurVerify = $reelAmount - $this->commission_transaction_val_percent_pay;

        // Pas de conversion nécessaire
        $this->commission_transaction_val_inter_convertion_cfa = $valeurVerify;
        $this->commission_transaction_val_inter_convertion_send = $valeurVerify;
        $this->commission_transaction_val_inter_receive = $valeurVerify < 0
            ? "Montant negatif"
            : $this->commission_transaction_val_inter_convertion_send;

        $this->transaction_inter_amount_money_receved = $this->commission_transaction_val_inter_convertion_send;
    }

    private function processEqualRecevedConversion($reelAmount)
    {

        $valeurVerify = $reelAmount + $this->commission_transaction_val_percent_pay;
        $this->commission_transaction_val_inter_convertion_cfa = $valeurVerify;
        $this->commission_transaction_val_inter_convertion_send = $valeurVerify;
        $this->commission_transaction_val_inter_receive = $valeurVerify < 0
            ? "Montant negatif"
            : $this->commission_transaction_val_inter_convertion_send;

        $this->transaction_inter_amount_money = $this->commission_transaction_val_inter_convertion_send;
    }
    /**
     * Définit les valeurs si aucun groupe n'est trouvé
     */
    private function setNoGroupValues()
    {
        $this->commission_transaction_val_inter_pay = "Pas de Groupe";
        $this->commission_transaction_val_inter_receive = "Pas de Groupe";
    }


    public function resetInterField()
    {
        $this->transaction_amount_robus = '';
        $this->provider_inter_out_civ = null;
        $this->country_inter_out_civ = null;
        $this->compte_inter_number_robus = '';
        $this->name_custumer = '';
        $this->num_piece_custumer = '';
        $this->date_piece = '';
        $this->name = '';
        $this->name_sender = '';
        $this->phone_inter_out_civ = '';
        $this->transaction_inter_amount_money = '';
        $this->transaction_inter_amount_money_receved = '';
        $this->account_number = '';
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
        $this->emit('modal-hide-inters');
        $this->emit('transaction_inters');
    }

    public function loadChoose3()
    {
        $this->provider_inter_out_civ = null;
    }

    public function cancelInterOutCivField()
    {

        $this->provider_inter_out_civ = null;
        $this->country_inter_out_civ = null;
        $this->phone_inter_out_civ = null;
        $this->compte_inter_number_robus = '';
        $this->transaction_inter_amount_money = '';
        $this->stepInter = 1;
        $this->name_custumer = '';
        $this->num_piece_custumer = '';
        $this->date_piece = '';
        $this->name = '';
        $this->name_sender = '';
        $this->account_number = '';
        $this->transaction_inter_amount_money_receved = '';
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
        $this->emit('transaction_inters');
    }

    public function addInterOut()
    {

        $provider_payment = Provider::whereId($this->provider_inter_out_civ)->first();
        $valeur_ceiling = sabdy()->silver_ceiling;
        $som_transfers =  $this->getSumValTransactionProperty();


        try {

            if ($provider_payment) {


                if (in_array($this->provider_inter_out_civ, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78, 93, 94, 95, 96, 97, 98, 100, 101, 102])) {

                    if (sabdy()->is_under_distributer) {

                        $this->validate(
                            [
                                'transaction_inter_amount_money' => 'required',
                                'phone_inter_out_civ' => 'required',
                                'country_inter_out_civ' => 'required',
                                "name_custumer" => 'required',
                                "num_piece_custumer" => 'required',
                                "date_piece" => 'required',
                            ],
                            [
                                'transaction_inter_amount_money.required' =>
                                'Votre montant est obligatoire',
                                'country_inter_out_civ.required' => 'Pays  est obligatoire',
                                'phone_inter_out_civ.required' => 'Numero est obligatoire',
                                'name_custumer.required' => 'Nom est obligatoire',
                                'num_piece_custumer.required' => 'Numero piece est obligatoire',
                                'date_piece.required' => 'date de validité est obligatoire',
                            ]
                        );
                    } else {

                        $this->validate(
                            [
                                'transaction_inter_amount_money' => 'required',
                                'phone_inter_out_civ' => 'required',
                                'country_inter_out_civ' => 'required',
                            ],
                            [
                                'transaction_inter_amount_money.required' =>
                                'Votre montant est obligatoire',
                                'country_inter_out_civ.required' => 'Pays  est obligatoire',
                                'phone_inter_out_civ.required' => 'Numero est obligatoire',
                            ]
                        );
                    }

                    $this->stepInter = 2;
                } elseif (in_array($this->provider_inter_out_civ, [99])) {


                    if (sabdy()->is_under_distributer) {


                        $this->validate(
                            [
                                'transaction_inter_amount_money' => 'required',
                                'phone_inter_out_civ' => 'required',
                                'country_inter_out_civ' => 'required',
                                "name_custumer" => 'required',
                                "name_sender" => 'required',
                                "num_piece_custumer" => 'required',
                                "date_piece" => 'required',
                                'account_number' => 'required',
                                'name' => 'required',
                            ],
                            [
                                'transaction_inter_amount_money.required' =>
                                'Votre montant est obligatoire',
                                'country_inter_out_civ.required' => 'Pays  est obligatoire',
                                'name_custumer.required' => 'Nom est obligatoire',
                                'name_sender.required' => 'Nom expediteur est obligatoire',
                                'num_piece_custumer.required' => 'Numero piece est obligatoire',
                                'date_piece.required' => 'date de validité est obligatoire',
                                'account_number.required' => 'Numero de compte est obligatoire',
                                'name.required' => 'Nom de la banque est obligatoire',
                            ]
                        );
                    } else {

                        $this->validate(
                            [
                                'transaction_inter_amount_money' => 'required',
                                'phone_inter_out_civ' => 'required',
                                'country_inter_out_civ' => 'required',
                            ],
                            [
                                'transaction_inter_amount_money.required' =>
                                'Votre montant est obligatoire',
                                'country_inter_out_civ.required' => 'Pays  est obligatoire',
                                'phone_inter_out_civ.required' => 'Numero est obligatoire',
                            ]
                        );
                    }

                    $this->stepInter = 2;
                } elseif ($this->provider_inter_out_civ == 51) {

                    $this->validate(
                        [
                            'transaction_inter_amount_money' => 'required',
                            'compte_inter_number_robus' => 'required',
                            'country_inter_out_civ' => 'required',
                        ],
                        [
                            'transaction_inter_amount_money.required' =>
                            'Votre montant est obligatoire',
                            'country_inter_out_civ.required' => 'Pays  est obligatoire',
                            'compte_inter_number_robus.required' => 'Numero est obligatoire',
                        ]
                    );

                    $this->stepInter = 2;
                } else {
                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('error', 'Ce providers n\'est pas encore disponible chez Robus !');
                }

                if (!$this->validateAmount()) {
                    return;
                }

                if (!$this->validateDailyLimit($som_transfers, $valeur_ceiling)) {
                    return;
                }
            } else {
                $this->resetInterField();
                $this->emit('modal-hide-inters');
                $this->emit('error', 'Enternationnal existante  !');
            }
        } catch (\Exception $exception) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }


    public function validatedTransaction()
    {

        $country_robus = Country::whereId($this->country_inter_out_civ)
            // ->whereStatus(true)
            ->first();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();
        $provider_payment = Provider::whereId($this->provider_inter_out_civ)->first();
        $valeur_ceiling = sabdy()->silver_ceiling;

        try {


            $this->validate([
                'password_validated' => 'required',
            ]);

            if (Hash::check($this->password_validated, sabdy()->password)) {

                $this->isValidating = true;

                $this->password_validated = "";

                if (in_array($this->provider_inter_out_civ, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78, 93, 94, 95, 96, 97, 98, 100, 101, 102])) {

                    if (sabdy()->is_under_distributer) {

                        $this->validate(
                            [
                                'transaction_inter_amount_money' => 'required',
                                'phone_inter_out_civ' => 'required',
                                'country_inter_out_civ' => 'required',
                                "name_custumer" => 'required',
                                "num_piece_custumer" => 'required',
                                "date_piece" => 'required',
                            ],
                            [
                                'transaction_inter_amount_money.required' =>
                                'Votre montant est obligatoire',
                                'country_inter_out_civ.required' => 'Pays  est obligatoire',
                                'phone_inter_out_civ.required' => 'Numero est obligatoire',
                                'name_custumer.required' => 'Nom est obligatoire',
                                'num_piece_custumer.required' => 'Numero piece est obligatoire',
                                'date_piece.required' => 'date de validité est obligatoire',
                            ]
                        );
                    } else {

                        $this->validate(
                            [
                                'transaction_inter_amount_money' => 'required',
                                'phone_inter_out_civ' => 'required',
                                'country_inter_out_civ' => 'required',
                            ],
                            [
                                'transaction_inter_amount_money.required' =>
                                'Votre montant est obligatoire',
                                'country_inter_out_civ.required' => 'Pays  est obligatoire',
                                'phone_inter_out_civ.required' => 'Numero est obligatoire',
                            ]
                        );
                    }
                } elseif (in_array($this->provider_inter_out_civ, [99])) {


                    if (sabdy()->is_under_distributer) {

                        $this->validate(
                            [
                                'transaction_inter_amount_money' => 'required',
                                'phone_inter_out_civ' => 'required',
                                'country_inter_out_civ' => 'required',
                                "name_custumer" => 'required',
                                "name_sender" => 'required',
                                "num_piece_custumer" => 'required',
                                "date_piece" => 'required',
                                'account_number' => 'required',
                                'name' => 'required',
                            ],
                            [
                                'transaction_inter_amount_money.required' =>
                                'Votre montant est obligatoire',
                                'country_inter_out_civ.required' => 'Pays  est obligatoire',
                                'name_custumer.required' => 'Nom est obligatoire',
                                'name_sender.required' => 'Nom expediteur est obligatoire',
                                'num_piece_custumer.required' => 'Numero piece est obligatoire',
                                'date_piece.required' => 'date de validité est obligatoire',
                                'account_number.required' => 'Numero de compte est obligatoire',
                                'name.required' => 'Nom de la banque est obligatoire',
                            ]
                        );
                    } else {

                        $this->validate(
                            [
                                'transaction_inter_amount_money' => 'required',
                                'phone_inter_out_civ' => 'required',
                                'country_inter_out_civ' => 'required',
                            ],
                            [
                                'transaction_inter_amount_money.required' =>
                                'Votre montant est obligatoire',
                                'country_inter_out_civ.required' => 'Pays  est obligatoire',
                                'phone_inter_out_civ.required' => 'Numero est obligatoire',
                            ]
                        );
                    }
                } elseif ($this->provider_inter_out_civ == 51) {

                    $this->validate(
                        [
                            'transaction_inter_amount_money' => 'required',
                            'compte_inter_number_robus' => 'required',
                            'country_inter_out_civ' => 'required',
                        ],
                        [
                            'transaction_inter_amount_money.required' =>
                            'Votre montant est obligatoire',
                            'country_inter_out_civ.required' => 'Pays  est obligatoire',
                            'compte_inter_number_robus.required' => 'Numero est obligatoire',
                        ]
                    );
                } else {
                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('error', 'Ce providers n\'est pas encore disponible chez Robus !');
                }

                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentageInter($country_robus, $this->provider_inter_out_civ);
                $reel_amount =  $this->search_amount($this->transaction_inter_amount_money);

                if ($this->provider_inter_out_civ == 51) {

                    if ($this->validateWallet($country_robus, $this->compte_inter_number_robus)) {

                        if ($percent_robus != null) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount()) {

                                    if (sabdy()->is_under_distributer) {

                                        $valeur_received = $reel_amount - ($percent_robus->percent_under_distributer * $reel_amount);
                                    } else {

                                        $valeur_received = $reel_amount - ($percent_robus->percent * $reel_amount);
                                    }

                                    // Update Info valeurs
                                    $walletAmountSend = Wallet::where('wallet_id',  $this->compte_inter_number_robus)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                    $walletInter = app(TransfertServiceBackend::class)->createRobusWalletInter($reel_amount, $percent_robus, $country_robus, $this->provider_inter_out_civ, $this->compte_inter_number_robus, $this->provider_inter_out_civ_type, $amount_solde_drawal);

                                    if ($walletInter != null) {

                                        if (app(TransfertServiceBackend::class)->updateAmountRobusReceived($walletInter, $country_robus, $valeur_received, $this->compte_inter_number_robus)) {
                                            if ($this->processCommissionsRobus($reel_amount, $percent_robus, $walletInter)) {
                                                app(NotificationServiceBackend::class)->sendNotificationRobus($walletInter);
                                                if (sabdy()->email != null) {
                                                    $notify = new MailController();
                                                    $notify->newPaiementInter($walletInter);
                                                }
                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('info', 'Votre demande de transaction a bien été prise en compte. !');
                                            } else {

                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('error ', 'Error de transferts du montant !');
                                            }
                                        } else {

                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('error', 'Error de demande transferts!');
                                        }
                                    } else {
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('error', 'Error de demande transferts !');
                                    }
                                } else {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit('error', 'Verifier votre Solde SVP!');
                                }
                            } else {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('error', 'Le  montant maxi jounalier est atteint !');
                            }
                            // }
                        } else {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Niveau de pourcentage introuvable !');
                        }
                    } else {
                        $this->resetInterField();
                        $this->emit('modal-hide-inters');
                        $this->emit('error', 'veuillez selectionnez le pays de destination ou le  Numero ne correspond pas au pays SVP !');
                    }
                } elseif (in_array($this->provider_inter_out_civ, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102])) {

                    if ($this->validatePhone($this->phone_inter_out_civ)) {

                        if ($percent_robus != null) {

                            $valeur_max_send  =  $country_robus->amount_max_send;

                            if ($this->validateAmountSendLimit($reel_amount, $valeur_max_send)) {

                                if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                    if ($this->validateAmount($reel_amount)) {

                                        if (sabdy()->is_under_distributer) {

                                            $valeur_received = $reel_amount - ($percent_robus->percent_under_distributer * $reel_amount);
                                        } else {

                                            $valeur_received = $reel_amount - ($percent_robus->percent * $reel_amount);
                                        }

                                        $walletInter = app(TransfertServiceBackend::class)->createMobileWalletInter(
                                            $reel_amount,
                                            $percent_robus,
                                            $country_robus,
                                            $this->provider_inter_out_civ,
                                            $this->phone_inter_out_civ,
                                            $this->name_custumer,
                                            $this->num_piece_custumer,
                                            $this->date_piece,
                                            $this->name,
                                            $this->name_sender,
                                            $this->account_number
                                        );

                                        if ($walletInter != null) {


                                            if (app(TransfertServiceBackend::class)->updateAmountReceived($walletInter, $country_robus, $valeur_received)) {

                                                if ($this->processCommissionMobiles($reel_amount, $percent_robus, $country_robus, $walletInter, $admin_civ)) {
                                                    app(NotificationServiceBackend::class)->sendNotificationRobus($walletInter);
                                                    if (sabdy()->email != null) {
                                                        $notify = new MailController();
                                                        $notify->newPaiementInter($walletInter);
                                                    }
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                                } else {

                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Error de transferts du montant  !');
                                                }
                                            } else {

                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('error', 'Error de demande transferts   !');
                                            }
                                        } else {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('error', 'Error de demande transferts  !');
                                        }
                                    } else {

                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('error', 'Verifier votre Solde SVP  !');
                                    }
                                } else {

                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit('error', 'Le  montant maxi jounalier est atteint !');
                                }
                            } else {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('error',  "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $country_robus->currencie->code);
                            }
                        } else {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Niveau de pourcentage introuvable !!');
                        }
                    } else {
                        $this->resetInterField();
                        $this->emit('modal-hide-inters');
                        $this->emit('error', 'Veuillez selectionnez le pays de destination SVP  !');
                    }
                } else {
                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('error', 'Veuillez selectionnez les providers de destination SVP !');
                }
                $this->isValidating = false;
            } else {
                $this->resetInterField();
                $this->emit('modal-hide-inters');
                $this->emit('error', 'Veuillez verifiez votre mot de passe SVP !');
            }
        } catch (\Exception $exception) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function render()
    {

        $transaction = WalletTransaction::query();

        return view('livewire.transaction-inters.transaction-civ', [
            'walletSolde' => Wallet::where(
                'sid_user_id',
                sabdy()->id
            )->sum('amount'),
            'transactions' => $transaction
                ->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallet_transactions.status', 'PENDING')
                ->where('wallet_transactions.created_by', sabdy()->id)
                // ->where('wallet_transactions.type_transaction' , 'Depot')
                ->select('wallet_transactions.*')
                ->orderBy('wallet_transactions.created_at', 'desc')
                ->paginate(15),
            'usersCountry' => sabdy()->wallet->currencie->code,
            'countrie_number_local' => sabdy()->country
                ->indecative,
            'countrie_inter_ivoires' => $this->ProvidersForCountryInters,
            'showRemonterInterButton' => $this->showRemonterInterButton,
            'showTransacInterButton' => $this->showTransacInterButton,

        ]);
    }

    public function getProvidersForCountryIntersProperty()
    {
        $countryNameGroup = sabdy()->country->name_groupe;
        $isUnderDistributer = sabdy()->is_under_distributer;

        $providerMap = [];

        if ($isUnderDistributer) {
            if ($countryNameGroup == "CIV") {
                $providerMap = ['AFL', 'AFC', 'ASIE', 'AFE', 'URP'];
            } else {
                $providerMap = ['AFL', 'CIV', 'AFC', 'ASIE', 'AFE', 'URP'];
            }
        } else {
            $providerMaps = [
                "CIV" => ['AFL', 'AFC', 'USA', 'MAG', 'URP', 'ASIE', 'AFE'],
                "USA" => ['AFL', 'AFC', 'URP', 'MAG', 'CIV', 'ASIE', 'AFE'],
                "AFL" => ['AFL', 'AFC', 'URP', 'CIV', 'USA', 'MAG', 'ASIE', 'AFE'],
                "AFC" => ['CIV', 'AFC', 'URP', 'USA', 'MAG', 'AFL', 'ASIE', 'AFE'],
                "URP" => ['CIV', 'AFC', 'AFL', 'USA', 'MAG', 'ASIE', 'AFE'],
                "MAG" => ['CIV', 'AFC', 'URP', 'AFL', 'USA', 'MAG', 'ASIE', 'AFE'],
            ];

            $providerMap = $providerMaps[$countryNameGroup] ?? [];
        }


        return Country::whereIn('name_groupe', $providerMap)
            ->whereRobus(true)
            ->where('id', '!=', sabdy()->country->id)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function validateAmountSendLimit($som_transfers, $valeur_ceiling)
    {
        if ($som_transfers > $valeur_ceiling) {
            return false;
        }

        return true;
    }

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }

    public function updatedCountryInterOutCiv($countryId)
    {
        $this->providers = $this->getProvidersByType(new Provider, $countryId, 8);
    }

    public function updatedProviderInterOutCiv()
    {
        $this->transaction_amount_robus = '';
        $this->transaction_inter_amount_money = '';
    }

    public static function getProvidersByType($query, $countryId, $providerType)
    {
        $isUnderDistributer = sabdy()->is_under_distributer;

        $result = $query
            ->join('country_services', 'country_services.provider_id', '=', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*');
        if ($isUnderDistributer) {
            $result->where('providers.id', '!=', 51);
        }

        // Ordonner par le nom des fournisseurs
        return $result->orderBy('providers.name', 'asc')->get();
    }


    private function processCommissionMobiles($amount, $percent_robus, $country_robus, $walletInter, $admin_civ)
    {


        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $amount),
        ];

        $wallet->update($updatedValues);

        $valuer_deposit = $percent_robus->percent_deposit_under_distributer *  $amount;

        $valeur_amount_admin  = $percent_robus->percent_under_distributer * $amount;

        $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;

        $valuer_companie = $percent_robus->percent_companie * $amount;

        // Commissions Inters 

        if (sabdy()->country->currencie->value > $country_robus->currencie->value) {

            $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            $percent_companie_convertion =   (float)  $valuer_companie *   (float) sabdy()->country->currencie->value;
            $percent_noUnder_convertion =   (float)  $valuer_deposit *   (float) sabdy()->country->currencie->value;
        } elseif (sabdy()->country->currencie->value == $country_robus->currencie->value) {

            $admin_valeur_convertion =   (float)  $valeur_percent_admin;
            $percent_companie_convertion =   (float)  $valuer_companie;
            $percent_noUnder_convertion =   (float)  $valuer_deposit;
        } elseif (sabdy()->country->currencie->value < $country_robus->currencie->value) {

            $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            $percent_companie_convertion =   (float)  $valuer_companie *   (float) sabdy()->country->currencie->value;
            $percent_noUnder_convertion =   (float)  $valuer_deposit *   (float) sabdy()->country->currencie->value;
        }


        if (sabdy()->is_under_distributer == 1) {


            if ($this->showRemonterInterButton) {



                $walletInter->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterInterCButton) {
                    $walletInter->increment('commission_companie', $valuer_companie);

                    $totalCommission =  $percent_companie_convertion +  $percent_noUnder_convertion;

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_inter_id', $walletInter->id, $totalCommission, $admin_valeur_convertion);
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_inter_id', $walletInter->id, $admin_valeur_convertion, $valuer_deposit);
                }
            } else {


                app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_inter_id', $walletInter->id, $admin_valeur_convertion);
            }
        } else {

             app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_inter_id', $walletInter->id, $admin_valeur_convertion);

            $message =  'Vous avez effectué un paiement de ' . $walletInter->amount .  sabdy()->wallet->currencie->code .
                ' ' .
                'par ' . $walletInter->provider->name .
                ' a ' . $walletInter->created_at .
                ' votre demande est en-cours de validation ' .
                'Robus vous remercie';

            app(NotificationServiceBackend::class)->notificationRobusSend($message);
        }


        return true;
    }


    private function processCommissionsRobus($amount_valeur, $percent_robus, $walletInter)
    {


        $wallet = sabdy()->wallet;
        $admin_civ = $this->getAdminCIVProperty();


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $amount_valeur),
        ];

        $wallet->update($updatedValues);

        $valeur_amount_admin  = $percent_robus->percent_under_distributer * $amount_valeur;

        $valuer_deposit = $percent_robus->percent_deposit_under_distributer *  $amount_valeur;

        // Commissions Inters 
        if (sabdy()->is_under_distributer == 1) {

            // 50 %
            $valuer_admin = $percent_robus->percent_admin_under_distributer * $valeur_amount_admin;

            $valuer_with_drawal =  $percent_robus->percent_with_drawal_under_distributer * $valeur_amount_admin;

            $valeur_percent_admin  = $valuer_with_drawal + $valuer_admin;

            // 20%
            if ($this->showRemonterInterButton) {

                $walletInter->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = sabdy()->is_accepted_companie ?? false;
                $valuer_companie = $percent_robus->percent_companie * $amount_valeur;
                $admin_valeur_convertion =  $this->convertAdminCommission($valeur_percent_admin);
                $depot_valeur_convertion =  $this->convertAdminCommission($valuer_deposit);
                $compagnie_valeur_convertion =  $this->convertAdminCommission($valuer_companie);

                if ($account_accepted && $this->showRemonterInterCButton) {
                    $walletInter->increment('commission_companie', $valuer_companie);

                    $amount_all_commissions =  $depot_valeur_convertion +  $compagnie_valeur_convertion;

                    if ($admin_civ->count() > 0) {
                        $nmbre_admin = $admin_civ->count();
                        $valeur_single = ($admin_valeur_convertion) / $nmbre_admin;

                        foreach ($admin_civ as $user) {
                            app(CommissionServiceBackend::class)->processAdminCommissionSDistributer($user->id, $walletInter->id, $valeur_single, $amount_all_commissions);
                        }
                    }
                } else {
                    if ($admin_civ->count() > 0) {
                        $nmbre_admin = $admin_civ->count();
                        $valeur_single = ($admin_valeur_convertion) / $nmbre_admin;
                        foreach ($admin_civ as $user) {
                            app(CommissionServiceBackend::class)->processAdminCommissionSDistributer($user->id, $walletInter->id, $valeur_single, $depot_valeur_convertion);
                        }
                    }
                }
            } else {

                $admin_valeur_convertion =  $this->convertAdminCommission($valeur_amount_admin);

                if ($admin_civ->count() > 0) {
                    $nmbre_admin = $admin_civ->count();
                    $valeur_single = ($admin_valeur_convertion) / $nmbre_admin;

                    foreach ($admin_civ as $user) {
                        app(CommissionServiceBackend::class)->processAdminCommission($user->id, $walletInter->id, $valeur_single);
                    }
                }
            }
        } else {

            $message =  'Vous avez effectué un paiement de ' . $walletInter->amount .  sabdy()->wallet->currencie->code .
                ' ' .
                'par ' . $walletInter->provider->name .
                ' a ' . $walletInter->created_at .
                ' votre demande est en-cours de validation ' .
                'Robus vous remercie';

            app(NotificationServiceBackend::class)->notificationRobusSend($message);
            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent * $amount_valeur;

            $admin_valeur_convertion =  $this->convertAdminCommission($valeur_percent_admin);

            if ($admin_civ->count() > 0) {
                $nmbre_admin = $admin_civ->count();
                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    app(CommissionServiceBackend::class)->processAdminCommission($user->id, $walletInter->id, $valeur_single);
                }
            }
        }



        return true;
    }

    public function convertAdminCommission($amount)
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

    // Validated wallet
    private function validateWallet($country_robus)
    {

        // Search Wallet Send
        $wallet_search = Wallet::where('wallet_id', $this->compte_inter_number_robus)
            ->where('country_id', $country_robus->id)
            //   ->where('status', true)
            ->first();

        if ($this->compte_inter_number_robus == sabdy()->wallet->wallet_id || $wallet_search->sidUser->is_custumers == false) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Ce numero vous appartient !');
            return false;
        }

        return true;
    }

    // Validated phone
    private function validateAmount()
    {

        $val_amount = $this->transaction_inter_amount_money;

        if ($val_amount > sabdy()->wallet->amount) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Vérifiez votre solde !');
            return false;
        }

        return true;
    }
    // Validated phone
    private function validatePhone()
    {

        if ($this->phone_inter_out_civ == sabdy()->phone) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Ce numero vous appartient !');
            return false;
        }

        return true;
    }

    // Validated date daily
    private function validateDailyLimit($som_transfers, $valeur_ceiling)
    {
        if ($som_transfers >= $valeur_ceiling) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('warning', 'Votre montant journalier est atteint !');
            return false;
        }

        return true;
    }

    public function getSumValTransactionProperty()
    {
        $service = app(TotalAmoutUserServiveBackend::class);

        $somm_valeur = $service->getWalletBanksCeilingProperty()
            + $service->getWalletDepositsCeilingProperty()
            + $service->getWalletIntersCeilingProperty()
            + $service->getWalletPaymentsCeilingProperty()
            + $service->getWalletTransactionsCeilingProperty()
            + $service->getWalletPhoneCreditsCeilingProperty()
            + $service->getWalletReloadsCeilingProperty()
            + $service->getWalletBankCardCeilingProperty();

        return $somm_valeur;
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

    /**
     * Set the value of showMoneyButton
     *
     * @return  self
     */
    public function setShowMoneyButton($showMoneyButton)
    {
        $this->showMoneyButton = $showMoneyButton;

        return $this;
    }
}
