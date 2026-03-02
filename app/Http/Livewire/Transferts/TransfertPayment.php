<?php

namespace App\Http\Livewire\Transferts;

use Livewire\Component;
use App\Models\Costs\PaymentCost;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer;
use App\Models\OperatorService;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Http\Services\TotalAmoutUserServiveBackend;
use App\Http\Services\NotificationServiceBackend;
use App\Http\Services\TransfertServiceBackend;
use App\Http\Services\CommissionServiceBackend;
use App\Events\CreateWalletPaiementEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class TransfertPayment extends Component
{
    public $stepPaiment = 1;
    public $name_canal_send;
    public $amount_canal_send;
    public $paiementDescription;
    public $paiementId;
    public $paiement_cost;
    public $offer_paiement;
    public $paiementStatus;
    public $provider_payment;
    
    // CIE
    public $paiement_name_cie;
    public $paiement_id_invoice;
    public $paiement_name;
    public $paiement_amount_total;
    public $paiement_amount_partiel;
    public $paiement_id_invoice_pre;
    public $pre_phone_invoice;
    public $pre_id_invoice;
    public $password_validated = '';

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

    // Choise
    public $paiementChoix_phone;
    public $paiementChoix_type;
    public $facture_choix_type;
    public $showRemonterPaiementButton = false;
    public $operators;
    public $operatorsCompagnie;
    public $operatorsTransaction;
    public $isPasswordValid = false;

    public function mount()
    {

        $this->operators = OperatorService::getOperatorsWithCommissions(sabdy()->id);
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
            'Active Paiements' => 'showRemonterPaiementButton',
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


    public function checkPassword()
    {

        if (empty($this->password_validated)) {
            // Gérer le cas où le mot de passe n'est pas valide
            return;
        }

        if (Hash::check($this->password_validated, sabdy()->password)) {
            $this->isPasswordValid = true;
        } else {
            $this->isPasswordValid = false;
        }
    }

    public function addPaiement()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_payment)->first();
        $offer = Offer::whereId($this->offer_paiement)->first();
        $som_transfers = app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();
        $valeur_ceiling = $walletUser->silver_ceiling;



        if ($provider) {

            if ($walletUser->country->code == 'CI') {

                //Provider CIE  
                $percent_robus = PaymentCost::whereId(2)->first();

                if ($this->provider_payment == 8) {

                    // Validation rules and messages
                    $validationRules = [
                        'paiement_name_cie' => 'required',
                        'paiement_amount_total' => 'required',
                        'facture_choix_type' => 'required',
                    ];
                    $validationMessages = [
                        'paiement_amount_total.required' => 'Votre montant est obligatoire',
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

                    if ($valAmount > $walletUser->wallet->amount) {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('error', 'Verifier votre montant  svp !');
                        return;
                    }

                    $this->stepPaiment = 2;
                } elseif ($this->provider_payment == 7) {

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
                        $this->resetPaiementField();
                        $this->emit('modal-hide-paiement');
                        $this->emit('warning', 'Votre montant journier est atteint !');
                        return;
                    }


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
    }

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }

    public function addPaiementValidated()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_payment)->first();
        $offer = Offer::whereId($this->offer_paiement)->first();
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminValeurProperty();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();
        $valeur_ceiling = $walletUser->silver_ceiling;
        DB::beginTransaction();

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

                        switch ($this->provider_payment) {
                            case 8: // CIE
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
                                break;

                            case 10: // SODECI
                                $validationRules = [
                                    'paiement_name_sodecie' => 'required',
                                    'paiement_num_contrat' => 'required|max:9',
                                    'paiement_amount_t_sodecie' => 'required',
                                ];
                                $validationMessages = [
                                    'paiement_amount_t_sodecie.required' => 'Votre montant est obligatoire',
                                    'paiement_name_sodecie.required' => 'Nom est obligatoire',
                                    'paiement_num_contrat.required' => 'Numero est obligatoire',
                                ];
                                break;

                            case 7: // CANAL+
                                switch ($this->paiementChoix_phone) {
                                    case 1:
                                        $validationRules = [
                                            'paiement_phone_canal' => 'required',
                                            'offer_paiement' => 'required',
                                            'paiement_name' => 'required',
                                        ];
                                        $validationMessages = [
                                            'offer_paiement.required' => 'Offre est obligatoire',
                                            'paiement_phone_canal.required' => 'Numero est obligatoire',
                                            'paiement_name.required' => 'Numero est obligatoire',
                                        ];
                                        break;

                                    case 2:
                                        $validationRules = [
                                            'paiement_decoder_canal' => 'required',
                                            'offer_paiement' => 'required',
                                            'paiement_name' => 'required',
                                        ];
                                        $validationMessages = [
                                            'offer_paiement.required' => 'Offre est obligatoire',
                                            'paiement_decoder_canal.required' => 'Code est obligatoire',
                                            'paiement_name.required' => 'Numero est obligatoire',
                                        ];
                                        break;
                                }
                                break;

                            default:
                                $this->emit('error', 'Provider non supporté.');
                                return;
                        }

                        $this->validate($validationRules, $validationMessages);


                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                            return;
                        }

                        // ✅ Calcul montant
                        switch ($this->provider_payment) {
                            case 8: // CIE
                                $reel_amount = $this->search_amount($this->paiement_amount_total);
                                $valAmount = $reel_amount;
                                $amountReceved = $reel_amount - (float) ($percent_robus->amount_auther);

                                break;

                            case 10: // SODECI
                                $reel_amount = $this->search_amount($this->paiement_amount_t_sodecie);
                                $valAmount = $reel_amount;
                                $amountReceved = $reel_amount - (float) ($percent_robus->amount_auther);

                                break;

                            case 7: // CANAL+
                                $valAmount = $offer->amount + $percent_robus->amount_canal;
                                $reel_amount = $offer->amount;
                                $amountReceved = $offer->amount;
                                break;
                        }

                        $amount_solde = $walletUser->wallet->amount - $valAmount;

                        // ✅ Vérification solde
                        if ($valAmount > $walletUser->wallet->amount) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Verifier votre montant svp !');
                            return;
                        }


                        // ✅ Création du paiement selon provider
                        switch ($this->provider_payment) {
                            case 8:
                                $walletPayment = app(TransfertServiceBackend::class)->createNewPaiement(
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
                                    $amountReceved,
                                    $amount_solde
                                );

                                if ($walletPayment) {
                                    $commission = $walletPayment->amount;
                                    $this->updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $commission);
                                }
                                break;

                            case 10:
                                $walletPayment = app(TransfertServiceBackend::class)->createNewPaiementSodeci(
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
                                    $amountReceved,
                                    $amount_solde
                                );
                                if ($walletPayment) {
                                    $commission = $walletPayment->amount;
                                    $this->updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $commission);
                                }
                                break;

                            case 7:


                                $walletPayment = app(TransfertServiceBackend::class)->createNewPaiementCanal(
                                    $walletUser,
                                    $offer,
                                    $this->paiement_decoder_canal,
                                    $this->paiement_id_invoice,
                                    $this->paiement_num_contrat,
                                    $this->pre_phone_invoice,
                                    $this->paiement_name,
                                    $this->pre_id_invoice,
                                    $this->paiement_name_sodecie,
                                    $provider,
                                    $percent_robus,
                                    $this->paiement_phone_canal,
                                    $this->paiementChoix_phone,
                                    $this->paiementChoix_type,
                                    $this->facture_choix_type,
                                    $amountReceved,
                                    $amount_solde
                                );
                                if ($walletPayment) {
                                    $commission = $walletPayment->amount + $percent_robus->amount_canal;
                                    $this->updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $commission);
                                }
                                break;
                        }

                        if ($walletPayment) {

                            $message = "Vous avez effectué un paiement de {$walletPayment->amount}{$walletUser->wallet->currencie->code} " .
                                "par {$walletPayment->provider->name} le {$walletPayment->created_at}. " .
                                "Votre demande est en cours de validation. Robus vous remercie.";

                            app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);
                            event(new CreateWalletPaiementEvent($walletPayment));
                            app(NotificationServiceBackend::class)->notifyCreateUsers(
                                $walletUser,
                                $admins,
                                'Paiement de facture',
                                'Demande de paiement est en cours de validation',
                                'Demande de paiement de ' . $walletPayment->wallet->sidUser->firstname
                            );

                            if ($admins->count() > 0) {
                                app(NotificationServiceBackend::class)->notifyAdmins($admins);
                            }

                            if ($walletUser->email) {
                                app(NotificationServiceBackend::class)->sendMailPaiementNotification($walletPayment);
                            }

                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('success', 'Votre demande de transaction a bien été prise en compte.');
                        } else {

                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('info', 'Erreur de la validation des informations !!!!!.');
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
            DB::commit();
        } catch (\Exception $exception) {
            $this->resetPaiementField();
            $this->emit('modal-hide-paiement');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }


    protected function updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $amount)
    {


        $valuer_deposit = 0;

        if (sabdy()->is_under_distributer) {

            $valuer_deposit =  $percent_robus->comission_auther;

            $banque_valeur =  (float)  $percent_robus->bank_comission;
            $admin_valeur =  (float)  $percent_robus->amount_auther;

            if ($this->showRemonterPaiementButton) {

                $walletPayment->update([
                    'commissions' => $valuer_deposit
                ]);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterPaiementCButton) {
                    $valuer_companie = $percent_robus->percent_companie;
                    $walletPayment->increment('commission_companie', $valuer_companie);

                    $valuer_admin_compagnie = (float)  $admin_valeur  - ((float) $valuer_deposit + (float)$valuer_companie + (float)$banque_valeur);

                    app(CommissionServiceBackend::class)->distributeAdminCommissionUnderBankAcceptedCompanies($admin_civ, 'wallet_payment_id', $walletPayment->id, $valuer_admin_compagnie, $valuer_companie, $valuer_deposit, $banque_valeur);
                } else {

                    $valor_commission = $percent_robus->amount_auther;
                    $banque_valeur =   (float)$percent_robus->bank_comission;
                    $valuer_deposit = (float)$percent_robus->comission_auther;
                    $admin_valeur =  (float) $valor_commission - ((float)$valuer_deposit + (float)$banque_valeur);
                    app(CommissionServiceBackend::class)->distributeAdminNoCompagniePaimentCommissions($admin_civ, 'wallet_payment_id', $walletPayment->id, $admin_valeur, $valuer_deposit, $banque_valeur);
                }
            } else {

                if ($walletPayment->provider_id == 7) {
                    $admin_valeur =   $percent_robus->comission_canal;
                    app(CommissionServiceBackend::class)->distributeAdminPaymentCommissions($admin_civ, 'wallet_payment_id', $walletPayment->id, $admin_valeur);
                } else {

                    $banque_valeur =  (float)  $percent_robus->bank_comission;
                    $admin_valeur =  (float)  $percent_robus->amount_auther -  (float) $banque_valeur;
                    app(CommissionServiceBackend::class)->distributeAdminPaimentCommissions($admin_civ, 'wallet_payment_id', $walletPayment->id, $admin_valeur, $banque_valeur);
                }
            }
        }

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount);

        return true;
    }

    protected function updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $walletUser, $amount)
    {


        $valuer_deposit = 0;

        if (sabdy()->is_under_distributer) {

            $adminAmount =  $percent_robus->amount_canal -  $percent_robus->comission_canal;

            if ($this->showRemonterPaiementButton) {

                $valor_commission =  $percent_robus->comission_canal;
                $banque_valeur =  0;
                $valuer_deposit = 0;

                $walletPayment->update([
                    'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                ]);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted) {

                    $valuer_companie = (float) $valor_commission * (float)$percent_robus->percent_companie;
                    $walletPayment->increment('commission_companie', $valuer_companie);
                    $valuer_admin_compagnie = (float)  $percent_robus->comission_canal  - (float)$valuer_companie;

                    app(CommissionServiceBackend::class)->distributeAdminCommissionUnderBankAcceptedCompanies($admin_civ, 'wallet_payment_id', $walletPayment->id, $valuer_admin_compagnie, $valuer_companie, $valuer_deposit, $banque_valeur);
                } else {
                     $banque_valeur =  0;
                    $valuer_deposit = 0;
                    $admin_valeur =   $percent_robus->comission_canal;
                    app(CommissionServiceBackend::class)->distributeAdminNoCompagniePaimentCommissions($admin_civ, 'wallet_payment_id', $walletPayment->id, $admin_valeur, $valuer_deposit, $banque_valeur);
                }
            } else {

                app(CommissionServiceBackend::class)->distributeAdminPaiementCommissions($admin_civ, 'wallet_payment_id', $walletPayment->id, $adminAmount);
            }
        }

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount);

        return true;
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
        $this->paiement_name = '';
        $this->paiement_amount_total = '';
        $this->paiementStatus = '';
        $this->paiementChoix_phone = null;
        $this->offer_paiement = null;
        $this->offer_paiement = null;
        $this->paiement_id_invoice_pre = '';
        $this->pre_phone_invoice = '';
        $this->pre_id_invoice = '';
        $this->stepPaiment = 1;
        $this->emit('modal-hide-paiement-validated');
    }

    public function render()
    {

        $offers = Offer::whereStatus(true)
            ->latest()
            ->orderBy('name', 'asc')
            ->get();

        $amount_received_canal = null;
        $commission_paiement_invoice_canal_val_nat = null;
        $commission_paiement_invoice_val_nat_pay = null;
        $commission_paiement_invoice_val_nat_pay_frais = null;
        $commission_paiement_invoice_val_nat_receive = null;
        $commission_paiement_invoice_canal_val_inter = null;

        $walletUser = Auth::guard('web')->user();

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

        if ($this->paiement_amount_total != '') {

            $commission_invoice = PaymentCost::whereId(2)->first();

            if ($walletUser->country->code == 'CI') {

                $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->comission_auther;

                $commission_paiement_invoice_val_nat_pay_frais =  (float) $commission_invoice->amount_auther;

                $commission_paiement_invoice_val_nat_receive =  (float) $this->paiement_amount_total;
            }
        }


        if ($this->paiement_amount_t_sodecie != '') {

            $commission_invoice = PaymentCost::whereId(2)->first();

            if ($walletUser->wallet->sidUser->is_under_distributer == 1) {

                $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->comission_auther;

                $commission_paiement_invoice_val_nat_pay_frais =  (float) $commission_invoice->amount_auther;

                $commission_paiement_invoice_val_nat_receive =  (float) $this->paiement_amount_t_sodecie;
            } else {

                $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->amount_auther;

                $commission_paiement_invoice_val_nat_receive =  (float) $this->paiement_amount_t_sodecie;

                $commission_paiement_invoice_val_nat_pay_frais =  (float) $commission_invoice->amount_auther;
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
        return view(
            'livewire.transferts.transfert-payment',
            [
                'provider_paiements' => $this->PaiementTransaction,
                'showRemonterPaiementButton' => $this->showRemonterPaiementButton,
                'usersCountry' => sabdy()->wallet->currencie->code,
                'offers_canal' => $offers,
                'amount_received_canal' => $amount_received_canal,
                'amount_invoice' => PaymentCost::where('status', true)->first(),
                'amount_paiement_invoice_auther_pay' => $commission_paiement_invoice_val_nat_pay,
                'amount_paiement_invoice_auther_receive' => $commission_paiement_invoice_val_nat_receive,
                'amount_paiement_invoice_canal' => $commission_paiement_invoice_canal_val_nat,
                'amount_paiement_invoice_canal_inter' => $commission_paiement_invoice_canal_val_inter,
                'amount_paiemet_frais_receive' => $commission_paiement_invoice_val_nat_pay_frais,
                'user_auth' => $walletUser

            ]
        );
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
}
