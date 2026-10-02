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
use App\Http\Services\ConvertionPercentBackend;
use App\Http\Services\TotalAmoutUserServiveBackend;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Offer;
use App\Models\Costs\PaymentCost;
use App\Models\Costs\PhoneCreditCost;

class TransactionCiv extends Component
{

    use WithFileUploads;

    public $transaction_amount_robus;
    public $provider_inter_out_civ;
    public $country_inter_out_civ;
    public $phone_inter_out_civ;
    public $interId;
    public $compte_inter_number_robus;
    public $account_number;
    public $providers = [];
    public $transaction_inter_amount_money;
    public $transaction_bank_amount;
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
    public $name_bank;
    public $number_accompte;
    public $phone_inter_expe;
    public $swit_code;
    public $num_piece_custumer;
    public $date_piece;
    public $name;
    public $name_sender;
    public $image;
    public $image_frontend;
    public $image_backend;
    public $image_preview;
    public $image_preview_frontend;
    public $image_preview_backend;
    public $piece_type;

    public $isValidating = false;
    public $isPasswordValid = false;

    // Gain commissions
    public $showRemonterTransButton = false;
    public $showRemonterCreditButton = false;
    public $showRemonterPaiementButton = false;
    public $showRemonterInterButton = false;
    public $showRemonterBankButton = false;
    public $showRemonterBankCButton = false;
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
    public $showBanSendButton = false;

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

    // New transaction
    // CIE
    public $paiement_name_cie;
    public $paiement_id_invoice;
    public $paiement_name;
    public $paiement_amount_total;
    public $paiement_amount_partiel;
    public $paiement_id_invoice_pre;
    public $pre_phone_invoice;
    public $pre_id_invoice;

    // SODECIE
    public $paiement_name_sodecie;
    public $paiement_amount_t_sodecie;
    public $paiement_amount_p_sodecie;
    public $paiement_decoder;
    public $paiement_num_contrat;
    public $provider_payment_edit;

    // CANAL
    public $paiement_phone_canal;
    public $paiement_decoder_canal;
    public $paiement_amount_Canal;
    public $offer_paiement;
    // Choise
    public $paiementChoix_phone;
    public $paiementChoix_type;
    public $facture_choix_type;

    // Reload card
    public $amount_reloadcard;
    public $account_number_reload_card;
    public $name_reloadcard;

    // New transactions
    public $number_phone;
    public $full_name_tresort;
    public $amount_djamo;
    public $full_name_djamo;
    public $full_name_push;
    public $name_canal_send;
    public $amount_canal_send;

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
        $this->image = '';
        // $this->emit('modal-hide-transfers-validated');
        // $this->emit('modal-hide-transfers');
    }

    public function updatedImage()
    {


        if ($this->image) {
            $this->image_preview = $this->createImagePreview();
        }

        if ($this->image_frontend) {
            $this->image_preview_frontend = $this->createImageFrontendPreview();
        }

        if ($this->image_backend) {
            $this->image_preview_backend = $this->createImageBackendPreview();
        }
    }

    public function updatedImageFrontend()
    {

        if ($this->image_frontend) {
            $this->image_preview_frontend = $this->createImageFrontendPreview();
        }
    }

    public function updatedImageBackend()
    {

        if ($this->image_backend) {
            $this->image_preview_backend = $this->createImageBackendPreview();
        }
    }


    private function createImageFrontendPreview()
    {
        try {
            $path = $this->image_frontend->getRealPath();
            $type = $this->image_frontend->getClientOriginalExtension();
            $data = file_get_contents($path);

            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function createImageBackendPreview()
    {
        try {
            $path = $this->image_backend->getRealPath();
            $type = $this->image_backend->getClientOriginalExtension();
            $data = file_get_contents($path);

            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function createImagePreview()
    {
        try {
            $path = $this->image->getRealPath();
            $type = $this->image->getClientOriginalExtension();
            $data = file_get_contents($path);

            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } catch (\Exception $e) {
            return null;
        }
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
            'Active International' => 'showRemonterInterButton',
            'Active Bank Send' => 'showRemonterBankButton',
        ];

        $restrictionCompangieMap = [
            'Active International' => 'showRemonterInterCButton',
            'Active Bank Send' => 'showRemonterBankCButton',
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
            'TRANSFERT BANK SEND' => 'showBanSendButton',
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
        $this->phone_inter_out_civ = '';
        $this->compte_inter_number_robus = '';
        $this->name_custumer = '';
        $this->num_piece_custumer = '';
        $this->date_piece = '';
        $this->name = '';
        $this->image = '';
        $this->name_sender = '';
        $this->transaction_inter_amount_money = '';
        $this->transaction_inter_amount_money_receved = '';
        $this->account_number = '';
        $this->resetCommissionValues();

        return $this->providers;
    }


    public function getPieceTypeLabel($type)
    {
        return match ($type) {
            '1' => 'Passport',
            '2' => 'Carte Nationale d\'Identité',
            '3' => 'Permis de conduire',
            '4' => 'Carte consulaire',
            default => 'Document'
        };
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

    public function updatedTransactionBankAmount()
    {
        $this->calculateAmountBanks();
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

            if ($this->provider_inter_out_civ == 104) {
                $commissionInter =  app(CommissionServicePercentBackend::class)->getRobusPercentageDjamoInter();
            } elseif ($this->provider_inter_out_civ == 105) {
                $commissionInter =  app(CommissionServicePercentBackend::class)->getRobusPercentagePushInter();
            } elseif ($this->provider_inter_out_civ == 106) {
                $commissionInter =  app(CommissionServicePercentBackend::class)->getRobusPercentageTresortInter();
            } elseif ($this->provider_inter_out_civ == 124) {
                $commissionInter =  app(CommissionServicePercentBackend::class)->getRobusPercentageBankSend($this->country_inter_out_civ);
            } elseif ($this->provider_inter_out_civ == 52 || $this->provider_inter_out_civ == 53 || $this->provider_inter_out_civ == 54) {
                $commissionInter =  app(CommissionServicePercentBackend::class)->getRobusPercentagePaymentInter();
            } elseif ($this->provider_inter_out_civ == 59 || $this->provider_inter_out_civ == 60) {
                $commissionInter =  app(CommissionServicePercentBackend::class)->getRobusPercentageRelaodCard();
            } elseif ($this->provider_inter_out_civ == 129 || $this->provider_inter_out_civ == 130 || $this->provider_inter_out_civ == 131 || $this->provider_inter_out_civ == 132 || $this->provider_inter_out_civ == 133 || $this->provider_inter_out_civ == 134 || $this->provider_inter_out_civ == 135 || $this->provider_inter_out_civ == 136 || $this->provider_inter_out_civ == 137 || $this->provider_inter_out_civ == 138 || $this->provider_inter_out_civ == 139 || $this->provider_inter_out_civ == 140 || $this->provider_inter_out_civ == 141) {
                $commissionInter =  app(CommissionServicePercentBackend::class)->getRobusPercentageInterVersement();
            } elseif ($this->provider_inter_out_civ == 61 || $this->provider_inter_out_civ == 62 || $this->provider_inter_out_civ == 63 || $this->provider_inter_out_civ == 64 || $this->provider_inter_out_civ == 65 || $this->provider_inter_out_civ == 67 || $this->provider_inter_out_civ == 69) {
                $commissionInter =  app(CommissionServicePercentBackend::class)->getRobusPercentageOrderCard();
            } elseif ($this->provider_inter_out_civ == 56 || $this->provider_inter_out_civ == 57 || $this->provider_inter_out_civ == 58) {
                $commissionInter =  PhoneCreditCost::whereId(2)->first();
            } else {
                $commissionInter = app(CommissionServicePercentBackend::class)->getRobusPercentageInter($countryRobus, $this->provider_inter_out_civ);
            }


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

    public function calculateAmountBanks()
    {
        $this->resetCommissionValues();

        $countryBankRobus = Country::find(107);
        $reelAmount = $this->transaction_bank_amount;

        if ($reelAmount === '') {
            return;
        }

        // Récupération optimisée des commissions
        $commissionBank = app(CommissionServicePercentBackend::class)
            ->getRobusPercentageBankSend($this->country_inter_out_civ);

        if (!$commissionBank) {
            $this->setNoGroupValues();
            return;
        }

        $levels = $commissionBank->levelBankAuthers()
            ->orderBy('amount_begin', 'asc')
            ->get();

        $valeur_first = $levels->first()?->amount_begin;
        $valeur_end   = $levels->last()?->amount_end;

        // Conversion en devise utilisateur
        $currencyValue = sabdy()->country->currencie->value;
        $countryCurrencyValue = $countryBankRobus->currencie->value;

        // Conversion selon ratio
        $reelAmountConvertion = $reelAmount * ($currencyValue / $countryCurrencyValue);

        // Vérification limites
        if ($valeur_first !== null && $reelAmountConvertion < $valeur_first) {
            $msg = "Montant min est : " . number_format($valeur_first, 2, ',', ' ');
            return [$msg, $msg];
        }

        if ($valeur_end !== null && $reelAmountConvertion > $valeur_end) {
            $msg = "Montant maxi est : " . number_format($valeur_end, 2, ',', ' ');
            return [$msg, $msg];
        }

        // Calcul de la commission correspondant au niveau
        foreach ($levels as $level) {
            if ($reelAmountConvertion >= $level->amount_begin && $reelAmountConvertion <= $level->amount_end) {

                $commission_pay = $level->amount !== null ? (float) $level->amount : null;

                $this->calculateBankCommission($level);

                $commission_receive = $reelAmountConvertion >= 0 ? (float) $reelAmountConvertion : 'Montant négatif';
                $this->transaction_inter_amount_money_receved = (float)$commission_receive - (float)$commission_pay;

                break;
            }
        }

        // Au cas où aucun niveau ne correspond
        if (!isset($commission_pay)) {
            $this->setNoGroupValues();
        }

        return [$commission_pay ?? null, $commission_receive ?? null];
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
        $reelAmount = (float) $reelAmount;

        $isDistributor = sabdy()->is_under_distributer || sabdy()->is_distributer;
        $merchantProviders = [104, 105, 106, 56, 57, 58, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141];
        $merchantPaiementProviders = [52, 53, 54];
        $isMerchantProvider = in_array($this->provider_inter_out_civ, $merchantProviders);

        if ($isDistributor) {

            if ($isMerchantProvider) {
                // Distributeur + marchand
                $this->commission_transaction_val_percent_pay =
                    $commissionInter->percent * $reelAmount;

                $this->commission_transaction_val_inter_pay =
                    $commissionInter->percent_merchands * $reelAmount;
            } else if ($merchantPaiementProviders) {

                $this->commission_transaction_val_percent_pay =
                    $commissionInter->amount_auther;

                $this->commission_transaction_val_inter_pay =
                    $commissionInter->comission_auther;
            } else {
                // Distributeur classique
                $this->commission_transaction_val_percent_pay =
                    $commissionInter->percent_under_distributer * $reelAmount;

                $this->commission_transaction_val_inter_pay =
                    $commissionInter->percent_deposit_under_distributer * $reelAmount;
            }
        } else {

            if ($isMerchantProvider) {
                // Non-distributeur + marchand
                $this->commission_transaction_val_percent_pay =
                    $commissionInter->percent * $reelAmount;

                $this->commission_transaction_val_inter_pay =
                    $commissionInter->percent_merchands * $reelAmount;
            } else {
                // Non-distributeur classique
                $this->commission_transaction_val_percent_pay =
                    $commissionInter->percent * $reelAmount;

                $this->commission_transaction_val_inter_pay =
                    $this->commission_transaction_val_percent_pay;
            }
        }
    }

    private function calculateBankCommission($commissionInter)
    {

        $countryBankRobus = Country::find(107);
        $currencyValue = sabdy()->country->currencie->value;
        $countryCurrencyValue = $countryBankRobus->currencie->value;
        $commissionAmountConverted = $commissionInter->percent_under * ($currencyValue / $countryCurrencyValue);
        $amountPaieConverted = $commissionInter->amount * ($currencyValue / $countryCurrencyValue);
        $amountPaieAdminConverted = $commissionInter->percent_admin * ($currencyValue / $countryCurrencyValue);
        $isDistributor = sabdy()->is_under_distributer || sabdy()->is_distributer;

        if ($isDistributor) {

            $this->commission_transaction_val_percent_pay =  $amountPaieConverted;

            $this->commission_transaction_val_inter_pay = $commissionAmountConverted;
        } else {

            $this->commission_transaction_val_percent_pay =  $amountPaieConverted;

            $this->commission_transaction_val_inter_pay = $amountPaieAdminConverted;
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
        $this->image_frontend = '';
        $this->image_backend = '';
        $this->image_preview_frontend = '';
        $this->image_preview_backend = '';
        $this->isPasswordValid = '';
        $this->piece_type = null;
        // CIE
        $this->paiement_name_cie = '';
        $this->paiement_id_invoice = '';
        $this->paiement_name = '';
        $this->paiement_amount_total = '';
        $this->paiement_amount_partiel = '';
        $this->paiement_id_invoice_pre = '';
        $this->pre_phone_invoice = '';
        $this->pre_id_invoice = '';
        // SODECIE
        $this->paiement_name_sodecie = '';
        $this->paiement_amount_t_sodecie = '';
        $this->paiement_amount_p_sodecie = '';
        $this->paiement_decoder = '';
        $this->paiement_num_contrat = '';
        $this->provider_payment_edit = '';
        $this->password_validated = '';
        // CANAL
        $this->paiement_phone_canal = '';
        $this->paiement_decoder_canal = '';
        $this->paiement_amount_Canal = '';
        $this->offer_paiement = '';
        // Choise
        $this->paiementChoix_phone = '';
        $this->paiementChoix_type = '';
        $this->facture_choix_type = '';
        // Reload card
        $this->amount_reloadcard = '';
        $this->account_number_reload_card = '';
        $this->name_reloadcard = '';
        // New transactions
        $this->number_phone = '';
        $this->full_name_tresort = '';
        $this->amount_djamo = '';
        $this->full_name_djamo = '';
        $this->full_name_push = '';
        $this->name_canal_send = '';
        $this->amount_canal_send = '';
        $this->stepInter = 1;
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
        $this->image_preview_frontend = '';
        $this->image_preview_backend = '';
        $this->piece_type = '';
        $this->isPasswordValid = false;
        $this->isValidating = false;
        $this->transaction_inter_amount_money_receved = '';
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
        $this->emit('transaction_inters');
    }

    public function addInterOut()
    {

        $provider_payment = Provider::whereId($this->provider_inter_out_civ)->first();
        $valeur_ceiling = sabdy()->silver_ceiling;
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();
        $walletUser = sabdy();
        $offer = Offer::whereId($this->offer_paiement)->first();

        // try {

            if ($provider_payment) {


                if (in_array($this->provider_inter_out_civ, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78, 93, 94, 95, 96, 97, 98, 100, 101, 102, 103])) {

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
                    $this->stepInter = 2;
                } elseif (in_array($this->provider_inter_out_civ, [99])) {


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

                    $this->stepInter = 2;
                } elseif (in_array($this->provider_inter_out_civ, [124])) {

                    $this->validate(
                        [
                            'transaction_bank_amount' => 'required',
                            'phone_inter_out_civ' => 'required',
                            'country_inter_out_civ' => 'required',
                            "name_custumer" => 'required',
                            "name_sender" => 'required',
                            "num_piece_custumer" => 'required',
                            "phone_inter_expe" => 'required',
                            "swit_code" => 'required',
                            "date_piece" => 'required',
                            'account_number' => 'required',
                            'name' => 'required',
                            'image'                    => 'nullable|image|mimes:png,jpg,jpeg',
                            'image_frontend'           => 'nullable|image|mimes:png,jpg,jpeg',
                            'image_backend'            => 'nullable|image|mimes:png,jpg,jpeg',
                            'piece_type' => 'required',

                        ],
                        [
                            'phone_inter_expe.required' => 'Le numero expediteur est obligatoire',
                            'swit_code.required' => 'Le Code swift/IFSC/Bic Code est obligatoire',
                            'piece_type.required' => 'Le type de piece est obligatoire',
                            'transaction_bank_amount.required' =>
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

                    $this->stepInter = 2;
                } elseif (in_array($this->provider_inter_out_civ, [104, 105, 106])) {

                    $this->validate(
                        [
                            'transaction_inter_amount_money' => 'required',
                            'phone_inter_out_civ' => 'required',
                            'country_inter_out_civ' => 'required',
                            "name_custumer" => 'required',
                            // 'image' => 'image|mimes:png,jpg,jpeg|max:102400',
                        ],
                        [
                            'transaction_inter_amount_money.required' =>
                            'Votre montant est obligatoire',
                            'country_inter_out_civ.required' => 'Pays  est obligatoire',
                            'phone_inter_out_civ.required' => 'Numero est obligatoire',
                            'name_custumer.required' => 'Nom est obligatoire',
                        ]
                    );
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
                } elseif ($this->provider_inter_out_civ == 52 || $this->provider_inter_out_civ == 53 || $this->provider_inter_out_civ == 54) {

                    // Paiement invoice transaction
                    $percent_robus =  PaymentCost::whereId(1)->first();

                    if ($this->provider_inter_out_civ == 53) {

                        // Validation rules and messages
                        $validationRules = [
                            'paiement_name_cie' => 'required',
                            'transaction_inter_amount_money' => 'required',
                            'facture_choix_type' => 'required',
                        ];
                        $validationMessages = [
                            'transaction_inter_amount_money.required' => 'Votre montant est obligatoire',
                            'paiement_name_cie.required' => 'Nom est obligatoire',
                            'facture_choix_type.required' =>
                            'Le choix est obligatoire'
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
                            $this->resetInterField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                            return;
                        }

                        // dd($this->paiement_amount_total);
                        $reel_amount =  $this->search_amount($this->paiement_amount_total);

                        // $validatedamountDays->search_amount(2000);
                        $valAmount = $reel_amount +  $percent_robus->amount_auther;

                        if ($valAmount > $walletUser->wallet->amount) {
                            $this->resetInterField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Verifier votre montant  svp !');
                            return;
                        }

                        $this->stepInter = 2;
                    } elseif ($this->provider_inter_out_civ == 54) {

                        //   Provider SODECI
                        $validationRules = [
                            'paiement_name_sodecie' => 'required',
                            'paiement_num_contrat' => 'required|max:9',
                            'transaction_inter_amount_money' => 'required',
                        ];
                        $validationMessages = [
                            'transaction_inter_amount_money.required' =>
                            'Votre montant est obligatoire',
                            'paiement_name_sodecie.required' =>
                            'Nom  est obligatoire',
                            'paiement_num_contrat.required' =>
                            'Numero  est obligatoire',
                        ];


                        // Validate the input
                        $this->validate($validationRules, $validationMessages);

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetInterField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                            return;
                        }

                        $reel_amount = $this->search_amount($this->paiement_amount_t_sodecie);

                        $valAmount = $reel_amount +  $percent_robus->amount_auther;

                        if ($valAmount > $walletUser->wallet->amount) {
                            $this->resetInterField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Verifier votre montant  svp !');
                            return;
                        }

                        $this->stepInter = 2;
                    } elseif ($this->provider_inter_out_civ == 52) {

                        $validationRules = [
                            'paiementChoix_phone' => 'required',
                        ];
                        $validationMessages = [
                            'paiementChoix_phone.required' =>
                            'Le choix est obligatoire',

                        ];

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

                        // Percent commission providers
                        $val_amount = $offer->amount + $percent_robus->amount_canal;
                        $this->name_canal_send  = $offer->name;
                        $this->amount_canal_send  = $val_amount;

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetInterField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                            return;
                        }


                        if ($val_amount > $walletUser->wallet->amount) {
                            $this->resetInterField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Verifier votre montant  svp !');
                            return;
                        }

                        $this->stepInter = 2;
                    }
                } elseif ($this->provider_inter_out_civ == 56 || $this->provider_inter_out_civ == 57 || $this->provider_inter_out_civ == 58) {

                    // Phone credit transaction
                    $percent_robus =  PhoneCreditCost::whereId(2)->first();

                    $this->validate(
                        [
                            'name_custumer' => 'required',
                            'phone_inter_out_civ' => 'required'
                        ],
                        [
                            'name_custumer.required' =>
                            'Nom & prenom est obligatoire',
                            'phone_inter_out_civ.required' =>
                            'Numero de télephone est obligatoire'
                        ]
                    );

                    $this->stepInter = 2;
                } elseif ($this->provider_inter_out_civ == 59 || $this->provider_inter_out_civ == 60) {

                    // Reload card transaction
                    $this->validate([
                        'account_number_reload_card' => 'required',
                        'name_reloadcard' => 'required',
                    ], [
                        'name_reloadcard.required' => 'Nom & prénom est obligatoire',
                        'account_number_reload_card.required' => 'Numero est obligatoire',
                    ]);

                    $this->stepInter = 2;
                } elseif ($this->provider_inter_out_civ == 61 || $this->provider_inter_out_civ == 62 || $this->provider_inter_out_civ == 63 || $this->provider_inter_out_civ == 64 || $this->provider_inter_out_civ == 65) {

                    // Order card transaction
                    $this->validate([
                        'account_number_reload_card' => 'required',
                        'name_reloadcard' => 'required',
                    ], [
                        'name_reloadcard.required' => 'Nom & prénom est obligatoire',
                        'account_number_reload_card.required' => 'Numero est obligatoire',
                    ]);

                    $this->stepInter = 2;
                } elseif (
                    $this->provider_inter_out_civ == 59 ||
                    $this->provider_inter_out_civ == 60 ||
                    $this->provider_inter_out_civ == 129
                    || $this->provider_inter_out_civ == 130
                    || $this->provider_inter_out_civ == 131
                    || $this->provider_inter_out_civ == 132
                    || $this->provider_inter_out_civ == 133
                    || $this->provider_inter_out_civ == 134
                    || $this->provider_inter_out_civ == 135
                    || $this->provider_inter_out_civ == 136
                    || $this->provider_inter_out_civ == 137
                    || $this->provider_inter_out_civ == 138
                    || $this->provider_inter_out_civ == 139
                    || $this->provider_inter_out_civ == 140
                    || $this->provider_inter_out_civ == 141
                ) {

                    // Order card transaction
                    $this->validate(
                        [
                            'num_accompte' => 'required',
                            'bank_code' => 'required',
                            'guichet_code' => 'required',
                            'num_authers' => 'required'
                        ],
                        [
                            'num_accompte.required' =>
                            'Numero est obligatoire',
                            'bank_code.required' =>
                            'Code de la banque obligatoire',
                            'guichet_code.required' =>
                            'Code du guichet est obligatoire',
                            'num_authers.required' =>
                            'Cle obligatoire est obligatoire'
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
        // } catch (\Exception $exception) {
        //     $this->resetInterField();
        //     $this->emit('modal-hide-inters');
        //     $this->emit('error', 'Error de message demande a l\'admin !');
        // }
    }


    public function validatedTransaction()
    {

        $country_robus = Country::whereId($this->country_inter_out_civ)
            ->first();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =     app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();
        $provider_payment = Provider::whereId($this->provider_inter_out_civ)->first();
        $valeur_ceiling = sabdy()->silver_ceiling;
        $walletUser = sabdy();
        $offer = Offer::whereId($this->offer_paiement)->first();

        // try {


        $this->validate([
            'password_validated' => 'required',
        ]);

        if (Hash::check($this->password_validated, sabdy()->password)) {

            $this->isValidating = true;

            $this->password_validated = "";

            if (in_array($this->provider_inter_out_civ, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78, 93, 94, 95, 96, 97, 98, 100, 101, 102, 103])) {

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
            } elseif (in_array($this->provider_inter_out_civ, [124])) {

                if (sabdy()->is_under_distributer) {


                    $this->validate(
                        [
                            'transaction_bank_amount' => 'required',
                            'phone_inter_out_civ' => 'required',
                            'country_inter_out_civ' => 'required',
                            "name_custumer" => 'required',
                            "name_sender" => 'required',
                            "num_piece_custumer" => 'required',
                            "phone_inter_expe" => 'required',
                            "swit_code" => 'required',
                            "date_piece" => 'required',
                            'account_number' => 'required',
                            'name' => 'required',
                            'image'                    => 'nullable|image|mimes:png,jpg,jpeg',
                            'image_frontend'           => 'nullable|image|mimes:png,jpg,jpeg',
                            'image_backend'            => 'nullable|image|mimes:png,jpg,jpeg',

                        ],
                        [
                            'phone_inter_expe.required' => 'Le numero expediteur est obligatoire',
                            'swit_code.required' => 'Le Code swift/IFSC/Bic Code est obligatoire',
                            'transaction_bank_amount.required' =>
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
                            'transaction_bank_amount' => 'required',
                            'phone_inter_out_civ' => 'required',
                            'country_inter_out_civ' => 'required',
                            "name_custumer" => 'required',
                            "name_sender" => 'required',
                            "num_piece_custumer" => 'required',
                            "phone_inter_expe" => 'required',
                            "swit_code" => 'required',
                            "date_piece" => 'required',
                            'account_number' => 'required',
                            'name' => 'required',
                            'image'                    => 'nullable|image|mimes:png,jpg,jpeg',
                            'image_frontend'           => 'nullable|image|mimes:png,jpg,jpeg',
                            'image_backend'            => 'nullable|image|mimes:png,jpg,jpeg',

                        ],
                        [
                            'phone_inter_expe.required' => 'Le numero expediteur est obligatoire',
                            'swit_code.required' => 'Le Code swift/IFSC/Bic Code est obligatoire',
                            'transaction_bank_amount.required' =>
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
                }
            } elseif (in_array($this->provider_inter_out_civ, [104, 105, 106])) {


                if (sabdy()->is_under_distributer) {

                    $this->validate(
                        [
                            'transaction_inter_amount_money' => 'required',
                            'phone_inter_out_civ' => 'required',
                            'country_inter_out_civ' => 'required',
                            "name_custumer" => 'required',
                            'image' => 'image|mimes:png,jpg,jpeg|max:102400',
                        ],
                        [
                            'transaction_inter_amount_money.required' =>
                            'Votre montant est obligatoire',
                            'country_inter_out_civ.required' => 'Pays  est obligatoire',
                            'phone_inter_out_civ.required' => 'Numero est obligatoire',
                            'name_custumer.required' => 'Nom est obligatoire',
                        ]
                    );
                } else {

                    $this->validate(
                        [
                            'transaction_inter_amount_money' => 'required',
                            'phone_inter_out_civ' => 'required',
                            'country_inter_out_civ' => 'required',
                            "name_custumer" => 'required',
                            'image' => 'image|mimes:png,jpg,jpeg|max:102400',
                        ],
                        [
                            'transaction_inter_amount_money.required' =>
                            'Votre montant est obligatoire',
                            'country_inter_out_civ.required' => 'Pays  est obligatoire',
                            'phone_inter_out_civ.required' => 'Numero est obligatoire',
                            'name_custumer.required' => 'Nom est obligatoire',
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
            } elseif ($this->provider_inter_out_civ == 52 || $this->provider_inter_out_civ == 53 || $this->provider_inter_out_civ == 54) {

                // Paiement invoice transaction
                $percent_robus =  PaymentCost::whereId(1)->first();

                if ($this->provider_inter_out_civ == 53) {

                    // Validation rules and messages
                    $validationRules = [
                        'paiement_name_cie' => 'required',
                        'transaction_inter_amount_money' => 'required',
                        'facture_choix_type' => 'required',
                    ];
                    $validationMessages = [
                        'transaction_inter_amount_money.required' => 'Votre montant est obligatoire',
                        'paiement_name_cie.required' => 'Nom est obligatoire',
                        'facture_choix_type.required' =>
                        'Le choix est obligatoire'
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
                        $this->resetInterField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('warning', 'Votre montant journier est atteint !');
                        return;
                    }

                    // dd($this->paiement_amount_total);
                    $reel_amount =  $this->search_amount($this->paiement_amount_total);

                    // $validatedamountDays->search_amount(2000);
                    $valAmount = $reel_amount +  $percent_robus->amount_auther;

                    if ($valAmount > $walletUser->wallet->amount) {
                        $this->resetInterField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('error', 'Verifier votre montant  svp !');
                        return;
                    }
                } elseif ($this->provider_inter_out_civ == 54) {

                    //   Provider SODECI
                    $validationRules = [
                        'paiement_name_sodecie' => 'required',
                        'paiement_num_contrat' => 'required|max:9',
                        'transaction_inter_amount_money' => 'required',
                    ];
                    $validationMessages = [
                        'transaction_inter_amount_money.required' =>
                        'Votre montant est obligatoire',
                        'paiement_name_sodecie.required' =>
                        'Nom  est obligatoire',
                        'paiement_num_contrat.required' =>
                        'Numero  est obligatoire',
                    ];


                    // Validate the input
                    $this->validate($validationRules, $validationMessages);

                    if ($som_transfers >= $valeur_ceiling) {
                        $this->resetInterField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('warning', 'Votre montant journier est atteint !');
                        return;
                    }

                    $reel_amount = $this->search_amount($this->paiement_amount_t_sodecie);

                    $valAmount = $reel_amount +  $percent_robus->amount_auther;

                    if ($valAmount > $walletUser->wallet->amount) {
                        $this->resetInterField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('error', 'Verifier votre montant  svp !');
                        return;
                    }
                } elseif ($this->provider_inter_out_civ == 52) {

                    $validationRules = [
                        'paiementChoix_phone' => 'required',
                    ];
                    $validationMessages = [
                        'paiementChoix_phone.required' =>
                        'Le choix est obligatoire',

                    ];

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

                    // Percent commission providers
                    $val_amount = $offer->amount + $percent_robus->amount_canal;
                    $this->name_canal_send  = $offer->name;
                    $this->amount_canal_send  = $val_amount;

                    if ($som_transfers >= $valeur_ceiling) {
                        $this->resetInterField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('warning', 'Votre montant journier est atteint !');
                        return;
                    }


                    if ($val_amount > $walletUser->wallet->amount) {
                        $this->resetInterField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('error', 'Verifier votre montant  svp !');
                        return;
                    }
                }
            } elseif ($this->provider_inter_out_civ == 56 || $this->provider_inter_out_civ == 57 || $this->provider_inter_out_civ == 58) {

                // Phone credit transaction
                $percent_robus =  PhoneCreditCost::whereId(2)->first();

                $this->validate(
                    [
                        'name_custumer' => 'required',
                        'phone_inter_out_civ' => 'required'
                    ],
                    [
                        'name_custumer.required' =>
                        'Nom & prenom est obligatoire',
                        'phone_inter_out_civ.required' =>
                        'Numero de télephone est obligatoire'
                    ]
                );
            } elseif ($this->provider_inter_out_civ == 59 || $this->provider_inter_out_civ == 60) {

                // Reload card transaction
                $this->validate([
                    'account_number_reload_card' => 'required',
                    'name_reloadcard' => 'required',
                ], [
                    'name_reloadcard.required' => 'Nom & prénom est obligatoire',
                    'account_number_reload_card.required' => 'Numero est obligatoire',
                ]);
            } elseif ($this->provider_inter_out_civ == 61 || $this->provider_inter_out_civ == 62 || $this->provider_inter_out_civ == 63 || $this->provider_inter_out_civ == 64 || $this->provider_inter_out_civ == 65) {

                // Order card transaction
                $this->validate([
                    'account_number_reload_card' => 'required',
                    'name_reloadcard' => 'required',
                ], [
                    'name_reloadcard.required' => 'Nom & prénom est obligatoire',
                    'account_number_reload_card.required' => 'Numero est obligatoire',
                ]);
            } elseif (
                $this->provider_inter_out_civ == 59 ||
                $this->provider_inter_out_civ == 60 ||
                $this->provider_inter_out_civ == 129
                || $this->provider_inter_out_civ == 130
                || $this->provider_inter_out_civ == 131
                || $this->provider_inter_out_civ == 132
                || $this->provider_inter_out_civ == 133
                || $this->provider_inter_out_civ == 134
                || $this->provider_inter_out_civ == 135
                || $this->provider_inter_out_civ == 136
                || $this->provider_inter_out_civ == 137
                || $this->provider_inter_out_civ == 138
                || $this->provider_inter_out_civ == 139
                || $this->provider_inter_out_civ == 140
                || $this->provider_inter_out_civ == 141
            ) {

                // Order card transaction
                $this->validate(
                    [
                        'num_accompte' => 'required',
                        'bank_code' => 'required',
                        'guichet_code' => 'required',
                        'num_authers' => 'required'
                    ],
                    [
                        'num_accompte.required' =>
                        'Numero est obligatoire',
                        'bank_code.required' =>
                        'Code de la banque obligatoire',
                        'guichet_code.required' =>
                        'Code du guichet est obligatoire',
                        'num_authers.required' =>
                        'Cle obligatoire est obligatoire'
                    ]
                );
            } else {
                $this->resetInterField();
                $this->emit('modal-hide-inters');
                $this->emit('error', 'Ce providers n\'est pas encore disponible chez Robus !');
            }


            if ($this->provider_inter_out_civ == 104) {
                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentageDjamoInter();
            } elseif ($this->provider_inter_out_civ == 105) {
                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentagePushInter();
            } elseif ($this->provider_inter_out_civ == 106) {
                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentageTresortInter();
            } elseif ($this->provider_inter_out_civ == 124) {
                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentageBankSend($this->country_inter_out_civ);
            } elseif ($this->provider_inter_out_civ == 56 || $this->provider_inter_out_civ == 57 || $this->provider_inter_out_civ == 58) {
                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentagePhoneInter();
            } elseif ($this->provider_inter_out_civ == 52 || $this->provider_inter_out_civ == 53 || $this->provider_inter_out_civ == 54) {
                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentagePaymentInter();
            } elseif ($this->provider_inter_out_civ == 59 || $this->provider_inter_out_civ == 60) {
                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentageRelaodCard();
            } elseif ($this->provider_inter_out_civ == 129 || $this->provider_inter_out_civ == 130 || $this->provider_inter_out_civ == 130 || $this->provider_inter_out_civ == 131 || $this->provider_inter_out_civ == 132 || $this->provider_inter_out_civ == 133 || $this->provider_inter_out_civ == 134 || $this->provider_inter_out_civ == 135 || $this->provider_inter_out_civ == 136 || $this->provider_inter_out_civ == 137 || $this->provider_inter_out_civ == 138 || $this->provider_inter_out_civ == 139 || $this->provider_inter_out_civ == 140 || $this->provider_inter_out_civ == 141) {
                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentageInterVersement();
            } elseif ($this->provider_inter_out_civ == 61 || $this->provider_inter_out_civ == 62 || $this->provider_inter_out_civ == 63 || $this->provider_inter_out_civ == 64 || $this->provider_inter_out_civ == 65 || $this->provider_inter_out_civ == 67 || $this->provider_inter_out_civ == 69) {
                $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentageOrderCard();
            } else {
                $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageInter($country_robus, $this->provider_inter_out_civ);
            }

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
            } elseif (in_array($this->provider_inter_out_civ, [104, 105, 106])) {

                if ($this->validatePhone($this->phone_inter_out_civ)) {

                    if ($percent_robus != null) {

                        $valeur_max_send  =  $country_robus->amount_max_send;

                        if ($this->validateAmountSendLimit($reel_amount, $valeur_max_send)) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount($reel_amount)) {

                                    if (sabdy()->is_under_distributer) {

                                        if ($this->provider_inter_out_civ == 104 || $this->provider_inter_out_civ == 105 || $this->provider_inter_out_civ == 106) {
                                            $valeur_received = $reel_amount - ($percent_robus->percent * $reel_amount);
                                        } else {
                                            $valeur_received = $reel_amount - ($percent_robus->percent_under_distributer * $reel_amount);
                                        }
                                    } else {

                                        $valeur_received = $reel_amount - ($percent_robus->percent * $reel_amount);
                                    }

                                    $actionPercentId = $percent_robus?->id;

                                    if ($this->image) {
                                        // Chemin de destination
                                        $userFolder = 'transactions/' . sabdy()->firstname;
                                        $filename = date('YmdHis') . '_' . uniqid() . '.' . $this->image->getClientOriginalExtension();
                                        $imagePath = $this->image->storeAs($userFolder, $filename, 'public');
                                    }
                                    $imagePath = null;

                                    $walletInter = app(TransfertServiceBackend::class)->createMobileWalletInterWith(
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
                                        $this->account_number,
                                        $actionPercentId,
                                        $imagePath
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
            } elseif (in_array($this->provider_inter_out_civ, [124])) {

                if ($this->validatePhone($this->phone_inter_out_civ)) {

                    if (!$percent_robus) {
                        return $this->failInter('Niveau de pourcentage introuvable !!');
                    }

                    $countryBank = Country::findOrFail(107);
                    $userRate    = sabdy()->country->currencie->value;
                    $bankRate    = $countryBank->currencie->value;

                    $amountUser = $this->search_amount($this->transaction_bank_amount);
                    if ($amountUser <= 0) {
                        return;
                    }
                    $amountCfa = $amountUser * ($userRate / $bankRate);
                    $valeur_max_send = $country_robus->amount_max_send * ($userRate / $bankRate);

                    if (!$this->validateAmountSendLimit($amountUser, $valeur_max_send)) {
                        return $this->failInter(
                            "Le montant maximum à envoyer est de : {$valeur_max_send} {$country_robus->currencie->code}"
                        );
                    }

                    if (!$this->validateDailyLimit($som_transfers, $valeur_ceiling)) {
                        return $this->failInter('Le montant maxi journalier est atteint !');
                    }

                    if (!$this->validateAmount($amountUser)) {
                        return $this->failInter('Vérifiez votre solde SVP !');
                    }

                    /* ===============================
                            Conversion devise → CFA
                            ================================ */


                    /* ===============================
                            Niveaux de commission
                            ================================ */

                    $levels = $percent_robus->levelBankAuthers()
                        ->orderBy('amount_begin', 'asc')
                        ->get();

                    if ($levels->isEmpty()) {
                        return $this->failInter('Configuration de commission invalide');
                    }

                    $min = $levels->first()->amount_begin;
                    $max = $levels->last()->amount_end;

                    if ($amountCfa < $min) {
                        return $this->failInter("Montant minimum : " . number_format($min, 2, ',', ' ') . ' CFA');
                    }

                    if ($amountCfa > $max) {
                        return $this->failInter("Montant maximum : " . number_format($max, 2, ',', ' ') . ' CFA');
                    }

                    /* ===============================
                            Recherche du bon niveau
                            ================================ */

                    $level = $levels->first(
                        fn($l) =>
                        $amountCfa >= $l->amount_begin && $amountCfa <= $l->amount_end
                    );

                    if (!$level) {
                        return $this->failInter('Aucun niveau de commission trouvé');
                    }

                    /* ===============================
                            Vérification solde
                        ================================ */

                    if ($amountCfa > sabdy()->wallet->amount) {
                        return $this->failInter('Solde insuffisant');
                    }

                    /* ===============================
                        Upload image
                        ================================ */

                    $savedPath = null;
                    $savedPathFrontend = null;
                    $savedPathBackend = null;

                    $customDirectory = public_path('bank_sends');

                    if ($this->image != null) {
                        $fileInfo = $this->moveUploadedFileToCustomDirectory(
                            $this->image,
                            $customDirectory,
                            false // Ne pas garder dans storage
                        );
                        $savedPath = 'bank_sends/' . $fileInfo['filename'];
                    }

                    if ($this->image_frontend != null) {
                        $fileInfoFrontend = $this->moveUploadedFileToCustomDirectory(
                            $this->image_frontend,
                            $customDirectory,
                            false // Ne pas garder dans storage
                        );
                        $savedPathFrontend = 'bank_sends/' . $fileInfoFrontend['filename'];
                    }


                    if ($this->image_backend  != null) {
                        $fileInfoBackend = $this->moveUploadedFileToCustomDirectory(
                            $this->image_backend,
                            $customDirectory,
                            false // Ne pas garder dans storage
                        );
                        $savedPathBackend = 'bank_sends/' . $fileInfoBackend['filename'];
                    }

                    /* ===============================
                        Création transaction
                        ================================ */

                    $walletInter = app(TransfertServiceBackend::class)
                        ->createMobileWalletBankSendWith(
                            $amountUser,
                            $level,
                            $country_robus,
                            $this->provider_inter_out_civ,
                            $this->phone_inter_out_civ,
                            $this->name_custumer,
                            $this->num_piece_custumer,
                            $this->date_piece,
                            $this->name,
                            $this->name_sender,
                            $this->account_number,
                            $this->phone_inter_expe,
                            $this->swit_code,
                            $savedPath,
                            $savedPathFrontend,
                            $savedPathBackend,
                            $this->piece_type,
                        );

                    if (!$walletInter) {
                        return $this->failInter('Erreur de création de la transaction');
                    }

                    /* ===============================
                        Montant reçu (CFA)
                        ================================ */

                    $valeur_received = $amountCfa - (float) $level->amount;

                    if (!app(TransfertServiceBackend::class)
                        ->updateAmountReceived($walletInter, $country_robus, $valeur_received)) {
                        return $this->failInter('Erreur de mise à jour du montant reçu');
                    }

                    /* ===============================
                        Commission + notifications
                        ================================ */

                    if (!$this->processCommissionBankSend($amountUser, $level, $country_robus, $walletInter, $admin_civ)) {
                        return $this->failInter('Erreur lors du traitement des commissions');
                    }

                    app(NotificationServiceBackend::class)->sendNotificationRobus($walletInter);

                    if (sabdy()->email) {
                        (new MailController())->newPaiementInter($walletInter);
                    }

                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                } else {
                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('error', 'Veuillez selectionnez le pays de destination SVP  !');
                }
            } elseif (in_array($this->provider_inter_out_civ, [53, 54, 52])) {
                // Paiement invoice
                if ($this->validatePhone($this->phone_inter_out_civ)) {

                    if ($percent_robus != null) {

                        $valeur_max_send  =  $country_robus->amount_max_send;

                        if ($this->validateAmountSendLimit($reel_amount, $valeur_max_send)) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount($reel_amount)) {


                                    if ($this->provider_inter_out_civ != 52) {

                                        $valeur_convertion_amount =   app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($percent_robus->amount_auther);

                                        $amount_receved = (float) $reel_amount - (float) $valeur_convertion_amount;

                                        $valeur_convertion =   $reel_amount;

                                        $amount_valeur = $reel_amount;

                                        $amount_commission = $percent_robus->amount_auther;

                                        $convertion_amount_pay =  $amount_valeur;
                                    } else {

                                        $amount_valeur = $offer->amount + $percent_robus->amount_canal;

                                        $valeur_convertion =   app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($offer->amount);

                                        $amount_receved = $valeur_convertion;

                                        $amount_commission = $percent_robus->amount_canal;

                                        $convertion_amount_pay =   app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($amount_valeur);
                                    }

                                    $valeur_convertion_comi =   app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($amount_commission);


                                    $valeur_convertion_amount_receved =   app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($amount_receved);


                                    if ($this->validateAmount()) {


                                        $walletPayment = app(TransfertServiceBackend::class)->createRobusWalletTransactionPayment(
                                            $valeur_convertion,
                                            $percent_robus,
                                            $country_robus,
                                            $this->provider_inter_out_civ,
                                            $this->paiement_id_invoice ?? $this->paiement_id_invoice_pre,
                                            $this->number_decoder,
                                            $this->pre_phone_invoice,
                                            $valeur_convertion_comi,
                                            $this->paiement_num_contrat,
                                            $this->paiementChoix_phone,
                                            $this->facture_choix_type,
                                            $offer,
                                            $valeur_convertion_amount_receved,
                                            $this->paiement_name_cie ??  $this->paiement_name_sodecie,
                                        );

                                        if ($this->provider_inter_out_civ != 52) {

                                            if ($walletPayment != null) {

                                                if ($this->updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $convertion_amount_pay)) {
                                                    app(NotificationServiceBackend::class)->sendNotificationRobus($walletPayment);

                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                                }
                                            } else {
                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('error', 'Erreur de transaction sur le paiement ');
                                            }
                                        } else {

                                            if ($walletPayment != null) {

                                                if ($this->updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $convertion_amount_pay)) {
                                                    app(NotificationServiceBackend::class)->sendNotificationRobus($walletPayment);
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                                }
                                            } else {
                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('error', 'Erreur de transaction sur le paiement ');
                                            }
                                        }

                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletPayment);
                                        }
                                    } else {
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('info', 'Veuillez verifier votre solde');
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
            } elseif (in_array($this->provider_inter_out_civ, [56, 57, 58])) {
                // Paiement phone credit
                if ($this->validatePhone($this->phone_inter_out_civ)) {

                    if ($percent_robus != null) {

                        $valeur_max_send  =  $country_robus->amount_max_send;

                        if ($this->validateAmountSendLimit($reel_amount, $valeur_max_send)) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount($reel_amount)) {


                                    $amount_valeur = $reel_amount + ($percent_robus->percent * $reel_amount);

                                    $amount_commission = $percent_robus->percent * $reel_amount;

                                    $amount_receved = $reel_amount;

                                    $valeur_convertion_comi =   app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($amount_commission);

                                    $valeur_convertion_amount_receved =   app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($amount_receved);

                                    $walletPhoneCredit = app(TransfertServiceBackend::class)->createRobusWalletTransactionCreditPayment(
                                        $amount_receved,
                                        $percent_robus,
                                        $country_robus,
                                        $this->provider_inter_out_civ,
                                        $this->name_custumer,
                                        $amount_commission,
                                        $this->phone_inter_out_civ,
                                        $valeur_convertion_comi,
                                        $valeur_convertion_amount_receved
                                    );

                                    if ($walletPhoneCredit != null) {

                                        if ($this->updateamountPhoneCreditReceveid($admin_civ, $percent_robus, $walletPhoneCredit, $reel_amount)) {
                                            app(NotificationServiceBackend::class)->sendNotificationRobus($walletPhoneCredit);
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                        }
                                    } else {

                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('error', 'Erreur de creation verifier les informations de saisie !');
                                    }

                                    if (sabdy()->email != null) {
                                        $notify = new MailController();
                                        $notify->newPaiementInter($walletPhoneCredit);
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
            } elseif (in_array($this->provider_inter_out_civ, [59, 60])) {
                // Bank reload transactions
                if ($this->validatePhone($this->num_accompte)) {

                    if ($percent_robus != null) {

                        $valeur_max_send  =  $country_robus->amount_max_send;

                        if ($this->validateAmountSendLimit($reel_amount, $valeur_max_send)) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount($reel_amount)) {

                                    $valeur_end =   $percent_robus->levelBanks->last();

                                    $valeur_convertion_max =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($valeur_end->amount_end);

                                    if ($valeur_convertion_max  <  $reel_amount) {

                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('info', 'Vous avez depasser le montant maximun !! ');
                                    }

                                    $percent_robus =  $this->percentRobus->getRobusPercentageRelaodCard();
                                    $valeur_first = $percent_robus->levelBanks->first();
                                    $valeur_end = $percent_robus->levelBanks->last();
                                    $all_valeurs = $percent_robus->levelBanks;

                                    $amount_request =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($reel_amount);

                                    foreach ($all_valeurs as $amount_user) {

                                        if ($amount_request >= $amount_user->amount_begin && $amount_request <= $amount_user->amount_end) {

                                            $convertion_amount_begin =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($valeur_first->amount_begin);

                                            $convertion_amount_end =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($valeur_end->amount_end);

                                            // Convertion valeurs
                                            if ($convertion_amount_begin > $reel_amount) {
                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('info', 'Montant minimun est de  . ' . $valeur_first->amount_begin);
                                            } elseif ($convertion_amount_end < $reel_amount) {
                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('info', 'Montant maximum est de  . ' . $valeur_first->amount_end);
                                            } else {

                                                if ($percent_robus !== null) {

                                                    if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                                        $percent_distr_cfa =   $amount_user->percent * $amount_request;

                                                        $amount_valeur_send = (float)$amount_request +  (float)$percent_distr_cfa;

                                                        $amount_valeur_convertion_send =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($amount_request);


                                                        if ($this->validateAmount($amount_valeur_convertion_send)) {

                                                            $comission_distributer = $amount_user->comission_distributer * $amount_request;
                                                            $comission_compagnie = $amount_user->percent_companie * $amount_request;

                                                            $amount_valeur =  $amount_request;

                                                            $amount_receved_valeur = $amount_request - $percent_distr_cfa;

                                                            $amount_valeur_convertion =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($amount_valeur);

                                                            $amount_commission_convertion =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($comission_distributer);

                                                            $amount_commission_compagnie =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($comission_compagnie);

                                                            $amount_commission_convertion_percent =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($percent_distr_cfa);

                                                            $valeur_convertion_amount_receved =   $amount_receved_valeur;


                                                            //valeur commissions
                                                            $walletVirement =  app(TransfertServiceBackend::class)->createRobusWalletTransactionVirement($amount_valeur_convertion, $this->account_number, $country_robus, $this->provider_inter_out_civ, $amount_commission_convertion_percent, $this->phone, $this->firstname, $this->lastname, $valeur_convertion_amount_receved);

                                                            if ($walletVirement != null) {

                                                                if ($this->updateamountVirementInterReceveid($admin_civ, $amount_commission_convertion, $walletVirement, $amount_valeur_convertion_send, $percent_distr_cfa, $amount_commission_compagnie)) {
                                                                    app(NotificationServiceBackend::class)->sendNotificationRobus($walletVirement);
                                                                    if (sabdy()->email != null) {
                                                                        $notify = new MailController();
                                                                        $notify->newPaiementInter($walletVirement);
                                                                    }
                                                                    $this->resetInterField();
                                                                    $this->emit('modal-hide-inters');
                                                                    $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                                                }
                                                            } else {
                                                                $this->resetInterField();
                                                                $this->emit('modal-hide-inters');
                                                                $this->emit('error', 'Erreur de transaction sur le paiement ');
                                                            }
                                                        } else {
                                                            $this->resetInterField();
                                                            $this->emit('modal-hide-inters');
                                                            $this->emit('error', 'Verifier votre Solde SVP  !');
                                                        }
                                                    } else {
                                                        return json_message_response(false, "Votre plafond quotidien est atteint !", 333);
                                                    }
                                                } else {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Niveau de pourcentage introuvable  !');
                                                }
                                            }
                                        }
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
            } elseif (in_array($this->provider_inter_out_civ, [61, 62, 63, 64, 65, 67, 69])) {
                // Rechargement de carte
                if ($this->validatePhone($this->phone_inter_out_civ)) {

                    if ($percent_robus != null) {

                        $valeur_max_send  =  $country_robus->amount_max_send;

                        if ($this->validateAmountSendLimit($reel_amount, $valeur_max_send)) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount($reel_amount)) {


                                    $valeur_first = $percent_robus->levelDeposits->where('is_inter_bank', true)->first();
                                    $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', true)->last();
                                    $all_valeurs = $percent_robus->levelDeposits->where('is_inter_bank', true);

                                    $convertion_amount_begin =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($valeur_first->amount_begin);

                                    $convertion_amount_end =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($valeur_end->amount_end);

                                    $amount_valeur =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($reel_amount);

                                    if ($percent_robus != null) {

                                        if ($convertion_amount_begin > $reel_amount) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('info', 'Montant minimun est de .  ' . $valeur_first->amount_begin);
                                        } elseif ($convertion_amount_end < $reel_amount) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('info', 'Montant maximum est de .  ' . $valeur_first->amount_end);
                                        } else {

                                            $amount_request =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($reel_amount);


                                            foreach ($all_valeurs as $amount_user) {

                                                if ($amount_request >= $amount_user->amount_begin && $amount_request <= $amount_user->amount_end) {


                                                    if ($percent_robus !== null) {

                                                        $amount_receved_valeur =  $amount_request;


                                                        if ($amount_user->amount == null) {

                                                            $percent_valeur = $amount_user->percent * $amount_request;

                                                            $amount_valeur = $amount_request;

                                                            $comission_distributer = $amount_user->comission_distributer * $amount_request;
                                                            $comission_compagnie = $amount_user->percent_companie * $amount_request;

                                                            $amount_receved_valeur = $amount_request - $percent_valeur;
                                                        } else {

                                                            $percent_valeur = $amount_user->amount;

                                                            $amount_valeur = (float)$amount_user->amount + (float)$amount_request;

                                                            $comission_distributer = $amount_user->comission_distributer;
                                                            $comission_compagnie = $amount_user->percent_companie;

                                                            $amount_receved_valeur = $amount_request - $amount_user->amount;
                                                        }

                                                        $amount_valeur_convertion_send =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($amount_request);

                                                        $amount_valeur_convertion =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($amount_valeur);

                                                        $amount_commission_convertion =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($percent_valeur);

                                                        $amount_commission_convertion_under =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($comission_distributer);

                                                        $amount_commission_convertion_compagnie =  app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($comission_compagnie);

                                                        $walletReloadCard =  app(TransfertServiceBackend::class)->createRobusWalletTransactionOrderCard($reel_amount, $this->account_number, $country_robus, $this->provider, $amount_commission_convertion,  $this->name, $amount_receved_valeur);

                                                        if ($walletReloadCard != null) {

                                                            if ($this->updateamountVirementInterReceveid($admin_civ, $amount_commission_convertion_under, $walletReloadCard, $amount_valeur_convertion_send, $percent_valeur, $amount_commission_convertion_compagnie)) {
                                                                app(NotificationServiceBackend::class)->sendNotificationRobus($walletReloadCard);
                                                                if (sabdy()->email != null) {
                                                                    $notify = new MailController();
                                                                    $notify->newPaiementInter($walletReloadCard);
                                                                }
                                                                $this->resetInterField();
                                                                $this->emit('modal-hide-inters');
                                                                $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                                            }
                                                        } else {

                                                            $this->resetInterField();
                                                            $this->emit('modal-hide-inters');
                                                            $this->emit('error', 'Erreur de creation des  !');
                                                        }
                                                    } else {

                                                        $this->resetInterField();
                                                        $this->emit('modal-hide-inters');
                                                        $this->emit('info', 'Niveau de pourcentage introuvable !!');
                                                    }
                                                }
                                            }
                                        }
                                    } else {

                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('info', 'Erreur veuillez veirifier chez l\'adminstrateur !!');
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
            } elseif (in_array($this->provider_inter_out_civ, [129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141])) {
                // Versement transactions
                if ($this->validatePhone($this->num_accompte)) {

                    if ($percent_robus != null) {

                        $valeur_max_send  =  $country_robus->amount_max_send;

                        if ($this->validateAmountSendLimit($reel_amount, $valeur_max_send)) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount($reel_amount)) {


                                    if ($percent_robus != null) {

                                        $amount_min = 20700;

                                        $convertion_amount_min =   app(ConvertionPercentBackend::class)->convertAdminCommissionPayment($amount_min);

                                        if ($reel_amount < $convertion_amount_min) {

                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('info', 'Le montant minimum est de ' . $convertion_amount_min  .  ' ' . robus()->country->currencie->code .  'et' . 'maximun ' . $convertion_amount_min . ' ' . robus()->country->currencie->code);
                                        }

                                        $percent_amount = $percent_robus->percent * $reel_amount;
                                        $valeur_received = $reel_amount -  $percent_amount;
                                        $valeur_amount = $reel_amount;

                                        $walletVersement = app(TransfertServiceBackend::class)->createWithVersementWalletInter($reel_amount, $percent_robus, $country_robus, $this->provider_inter_out_civ, 9, $this->num_authers, $this->guichet_code, $this->bank_code, $this->num_accompte, $this->name);

                                        if ($walletVersement != null) {

                                            if ($this->updateAmountReceived($walletVersement, $country_robus, $valeur_received)) {

                                                if ($this->processCommissionWithVersementMobiles($valeur_amount, $percent_robus, $country_robus, $walletVersement, $admin_civ)) {
                                                    app(NotificationServiceBackend::class)->sendNotificationRobus($walletVersement);
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('info', 'Votre demande de transaction a bien été prise en compte.');
                                                } else {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Error de transferts du montant !');
                                                }
                                            } else {

                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('error', 'Pas de poucentage defini dans cette zone !');

                                                return   json_message_response(false, "Error de demande transferts  !", 300);
                                            }

                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletVersement);
                                            }
                                        } else {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('error', 'Pas de poucentage defini dans cette zone !');
                                            return   json_message_response(false, "Error de demande transferts  !", 300);
                                        }
                                    } else {

                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('error', 'Pas de poucentage defini dans cette zone !');
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
        // } catch (\Exception $exception) {
        //     $this->resetInterField();
        //     $this->emit('modal-hide-inters');
        //     $this->emit('error', 'Error de message demande a l\'admin !');
        // }
    }



    private function updateAmountReceived($walletInter, $country_robus, $valeur_received)
    {

        if (sabdy()->country->currencie->value > $country_robus->currencie->value) {


            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) sabdy()->country->currencie->value;

            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        } elseif (sabdy()->country->currencie->value == $country_robus->currencie->value) {

            $commission_transaction_val_inter_convertion_send = (float)   $valeur_received;
        } elseif (sabdy()->country->currencie->value < $country_robus->currencie->value) {

            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) sabdy()->country->currencie->value;

            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        }

        if ($commission_transaction_val_inter_convertion_send) {

            $walletInter->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            return true;
        } else {
            return false;
        }
    }

    private function moveUploadedFileToCustomDirectory($uploadedFile, $customDirectory, $keepInStorage = false)
    {
        // S'assurer que le dossier existe
        if (!file_exists($customDirectory)) {
            // Créer le dossier avec les bonnes permissions
            $oldMask = umask(0);
            @mkdir($customDirectory, 0775, true);
            umask($oldMask);
        }

        // Vérifier que le dossier est accessible en écriture
        if (!is_writable($customDirectory)) {
            @chmod($customDirectory, 0775);

            // Si toujours pas accessible, utiliser storage à la place
            if (!is_writable($customDirectory)) {
                return $this->saveToStorageOnly($uploadedFile);
            }
        }

        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        // Nettoyer le nom de fichier
        $cleanName = preg_replace('/[^\w\s.-]/', '', $originalName);
        $cleanName = str_replace(['’', "'", '"', '`'], '', $cleanName); // Enlever guillemets
        $filename = pathinfo($cleanName, PATHINFO_FILENAME) . '_' .
            date('YmdHis') . '_' . uniqid() . '.' . $extension;
        // Remplacer les espaces par des underscores
        $filename = str_replace(' ', '_', $filename);
        // Chemin complet
        $customPath = $customDirectory . '/' . $filename;
        // Déplacer le fichier avec gestion d'erreur
        try {
            $uploadedFile->move($customDirectory, $filename);
        } catch (\Exception $e) {
            // Si échec, utiliser copy() à la place
            $tempPath = $uploadedFile->getRealPath();
            if (copy($tempPath, $customPath)) {
                unlink($tempPath);
            } else {
                throw new \Exception("Impossible de déplacer le fichier: " . $e->getMessage());
            }
        }

        $result = [
            'custom_path' => $customPath,
            'filename' => $filename,
            'storage_path' => null
        ];

        // Optionnel : garder une copie dans le storage Laravel
        if ($keepInStorage) {
            $userFolder = 'bank_sends/' . sabdy()->id;

            // Copier depuis le chemin public vers storage
            $storagePath = $userFolder . '/' . $filename;
            Storage::disk('public')->put($storagePath, file_get_contents($customPath));

            $result['storage_path'] = $storagePath;
        }

        return $result;
    }

    private function saveToStorageOnly($uploadedFile)
    {
        // Sauvegarder uniquement dans le storage Laravel
        $filename = date('YmdHis') . '_' . uniqid() . '.' .
            $uploadedFile->getClientOriginalExtension();

        $path = $uploadedFile->storeAs('bank_sends', $filename, 'public');

        return [
            'custom_path' => storage_path('app/public/' . $path),
            'filename' => $filename,
            'storage_path' => $path
        ];
    }
    private function failInter(string $message)
    {
        $this->resetInterField();
        $this->emit('modal-hide-inters');
        $this->emit('error', $message);
        return;
    }

    public function render()
    {

        $transaction = WalletTransaction::query();

        $offers = Offer::whereStatus(true)
            ->latest()
            ->orderBy('name', 'asc')
            ->get();

        $walletUser = sabdy();
        $amount_received_canal = null;
        $commission_paiement_invoice_canal_val_nat = null;
        $commission_paiement_invoice_val_nat_pay = null;
        $commission_paiement_invoice_val_nat_pay_frais = null;
        $commission_paiement_invoice_val_nat_receive = null;
        $commission_paiement_invoice_canal_val_inter = null;

        if ($this->offer_paiement != '') {


            if (sabdy()->country->id != 107) {

                $commission_invoice = PaymentCost::whereId(2)->first();

                foreach ($offers->take(1) as $offer) {

                    $offer_amount = $offer->whereId($this->offer_paiement)->first();

                    $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                    $valeur_offer_receveid =  $offer_amount->amount;

                    $convertion_offers = app(ConvertionPercentBackend::class)->convertAdminCommissionInvoice($valeur_offer);

                    $convertion_offer_receveid = app(ConvertionPercentBackend::class)->convertAdminCommissionInvoice($valeur_offer_receveid);

                    if (sabdy()->is_under_distributer == true) {

                        $commission_reload_inter_val_inter_percent_pay = app(ConvertionPercentBackend::class)->convertAdminCommissionInvoice($commission_invoice->comission_canal);

                        $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                        $commission_paiement_invoice_canal_val_inter = app(ConvertionPercentBackend::class)->convertAdminCommissionInvoice($commission_invoice->amount_canal);
                    } else {

                        $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;
                    }


                    $amount_received_canal =  $convertion_offer_receveid;
                }
            }
        }

        if ($this->transaction_inter_amount_money != '') {

            $commission_invoice = PaymentCost::whereId(2)->first();

            if ($walletUser->wallet->sidUser->is_under_distributer == 1 || $walletUser->wallet->sidUser->is_com_zone == 1) {

                $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->comission_auther;

                $commission_paiement_invoice_val_nat_pay_frais =  (float) $commission_invoice->amount_auther;

                $commission_paiement_invoice_val_nat_receive =  (float) $this->transaction_inter_amount_money;
            } else {

                $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->amount_auther;

                $commission_paiement_invoice_val_nat_receive =  (float) $this->transaction_inter_amount_money;

                $commission_paiement_invoice_val_nat_pay_frais =  (float) $commission_invoice->amount_auther;
            }
        }

        // Invoice canal national

        if ($this->offer_paiement != '') {


            if (sabdy()->country->id == 107) {

                $commission_invoice = PaymentCost::whereId(2)->first();

                foreach ($offers->take(1) as $offer) {

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
            'showRemonterBankButton' => $this->showRemonterBankButton,
            'showTransacInterButton' => $this->showTransacInterButton,
            'offers_canal' => $offers,
            'amount_received_canal' => $amount_received_canal,
            'amount_invoice' => PaymentCost::where('status', true)->first(),
            'amount_paiement_invoice_auther_pay' => $commission_paiement_invoice_val_nat_pay,
            'amount_paiement_invoice_auther_receive' => $commission_paiement_invoice_val_nat_receive,
            'amount_paiement_invoice_canal' => $commission_paiement_invoice_canal_val_nat,
            'amount_paiement_invoice_canal_inter' => $commission_paiement_invoice_canal_val_inter,
            'amount_paiemet_frais_receive' => $commission_paiement_invoice_val_nat_pay_frais,
            'user_auth' => sabdy()

        ]);
    }


    private function processCommissionWithVersementMobiles($amount, $percent_robus, $country_robus, $walletInter, $admin_civ)
    {


        $wallet = sabdy()->wallet;
        $account_accepted = robus()->is_accepted_companie ?? false;

        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $amount),
        ];

        $wallet->update($updatedValues);
        $amount_bank =  $percent_robus->percent_bank_custumer * $amount;
        $valuer_deposit = $percent_robus->percent_merchands *  $amount;
        $percent_valeur_compagnie = $percent_robus->percent_companie *  $amount;
        $bank_valeur_convertion =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($amount_bank);

        // Commissions Inters 
        $valeur_amount_admin  = $percent_robus->percent_admin * $amount;
        $admin_valeur_convertion =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valeur_amount_admin);
        $company_valeur_convertion =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($percent_valeur_compagnie);

        $cumul_amount =  (float) $admin_valeur_convertion + (float) $company_valeur_convertion;

        if ($this->showRemonterInterButton) {

            $walletInter->update([
                'commissions' => DB::raw('commissions+'  . $valuer_deposit),
            ]);

            $account_accepted = sabdy()->is_accepted_companie ?? false;
            $deposit_valeur_convertion =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valuer_deposit);

            if ($account_accepted && $this->showRemonterInterCButton) {
                $walletInter->increment('commission_companie', $percent_valeur_compagnie);
                app(CommissionServiceBackend::class)->distributeAdminAndVersementCommissionAcceptedCompanies($admin_civ, 'wallet_inter_id', $walletInter->id, $admin_valeur_convertion, $company_valeur_convertion, $deposit_valeur_convertion, $bank_valeur_convertion);
            } else {
                app(CommissionServiceBackend::class)->distributeAdminAndBankCommissionAccepteds($admin_civ, 'wallet_inter_id', $walletInter->id, $cumul_amount, $deposit_valeur_convertion, $bank_valeur_convertion);
            }
        } else {
            $valeur_percent_admin  = $percent_robus->percent * $amount;
            $admin_valeur_convertion =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valeur_percent_admin);
            app(CommissionServiceBackend::class)->distributeAdminAndBankCommissions($admin_civ, 'wallet_inter_id', $walletInter->id, $admin_valeur_convertion,  $bank_valeur_convertion);
        }

        return true;
    }

    private function updateamountVirementInterReceveid($admin_civ, $amount_user, $walletBank, $amount_valeur, $percent_valeur, $amount_commission_compagnie)
    {

        $account_accepted = sabdy()->is_accepted_companie ?? false;
        $amount_solde = sabdy()->wallet->amount - $amount_valeur;

        $walletBank->update([
            'amount_solde' => $amount_solde,
        ]);

        sabdy()->wallet->update([
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount-'  .  $amount_valeur),
        ]);


        $valuer_deposit = $amount_user;

        if ($this->showRemonterInterButton) {

            $amountCompanie =  $amount_commission_compagnie;
            $totalAmount =  (float) $percent_valeur  - ((float)$valuer_deposit + $amountCompanie);

            $walletBank->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);

            $admin_valeur_convertion = app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valuer_deposit);
            $admin_cumul_convertion = app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($totalAmount);
            $valeur_amount_admin =  (float) $percent_valeur  -  (float)$admin_valeur_convertion;
            $account_accepted = sabdy()->is_accepted_companie ?? false;

            if ($account_accepted && $this->showRemonterInterCButton) {

                $compagnie_convertion = app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($amountCompanie);

                $walletBank->increment('commission_companie', $amountCompanie);

                app(CommissionServiceBackend::class)->distributeAdminCommissionIntersAcceptedCompanies($admin_civ, 'wallet_inter_id', $walletBank->id, $admin_cumul_convertion, $compagnie_convertion, $admin_valeur_convertion);
            } else {
                app(CommissionServiceBackend::class)->distributeAdminNoCommissionInterCompagnies($admin_civ, 'wallet_inter_id', $walletBank->id, $valeur_amount_admin, $admin_valeur_convertion);
            }
        } else {

            $admin_valeur_convertion = $percent_valeur;
            app(CommissionServiceBackend::class)->distributeAdminPaiementCommissions($admin_civ, 'wallet_inter_id', $walletBank->id, $admin_valeur_convertion);
        }
        return true;
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

    private function updateamountPhoneCreditReceveid($admin_civ, $percent_robus, $walletPhone, $amount)
    {

        $amount_commission = $amount + ($percent_robus->percent * $amount);
        $amount_solde = sabdy()->wallet->amount - $amount_commission;

        $walletPhone->update([
            'amount_solde' => $amount_solde,
        ]);

        sabdy()->wallet->update([
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $valuer_deposit = (float) $percent_robus->percent_merchands * $amount;
        $valuer_companie = $percent_robus->percent_companie * $amount;

        $commission_convertion =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valuer_deposit);
        $commission_bank_convertion =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valuer_companie);

        if ($this->showRemonterInterButton) {

            $walletPhone->increment('commissions', $valuer_deposit);

            $account_accepted = sabdy()->is_accepted_companie ?? false;

            if ($account_accepted && $this->showRemonterInterCButton) {
                $walletPhone->increment('commission_companie', $valuer_companie);
                app(CommissionServiceBackend::class)->distributeUnderCommissionAcceptedCompanies($admin_civ,  'wallet_inter_id',  $walletPhone->id, $commission_bank_convertion, $commission_convertion);
            } else {
                app(CommissionServiceBackend::class)->distributeAdminNoCommissionInterCompagnies($admin_civ,  'wallet_inter_id',  $walletPhone->id, $commission_bank_convertion, $commission_convertion);
            }
        }
        return true;
    }

    private function updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_solde = robus()->wallet->amount - $amount;

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);

        $amount_commission =  $amount;

        sabdy()->wallet->update([
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);


        $valuer_companie = $percent_robus->percent_companie;
        $valuer_deposit_amount =  $percent_robus->comission_canal;
        $valuer_deposit =  app(ConvertionPercentBackend::class)->convertAdminCommissionInvoice($valuer_deposit_amount);
        $amountCompConvert =  app(ConvertionPercentBackend::class)->convertAdminCommissionInvoice($valuer_companie);

        $admin_compagnie_valeur =  $percent_robus->amount_canal -  ($percent_robus->comission_canal + $valuer_companie);
        $admin_no_compagnie =  $percent_robus->amount_canal -  ($percent_robus->comission_canal + $valuer_companie);
        $banque_valeur =  0;

        if ($this->showRemonterInterButton) {

            $walletPayment->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);

            $account_accepted = sabdy()->is_accepted_companie ?? false;

            if ($account_accepted && $this->showRemonterInterCButton) {

                $compagnie_amount =  $percent_robus->percent_companie +  $percent_robus->comission_auther;

                $walletPayment->increment('commission_companie', $amountCompConvert);

                app(CommissionServiceBackend::class)->distributeAdminCommissionUnderBankAcceptedCompanies($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_compagnie_valeur, $compagnie_amount, $valuer_deposit_amount, $banque_valeur);
            } else {
                app(CommissionServiceBackend::class)->distributeAdminNoCompagniePaimentCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_no_compagnie, $valuer_deposit_amount, $banque_valeur);
            }
        } else {

            $admin_valeur =   $percent_robus->comission_canal;
            app(CommissionServiceBackend::class)->distributeAdminPaiementCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_valeur);
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

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }

    public function updatedCountryInterOutCiv($countryId)
    {
        $walletPaymentInter = $this->getProvidersByType(new Provider, $countryId, 7);
        $walletPhoneInter = $this->getProvidersByType(new Provider, $countryId, 9);
        $walletVirementBank = $this->getProvidersReloadByType(new Provider, $countryId, [10]);
        $walletReloadCardInter = $this->getProvidersReloadByType(new Provider, $countryId, [11, 21, 22]);
        $walletBankInter =  $this->getProvidersByType(new Provider, $countryId, 28);
        $walletInterUnder =  $this->getProvidersByUnderDistributerType(new Provider, $countryId, 8);

        $walletsData = []; // Initialisation du tableau

        // Conversion en objets si ce sont des tableaux
        $walletPaymentInter = $this->ensureObjects($walletPaymentInter);
        $walletPhoneInter = $this->ensureObjects($walletPhoneInter);
        $walletVirementBank = $this->ensureObjects($walletVirementBank);
        $walletReloadCardInter = $this->ensureObjects($walletReloadCardInter);
        $walletBankInter = $this->ensureObjects($walletBankInter);
        $walletInterUnder = $this->ensureObjects($walletInterUnder);

        // Structure de données catégorisée
        if ((sabdy()->is_under_distributer) && sabdy()->country->id != 107) {
            $walletsData['Transfert international'] = [
                "Paiements Factures" => !empty($walletPaymentInter) ? $walletPaymentInter : [],
                "Transfert" => !empty($walletInterUnder) ? $walletInterUnder : [],
                "Crédit téléphonique" => !empty($walletPhoneInter) ? $walletPhoneInter : [],
                "Versement bancaire" => !empty($walletVirementBank) ? $walletVirementBank : [],
                "Rechargement de carte" => !empty($walletReloadCardInter) ? $walletReloadCardInter : [],
                "Bank send" => !empty($walletBankInter) ? $walletBankInter : [],
            ];
        } elseif ((sabdy()->is_under_distributer) && sabdy()->country->id == 107) {
            $walletsData['Transfert international'] = [
                "Paiements Factures" => !empty($walletPaymentInter) ? $walletPaymentInter : [],
                "Transfert" => !empty($walletInterUnder) ? $walletInterUnder : [],
                "Crédit téléphonique" => !empty($walletPhoneInter) ? $walletPhoneInter : [],
                "Versement bancaire" => !empty($walletVirementBank) ? $walletVirementBank : [],
                "Rechargement de carte" => !empty($walletReloadCardInter) ? $walletReloadCardInter : [],
                "Bank send" => !empty($walletBankInter) ? $walletBankInter : [],
            ];
        } else {
            // Cas pour les non sous-distributeurs ou autres conditions
            $walletsData['Transfert international'] = [
                "Paiements Factures" => !empty($walletPaymentInter) ? $walletPaymentInter : [],
                "Crédit téléphonique" => !empty($walletPhoneInter) ? $walletPhoneInter : [],
                "Versement bancaire" => !empty($walletVirementBank) ? $walletVirementBank : [],
                "Rechargement de carte" => !empty($walletReloadCardInter) ? $walletReloadCardInter : [],
                "Bank send" => !empty($walletBankInter) ? $walletBankInter : [],
            ];
        }

        $this->providers = $walletsData;
    }

    /**
     * Méthode utilitaire pour s'assurer que les éléments sont des objets
     */
    private function ensureObjects($data)
    {
        if (empty($data)) {
            return [];
        }

        // Si c'est déjà une collection Eloquent
        if ($data instanceof \Illuminate\Support\Collection) {
            return $data->values()->toArray();
        }

        // Si c'est un tableau, on vérifie le type des éléments
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                // Si l'élément est un tableau, on le convertit en objet standard
                if (is_array($item)) {
                    $data[$key] = (object) $item;
                }
            }
        }

        return $data;
    }

    private function getProvidersByUnderDistributerType($query, $countryId, $providerType)
    {


        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.id', '!=', 51)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }

    private  function getProvidersReloadByType($query, $countryId, $providerTypes)
    {


        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->whereIn('providers.provider_type_id', (array) $providerTypes)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
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
            ->whereIn('providers.provider_type_id', (array) $providerType)
            ->select('providers.*');
        if ($isUnderDistributer) {
            $result->where('providers.id', '!=', 51);
        }

        return $result->orderBy('providers.name', 'asc')->get();
    }


    private function updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_solde = sabdy()->wallet->amount - $amount;
        $amount_commission =  $amount;

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);

        sabdy()->wallet->update([
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $valuer_deposit_amount =  $percent_robus->comission_auther;
        $valuer_companie = $percent_robus->percent_companie;
        $banque_valeur =  (float)  $percent_robus->bank_comission;

        $valuer_deposit =  app(ConvertionPercentBackend::class)->convertAdminCommissionInvoice($valuer_deposit_amount);
        $amountCompConvert =  app(ConvertionPercentBackend::class)->convertAdminCommissionInvoice($valuer_companie);

        $admin_valeur = (float) $percent_robus->amount_auther - (float)($percent_robus->comission_auther + $banque_valeur);

        if ($this->showRemonterInterButton) {

            $walletPayment->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);

            $account_accepted = sabdy()->is_accepted_companie ?? false;

            if ($account_accepted && $this->showRemonterInterCButton) {

                $walletPayment->increment('commission_companie', $amountCompConvert);

                $valuer_admin_compagnie = (float)  $admin_valeur  - ((float) $valuer_deposit_amount + (float)$valuer_companie + (float)$banque_valeur);

                app(CommissionServiceBackend::class)->distributeAdminCommissionUnderBankAcceptedCompanies($admin_civ, 'wallet_inter_id', $walletPayment->id,  $valuer_admin_compagnie, $valuer_companie, $valuer_deposit, $banque_valeur);
            } else {

                $valor_commission = $percent_robus->amount_auther;
                $admin_sender_valeur =  (float) $valor_commission - ((float)$valuer_deposit_amount + (float)$banque_valeur);
                app(CommissionServiceBackend::class)->distributeAdminNoCompagniePaimentCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_sender_valeur, $valuer_deposit_amount, $banque_valeur);
            }
        } else {
            $banque_valeur =  (float)  $percent_robus->bank_comission;
            $admin_valeur =  (float)  $percent_robus->amount_auther -  (float) $banque_valeur;
            app(CommissionServiceBackend::class)->distributeAdminPaimentCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_valeur, $banque_valeur);
        }
        return true;
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

        if ($walletInter->provider_id == 104 || $walletInter->provider_id == 105 || $walletInter->provider_id == 106) {
            $valuer_deposit = $percent_robus->percent_merchands *  $amount;
            $valeur_amount_admin  = $percent_robus->percent * $amount;
        } else {
            $valuer_deposit = $percent_robus->percent_deposit_under_distributer *  $amount;
            $valeur_amount_admin  = $percent_robus->percent_under_distributer * $amount;
        }


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

    private function processCommissionBankSend($amount, $percent_robus, $country_robus, $walletInter, $admin_civ)
    {


        $wallet = sabdy()->wallet;

        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $amount),
        ];

        $wallet->update($updatedValues);

        $valuer_deposit = $percent_robus->percent_under;
        $valeur_amount_admin  = $percent_robus->amount;
        $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;
        $valuer_companie = $percent_robus->percent_companie;

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


            if ($this->showRemonterBankButton) {



                $walletInter->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterBankCButton) {
                    $walletInter->increment('commission_companie', $valuer_companie);

                    $totalCommission =  $percent_companie_convertion +  $percent_noUnder_convertion;

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_bank_send_id', $walletInter->id, $totalCommission, $admin_valeur_convertion);
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_bank_send_id', $walletInter->id, $admin_valeur_convertion, $valuer_deposit);
                }
            } else {


                app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_bank_send_id', $walletInter->id, $admin_valeur_convertion);
            }
        } else {

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_bank_send_id', $walletInter->id, $admin_valeur_convertion);

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
                $admin_valeur_convertion = app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valeur_percent_admin);
                $depot_valeur_convertion = app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valuer_deposit);
                $compagnie_valeur_convertion = app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valuer_companie);

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

                $admin_valeur_convertion =  app(ConvertionPercentBackend::class)->convertAdminTransactionCommission($valeur_amount_admin);

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
