<?php

namespace App\Http\Livewire\Transferts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Http\Services\TotalAmoutUserServiveBackend;
use App\Http\Services\NotificationServiceBackend;
use App\Http\Services\TransfertServiceBackend;
use App\Http\Services\CommissionServiceBackend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Costs\BankCost;
use App\Models\Custumers\Country;
use App\Events\CreateWalletBankEvent;
use App\Models\OperatorService;

class TransfertDeposit extends Component
{

    public $stepVirement = 1;
    public $isPasswordValid = false;
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
    public $password_validated = '';
    public $showRemonterVirementButton = false;
    public $operators;
    public $operatorsCompagnie;
    public $operatorsTransaction;

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
            'Active Virements' => 'showRemonterVirementButton',
        ];


        foreach ($this->operators as $operator) {
            $restrictions = explode(',', $operator->name);

            foreach ($restrictionMap as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $this->{$buttonVar} = true;
                }
            }
        }

    }


    public function addVirement()
    {
        $walletUser = Auth::guard('web')->user();
        $som_transfers = app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();
        $valeur_ceiling = $walletUser->silver_ceiling;

        $this->validate(
            [
                'firstname_user' => 'required|string|min:2',
                'lastname_user' => 'required|string|min:2',
                'phone_user' => 'required|string|min:8',
                'account_number_user_bank' => 'required|string',
                'amount_virement' => 'required|numeric|min:1',
                'provider_virement' => 'required|numeric',
            ],
            [
                'firstname_user.required' => 'Votre nom est obligatoire',
                'lastname_user.required' => 'Votre prénom est obligatoire',
                'phone_user.required' => 'Votre numéro est obligatoire',
                'account_number_user_bank.required' => 'Votre numéro de compte est obligatoire',
                'amount_virement.required' => 'Votre montant est obligatoire',
                'provider_virement.required' => 'Votre fournisseur est obligatoire',
            ]
        );

        try {

            if (in_array($this->provider_virement, [11, 12])) {


                // Percent comission en valeur
                $percent_robus = BankCost::find(2);

                if ($som_transfers >= $valeur_ceiling) {
                    $this->resetVirementField();
                    $this->emit('modal-hide-virement');
                    $this->emit('warning', 'Votre montant journalier est atteint !');
                    return;
                }

                $valeur_end = $percent_robus->levelBankIvories->last();

                if ($valeur_end && $valeur_end->amount_end < $this->amount_virement) {
                    $this->resetVirementField();
                    $this->emit('modal-hide-virement');
                    $this->emit('info', 'Vous avez dépassé le montant max !');
                    return;
                }

                $this->stepVirement = 2;
            } else {
                $this->emit('error', 'Veuillez sélectionner un virement !');
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
            $this->emit('error', 'Erreur interne, contactez un administrateur !');
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


    public function addValidatedVirement()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_virement)->first();
        $country_virement = Country::whereId($this->country_virement)
            ->whereStatus(true)
            ->first();
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminValeurProperty();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();
        $valeur_ceiling = $walletUser->silver_ceiling;
        DB::beginTransaction();

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

                    // Percent comission an valeur
                    $percent_robus = BankCost::whereId(2)->first();

                    if ($som_transfers >= $valeur_ceiling) {
                        $this->resetVirementField();
                        $this->emit('modal-hide-virement');
                        $this->emit('warning', 'Votre montant journier est atteint !');
                        return;
                    }

                    $valeur_end = $percent_robus->levelBankIvories->last();

                    if ($valeur_end->amount_end < $this->amount_virement) {
                        $this->resetVirementField();
                        $this->emit('modal-hide-virement');
                        $this->emit('info', 'Vous avez depasser le montant max du virement !!!');
                        return;
                    }

                    $reel_amount = $this->search_amount($this->amount_virement);

                    foreach ($percent_robus->levelBankIvories as $amount_user) {

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



                                    $walletBank = app(TransfertServiceBackend::class)->createNewVirement(
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

                                    app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);

                                    if ($walletBank != null) {
                                        $this->updateamountVirementReceveid($admin_civ, $amount_user, $walletBank, $walletUser, $amount_valeur);
                                    }

                                    event(new CreateWalletBankEvent($walletBank));
                                } else {

                                    $this->resetVirementField();
                                    $this->emit('modal-hide-virement');
                                    $this->emit('error', 'Desolez vous n\'etes pas autorisée !');
                                    return;
                                }

                                app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Virement bancaire', 'Demande de virement est en cours de validation', 'Demande de virement de ' . ' ' . $walletBank->wallet->sidUser->firstname);

                                if ($admins->count() > 0) {
                                    app(NotificationServiceBackend::class)->notifyAdmins($admins);
                                }
                                $this->resetVirementField();
                                $this->emit('modal-hide-virement');
                                $this->emit('success', 'Votre demande de transaction a bien été prise en compte.');
                                if ($walletUser->email != null) {
                                    app(NotificationServiceBackend::class)->sendMailVersementNotification($walletBank);
                                }
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
            DB::commit();
        } catch (\Exception $exception) {
            $this->resetVirementField();
            $this->emit('modal-hide-virement');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
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
        $this->stepVirement = 1;
        $this->emit('modal-hide-virement');
    }

    protected function updateamountVirementReceveid($admin_civ, $amount_user, $walletBank, $walletUser, $amount_valeur)
    {


        $adminAmount = $amount_user->amount;

        if (sabdy()->is_under_distributer) {


            if ($this->showRemonterVirementButton) {

                $valuer_deposit =  $amount_user->comission_distributer;

                $walletBank->update([
                    'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                ]);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterVirementCButton) {
                    $valuer_companie = $amount_user->percent_companie;
                    $walletBank->increment('commission_companie', $valuer_companie);

                    $totalCommission =  $amount_user->percent_companie +  $valuer_deposit;

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_bank_id', $walletBank->id, $totalCommission, $adminAmount);
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_bank_id', $walletBank->id, $adminAmount, $valuer_deposit);
                }
            } else {

                app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_bank_id', $walletBank->id, $adminAmount);
            }
        } else {

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_bank_id', $walletBank->id, $adminAmount);
        }

        $amount_commission = $amount_valeur;

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount_commission);

        return true;
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
        $this->account_number_user_bank = '';
        $this->stepVirement = 1;
        $this->emit('modal-hide-virement');
        $this->emit('modal-hide-virement-validated');
    }

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }



    public function render()
    {
        $walletUser = Auth::guard('web')->user();

        $commission_virement_val_nat_pay  = null;
        $commission_virement_val_nat_receive  = null;

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
        return view(
            'livewire.transferts.transfert-deposit',
            [
                'provider_virements' => $this->VirementTransaction,
                'user_auth' => $walletUser,
                'amount_virement_nation_pay' => $commission_virement_val_nat_pay,
                'usersCountry' => sabdy()->wallet->currencie->code,
                'amount_virement_nation_receive' => $commission_virement_val_nat_receive,
                'showRemonterVirementButton' => $this->showRemonterVirementButton,
            ]
        );
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
}
