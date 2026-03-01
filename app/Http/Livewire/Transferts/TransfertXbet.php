<?php

namespace App\Http\Livewire\Transferts;


use App\Models\OperatorService;
use App\Http\Services\CommissionServiceBackend;
use App\Http\Services\ConvertionPercentBackend;
use App\Http\Services\TotalAmoutUserServiveBackend;
use App\Http\Services\TransfertServiceBackend;
use App\Http\Services\NotificationServiceBackend;
use App\Http\Services\CommissionServicePercentBackend;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Events\CreateWalletXbetEvent;
use Livewire\Component;

class TransfertXbet extends Component
{


    public $operators;
    public $operatorsTransaction;
    public $choose_type_transaction;
    public $id_compte;
    public $id_compte_retrait;
    public $amount_xbet;
    public $full_nameXbet;
    public $code_compte;
    public $countrie;
    public $citie_xbet;
    public $area_xbet;
    public $stepXbet = 1;

    public $showTransDepotXbetButton = false;
    public $showTransRetraitXbetButton = false;

    public $showTransRemonteDepotXbetButton = false;
    public $showTransRemonteRetraitXbetButton = false;
    // Compagnies
    public $showTransRemonteDepotXbetCButton = false;
    public $showTransRemonteRetraitXbetCButton = false;
    public $operatorsCompagnie;

    public $isPasswordValid = false;
    public $password_validated = '';
    // Phone credit
    public $commission_transaction_val_nat_xbet_pay;
    public $commission_transaction_val_admin_xbet_receive;
    public $commission_transaction_val_nat_xbet_receive;
    public $commission_transaction_val_nat_xbet_com;
    public $commission_transaction_drawal;

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
            'Active Xbets' => 'showTransRemonteDepotXbetButton',
            'Active Retrait Xbet' => 'showTransRemonteRetraitXbetButton'
        ];

        $restrictionCompangieMap = [
            'Active Xbets' => 'showTransRemonteDepotXbetCButton',
            'Active Retrait Xbet' => 'showTransRemonteRetraitXbetCButton'
        ];


        $restrictionMapTransaction = [
            'TRANSFERT XBET' => 'showTransDepotXbetButton',
            'RETRAIT XBET' => 'showTransRetraitXbetButton'
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


    public function addXbetSend()
    {

        $walletUser = sabdy();
        $provider = Provider::whereId(87)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();


        try {

            if ($provider) {


                if ($this->choose_type_transaction == 1) {

                    $this->validate(
                        [
                            'amount_xbet' => 'required',
                            'full_nameXbet' => 'required',
                            'id_compte' => 'required',
                        ],
                        [

                            'amount_xbet.required' =>
                            'Votre montant est obligatoire',
                            'full_nameXbet.required' =>
                            'le nom est obligatoire',
                            'id_compte.required' =>
                            'le Id du compte est obligatoire',
                        ]
                    );

                    $this->amount_xbet = htmlspecialchars($this->amount_xbet, ENT_QUOTES, 'UTF-8');

                    $this->stepXbet = 2;
                } else {

                    $this->validate(
                        [
                            'code_compte' => 'required',
                            'amount_xbet' => 'required',
                            'id_compte' => 'required'
                        ],
                        [

                            'code_compte.required' =>
                            'le code du compte est obligatoire',
                            'amount_xbet.required' =>
                            'Votre montant est obligatoire',
                            'id_compte.required' =>
                            'Votre Id du compte est obligatoire',
                        ]
                    );

                    $this->amount_xbet = htmlspecialchars($this->amount_xbet, ENT_QUOTES, 'UTF-8');

                    $this->stepXbet = 2;
                }


                if ($som_transfers >= $valeur_ceiling) {

                    $this->resetXbetTransactionField();
                    $this->emit('modal-hide-xbet');
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                    return;
                }

                $reel_amount = $this->search_amount($this->amount_xbet);

                $val_amount = $reel_amount;

                if ($val_amount > $walletUser->wallet->amount) {
                    $this->resetXbetTransactionField();
                    $this->emit('modal-hide-xbet');
                    $this->emit(
                        'error',
                        'Verifier votre montant  svp !'
                    );
                    return;
                }

                $this->stepXbet = 2;
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetXbetTransactionField();
            $this->emit('modal-hide-xbet');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedXbet()
    {

        $walletUser = sabdy();
        $provider = Provider::whereId(87)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminValeurProperty();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();

        try {



            $this->validate([
                'password_validated' => 'required',
            ], [
                'password_validated.required' => 'Mot de passe est obligatoire',
            ]);

            if (!Hash::check($this->password_validated, sabdy()->password)) {
                $this->resetXbetTransactionField();
                $this->emit('modal-hide-xbet');
                $this->emit('error', 'Vérifiez votre mot de passe SVP !');
                return;
            }


            if ($walletUser->country->code != 'CI') {
                $this->resetXbetTransactionField();
                $this->emit('modal-hide-xbet');
                $this->emit(
                    'warning',
                    'Ce réchargement est uniquement pour la CIV !'
                );
                return;
            }


            if ($som_transfers >= $valeur_ceiling) {
                $this->resetXbetTransactionField();
                $this->emit('modal-hide-xbet');
                $this->emit('warning', 'Votre montant journier est atteint !');
                return;
            }


            $reel_amount = $this->search_amount($this->amount_xbet);
            $percent_robus =  app(CommissionServicePercentBackend::class)->getRobusPercentageOnexbet();

            $val_amount = $reel_amount;

            if ($val_amount > $walletUser->wallet->amount) {
                $this->resetXbetTransactionField();
                $this->emit('modal-hide-xbet');
                $this->emit('error', 'Vérifier votre montant SVP !');
                return;
            }


             $amount_solde =  $walletUser->wallet->amount ;

            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                if ($this->choose_type_transaction === 1) {
                    $amountReceved = $reel_amount -  ($percent_robus->percent *  $reel_amount);
                } else {
                    $amountReceved = $reel_amount -  ($percent_robus->percent_with_drawal *  $reel_amount);
                }

                $walletXbet =  app(TransfertServiceBackend::class)->createXbetTransaction(
                    $reel_amount,
                    $provider,
                    $percent_robus,
                    $amount_solde,
                    $this->choose_type_transaction,
                    $this->full_nameXbet,
                    $this->id_compte,
                    $this->code_compte,
                    $amountReceved
                );

                if ($this->choose_type_transaction == 1) {
                    $message =
                        'Vous avez effectué un dépot de ' .
                        $walletXbet->amount .
                        $walletUser->wallet->currencie->code .
                        ' ' .
                        'par ' .
                        $walletXbet->provider->name .
                        ' le ' .
                        $walletXbet->created_at .
                        ' votre demande est en cours de validation ' .
                        'Robus vous remercie';
                } else {

                    $message =
                        'Vous avez effectué un rétrait de ' .
                        $walletXbet->amount .
                        $walletUser->wallet->currencie->code .
                        ' ' .
                        'par ' .
                        $walletXbet->provider->name .
                        ' le ' .
                        $walletXbet->created_at .
                        ' votre demande est en cours de validation ' .
                        'Robus vous remercie';
                }


                app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);

                if ($walletXbet != null  && $walletXbet->status == 'PENDING') {
                    $this->updateamountXbetTrasaction($admin_civ, $percent_robus, $walletXbet,  $walletUser, $reel_amount, $this->choose_type_transaction);
                }
                event(new CreateWalletXbetEvent($walletXbet));
                app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Transaction 1XBET', 'Demande de 1XBET est en cours de validation', 'Demande de transaction 1XBET de ' . ' ' . $walletXbet->wallet->sidUser->firstname);

                if ($admins->count() > 0) {
                    app(NotificationServiceBackend::class)->notifyAdmins($admins);
                }

                $this->resetXbetTransactionField();
                $this->emit('modal-hide-xbet');
                $this->emit('success', 'Votre demande de transaction a bien été prise en compte !');
                if ($walletUser->email != null) {
                    app(NotificationServiceBackend::class)->sendMailXbetNotification($walletXbet);
                }
            } else {
                $this->resetXbetTransactionField();
                $this->emit('modal-hide-xbet');
                $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                return;
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->resetXbetTransactionField();
            $this->emit('modal-hide-xbet');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    protected function updateamountXbetTrasaction($admin_civ, $amount_user, $walletXbet, $walletUser, $amount_valeur, $choose_type_transaction)
    {


        $adminAmount = $amount_valeur + (float) ($amount_user->percent_admin * $amount_valeur);

        $val_amount_send = $amount_valeur + (float) ($amount_user->percent * $amount_valeur);

        if (sabdy()->is_under_distributer) {

            if ($choose_type_transaction ===  1) {

                $amount_commission = $val_amount_send;

                app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount_commission);

                $admin_nocomission = app(ConvertionPercentBackend::class)->convertAdminCommission($amount_user->percent * $amount_valeur);

                if ($this->showTransRemonteDval_amount_sendepotXbetButton) {

                    $amount__commission = $amount_valeur + ($amount_user->percent * $amount_valeur);
                    $amount_solde = sabdy()->wallet->amount - $amount__commission;
                    $valuer_deposit = $amount_user->percent_merchands * $amount_valeur;
                    $valuer_conversion_companie =  app(ConvertionPercentBackend::class)->convertAdminCommission($amount_user->percent_companie * $amount_valeur);
                    $valuer_conversion_deposit =  app(ConvertionPercentBackend::class)->convertAdminCommission($amount_user->percent_merchands * $amount_valeur);
                    $admin_valeur =  app(ConvertionPercentBackend::class)->convertAdminCommission($amount_user->percent_admin *  $amount_valeur);

                    $walletXbet->update([
                        'amount_solde' => $amount_solde,
                        'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                    ]);

                    $account_accepted = sabdy()->is_accepted_companie ?? false;

                    if ($account_accepted && $this->showTransRemonteDepotXbetCButton) {

                        $valuer_companie = $amount_user->percent_companie * $amount_valeur;

                        $walletXbet->increment('commission_companie', $valuer_companie);
                        app(CommissionServiceBackend::class)->distributeAdminCommissionAcceptedXbetCompanies($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_valeur, $valuer_conversion_companie, $valuer_conversion_deposit);
                    } else {
                        app(CommissionServiceBackend::class)->distributeAdminCommissionXbetNoCompanies($admin_civ, 'wallet_xbet_id', $walletXbet->id,  $admin_valeur, $valuer_conversion_deposit);
                    }
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminPaiementCommissions($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_nocomission);
                }
            } else {

                $amount__commission = $amount_valeur + ($amount_user->percent_with_drawal * $amount_valeur);

                $amount_solde = sabdy()->wallet->amount - $amount__commission;

                $admin_nocomission = app(ConvertionPercentBackend::class)->convertAdminCommission($amount_user->percent_with_drawal * $amount_valeur);

                if ($this->showTransRemonteRetraitXbetButton) {

                    $valuer_deposit =  $amount_user->percent_under_drawal * $amount_valeur;
                    $valuer_conversion_deposit =  app(ConvertionPercentBackend::class)->convertAdminCommission($amount_user->percent_under_drawal * $amount_valeur);
                    $admin_valeur =  app(ConvertionPercentBackend::class)->convertAdminCommission($amount_user->percent_admin *  $amount_valeur);

                    $walletXbet->update([
                        'amount_solde' => $amount_solde,
                        'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                    ]);

                    $account_accepted = sabdy()->is_accepted_companie ?? false;

                    if ($account_accepted && $this->showTransRemonteRetraitXbetCButton) {

                        $valuer_companie =  $amount_user->percent_companie * $amount_valeur;

                        $valuer_conversion_companie =  app(ConvertionPercentBackend::class)->convertAdminCommission($amount_user->percent_companie * $amount_valeur);

                        $walletXbet->increment('commission_companie', $valuer_companie);

                        app(CommissionServiceBackend::class)->distributeAdminCommissionAcceptedXbetCompanies($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_valeur, $valuer_conversion_companie, $valuer_conversion_deposit);
                    } else {
                        app(CommissionServiceBackend::class)->distributeAdminCommissionXbetNoCompanies($admin_civ, 'wallet_xbet_id', $walletXbet->id,  $admin_valeur, $valuer_conversion_deposit);
                    }
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminPaiementCommissions($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_nocomission);
                }
            }
        } else {

            return null;
        }

        return true;
    }

    public function resetXbetTransactionField()
    {
        $this->id_compte = null;
        $this->amount_xbet = '';
        $this->full_nameXbet = '';
        $this->code_compte = '';
        $this->countrie = '';
        $this->citie_xbet = '';
        $this->area_xbet = '';
        $this->id_compte_retrait = '';
        $this->choose_type_transaction = null;
        $this->stepXbet = 1;
        $this->emit('modal-hide-xbet-validated');
        $this->emit('modal-hide-xbet');
        $this->emit('menu-xbet-active');
    }


    public function calculateAmountXbets()
    {

        $this->commission_transaction_val_admin_xbet_receive = 0;
        $this->commission_transaction_val_nat_xbet_receive = 0;
        $this->commission_transaction_val_nat_xbet_pay = 0;


        if ($this->amount_xbet) {

            $reel_amount = $this->search_amount($this->amount_xbet);

            if ($reel_amount != '') {

                $commission_transaction = app(CommissionServicePercentBackend::class)->getRobusPercentageOnexbet();

                if ($this->choose_type_transaction === 1) {
                    $commission_percent_transaction = (float) $commission_transaction->percent * $reel_amount;
                    $this->commission_transaction_val_admin_xbet_receive = (float) $commission_transaction->percent_admin * $reel_amount;
                    $this->commission_transaction_val_nat_xbet_receive = (float) $reel_amount;
                    $this->commission_transaction_val_nat_xbet_pay = (float) $commission_percent_transaction;
                    $this->commission_transaction_val_nat_xbet_com = (float) $commission_transaction->percent_merchands * $reel_amount;
                } else {
                    $commission_percent_transaction = (float) $commission_transaction->percent_with_drawal * $reel_amount;
                    $this->commission_transaction_val_admin_xbet_receive = (float) $commission_transaction->percent_admin * $reel_amount;
                    $this->commission_transaction_val_nat_xbet_receive = (float) $reel_amount;
                    $this->commission_transaction_val_nat_xbet_pay = (float) $commission_percent_transaction;
                    $this->commission_transaction_val_nat_xbet_com = (float) $commission_transaction->percent_under_drawal * $reel_amount;
                }
            }
        }
    }


    public function updatedAmountXbet()
    {
        $this->calculateAmountXbets();
    }

    public function render()
    {
        return view(
            'livewire.transferts.transfert-xbet',
            [
                'showRetraitButton' => $this->showTransDepotXbetButton,
                'showDepotButton' => $this->showTransRetraitXbetButton,
                'showDepotRemonteButton' => $this->showTransRemonteDepotXbetButton,
                'showRetraitRemonteButton' => $this->showTransRemonteRetraitXbetButton,
                'countrie_number_local' => sabdy()->country
                    ->indecative,
                'amount_transaction_nat_val_xbet_percent' => $this->commission_transaction_val_nat_xbet_pay,
                'amount_transaction_nat_val_xbet_receive' => $this->commission_transaction_val_nat_xbet_receive,
                'amount_transaction_nat_val_xbet_commission' => $this->commission_transaction_val_admin_xbet_receive,
                'amount_transaction_nat_val_xbet_commission_under' => $this->commission_transaction_val_nat_xbet_com,
                'usersCountry' => sabdy()->wallet->currencie->code,
            ]
        );
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

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }
}
