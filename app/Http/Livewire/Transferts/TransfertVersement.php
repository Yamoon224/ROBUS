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
use App\Http\Services\CommissionServicePercentBackend;
use App\Events\CreateWalletVersementEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransfertVersement extends Component
{
    public $stepVersment = 1;

    public $num_accompte;
    public $bank_code;
    public $guichet_code;
    public $num_authers;
    public $amount_versement;
    public $provider_versement;
    public $password_validated = '';

    public $showRemonterVersementButton = false;
    public $showRemonterVersementCButton = false;
    public $operators;
    public $operatorsCompagnie;
    public $operatorsTransaction;
    public $isPasswordValid = false;
    public $commission_transaction_val_nat_versement_pay;
    public $commission_transaction_val_admin_versement_receive;
    public $commission_transaction_val_nat_versement_receive;

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
            'Active Versements' => 'showRemonterVersementButton',
        ];

        $restrictionCompangieMap = [
            'Active Versements' => 'showRemonterVersementCButton'
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

    public function addVersement()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_versement)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers = app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();

        $this->validate(
            [
                'num_accompte' => 'required',
                'bank_code' => 'required',
                'guichet_code' => 'required',
                'num_authers' => 'required',
                'amount_versement' => 'required',
            ],
            [
                'num_accompte.required' =>
                'Numero est obligatoire',
                'bank_code.required' =>
                'Code de la banque obligatoire',
                'guichet_code.required' =>
                'Code du guichet est obligatoire',
                'num_authers.required' =>
                'Cle obligatoire est obligatoire',
                'amount_versement.required' =>
                'Montant est obligatoire',
            ]
        );


        try {

            if ($provider) {



                $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageVersement($provider->id);

                if ($walletUser->country->code != 'CI') {
                    $this->resetVersmentField();
                    $this->emit(
                        'warning',
                        'Cette operation est uniquement pour la CIV !'
                    );
                    return;
                }
                if ($som_transfers >= $valeur_ceiling) {

                    $this->resetVersmentField();
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                    return;
                }

                $reel_amount = $this->search_amount($this->amount_versement);

                $val_amount = $reel_amount +  $percent_robus->percent *  $reel_amount;

                if ($val_amount > $walletUser->wallet->amount) {
                    $this->resetVersmentField();
                    $this->emit(
                        'error',
                        'Verifier votre montant  svp !'
                    );
                    return;
                }

                $this->stepVersment = 2;
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetVersmentField();
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedVersement()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_versement)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminValeurProperty();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();

        try {


            if (!$provider) {
                $this->emit('error', 'Rechargement existant !');
                return;
            }


            $this->validate([
                'password_validated' => 'required',
                'num_accompte' => 'required',
                'bank_code' => 'required',
                'guichet_code' => 'required',
                'num_authers' => 'required',
                'amount_versement' => 'required',
            ], [
                'password_validated.required' => 'Mot de passe est obligatoire',
                'num_accompte.required' =>
                'Numero est obligatoire',
                'bank_code.required' =>
                'Code de la banque obligatoire',
                'guichet_code.required' =>
                'Code du guichet est obligatoire',
                'num_authers.required' =>
                'Cle obligatoire est obligatoire',
                'amount_versement.required' =>
                'Montant est obligatoire',
            ]);

            if (!Hash::check($this->password_validated, sabdy()->password)) {
                $this->resetVersmentField();
                $this->emit('error', 'Vérifiez votre mot de passe SVP !');
                return;
            }


            if ($walletUser->country->code != 'CI') {
                $this->resetVersmentField();
                $this->emit(
                    'warning',
                    'Ce réchargement est uniquement pour la CIV !'
                );
                return;
            }


            if ($som_transfers >= $valeur_ceiling) {
                $this->resetVersmentField();
                $this->emit('warning', 'Votre montant journier est atteint !');
                return;
            }


            $reel_amount = $this->search_amount($this->amount_versement);
            $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageVersement($provider->id);

            $val_amount = $reel_amount;

            if ($val_amount > $walletUser->wallet->amount) {
                $this->resetVersmentField();
                $this->emit('error', 'Vérifier votre montant SVP !');
                return;
            }

            $amount_solde =  $walletUser->wallet->amount -  $val_amount;

            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                $amountReceved = $reel_amount;

                $walletVersement =  app(TransfertServiceBackend::class)->createVersementTransaction(
                    $val_amount,
                    $provider,
                    $percent_robus,
                    $amount_solde,
                    $this->num_accompte,
                    $this->bank_code,
                    $this->guichet_code,
                    $this->num_authers,
                    $amountReceved
                );

                $message =
                    'Vous avez effectué un versement de ' .
                    $walletVersement->amount .
                    $walletUser->wallet->currencie->code .
                    ' ' .
                    'par ' .
                    $walletVersement->provider->name .
                    ' le ' .
                    $walletVersement->created_at .
                    ' votre demande est en cours de validation ' .
                    'Robus vous remercie';


                app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);

                if ($walletVersement != null  && $walletVersement->status == 'PENDING') {
                    $this->updateamountVersementReceveid($admin_civ, $percent_robus, $walletVersement,  $walletUser, $reel_amount);
                }

                event(new CreateWalletVersementEvent($walletVersement));

                app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Versement Bancaire', 'Demande de versement bancaire est en cours de validation', 'Demande de versement bancaire ' . ' ' . $walletVersement->wallet->sidUser->firstname);

                if ($admins->count() > 0) {
                    app(NotificationServiceBackend::class)->notifyAdmins($admins);
                }

                $this->resetVersmentField();
                $this->emit('success', 'Votre demande de transaction a bien été prise en compte !');
                if ($walletUser->email != null) {
                    app(NotificationServiceBackend::class)->sendMailVersementBanqueNotification($walletVersement);
                }
            } else {
                $this->resetVersmentField();
                $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                return;
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->resetVersmentField();
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }


    public function calculateAmountVersements()
    {

        $this->commission_transaction_val_admin_versement_receive = 0;
        $this->commission_transaction_val_nat_versement_receive = 0;
        $this->commission_transaction_val_nat_versement_pay = 0;


        if ($this->amount_versement) {

            $reel_amount = $this->search_amount($this->amount_versement);

            if ($reel_amount != '') {

                $commission_transaction = app(CommissionServicePercentBackend::class)->getRobusPercentageVersement($this->provider_versement);
                $commission_percent_transaction = (float) $commission_transaction->percent * $reel_amount;
                $this->commission_transaction_val_admin_versement_receive = (float) $commission_percent_transaction;
                $this->commission_transaction_val_nat_versement_receive = (float) $reel_amount;
                $this->commission_transaction_val_nat_versement_pay = (float) $commission_transaction->percent_merchands * $reel_amount;
            }
        }
    }

    public function updatedAmountVersement()
    {
        $this->calculateAmountVersements();
    }


    public function render()
    {
        return view(
            'livewire.transferts.transfert-versement',
            [
                'provider_versements' => $this->VersementTransaction,
                'showRemonterVersementButton' => $this->showRemonterVersementButton,
                'amount_transaction_nat_val_versement_percent' => $this->commission_transaction_val_nat_versement_pay,
                'amount_transaction_nat_val_versement_receive' => $this->commission_transaction_val_nat_versement_receive,
                'amount_transaction_nat_val_versement_commission' => $this->commission_transaction_val_admin_versement_receive,
                'usersCountry' => sabdy()->wallet->currencie->code,
            ]
        );
    }

     public function getVersementTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $versement_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 18)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $versement_transaction;
    }

    public function updateamountVersementReceveid($admin_civ, $percent_robus, $walletVersement,  $walletUser, $amount)
    {

        $amount_percent = $percent_robus->percent * $amount;

        $valuer_deposit = (float) $percent_robus->percent_merchands * $amount;

        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = sabdy()->wallet->amount - $amount;

        $walletVersement->update([
            'amount_solde' => $amount_solde,
        ]);

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount);

        if (sabdy()->is_under_distributer) {

            if ($this->showRemonterVersementButton) {

                $valuer_companie = $percent_robus->percent_companie * $amount;

                $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                $adminAmount = (float) $amount_percent - (float) $cumul_valeurs;

                $walletVersement->increment('commissions', $valuer_deposit);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterVersementCButton) {
                    $walletVersement->increment('commission_companie', $valuer_companie);

                    $totalCommission =  $valuer_companie + $valuer_deposit;

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_versement_id', $walletVersement->id, $totalCommission, $adminAmount);
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_versement_id', $walletVersement->id, $adminAmount, $valuer_deposit);
                }
            } else {

                app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_versement_id', $walletVersement->id, $amount_percent);
            }
        } else {

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_versement_id', $walletVersement->id, $amount_percent);
        }


        return true;
    }


    public function resetVersmentField()
    {
        $this->num_accompte = null;
        $this->amount_versement = '';
        $this->provider_versement = null;
        $this->num_authers = '';
        $this->bank_code = '';
        $this->guichet_code = '';
        $this->stepVersment = 1;
        $this->emit('modal-hide-versement');
        $this->emit('menu-versement-active');
    }
    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }
}
