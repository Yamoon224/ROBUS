<?php

namespace App\Http\Livewire\Transferts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\OperatorService;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Http\Services\TotalAmoutUserServiveBackend;
use App\Http\Services\NotificationServiceBackend;
use App\Http\Services\TransfertServiceBackend;
use App\Http\Services\CommissionServiceBackend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\CommissionServicePercentBackend;
use App\Events\CreateWalletTresortEvent;

class TransfertTresort extends Component
{

    public $stepTresort = 1;

    public $number_phone;
    public $amount_tresort;
    public $full_name_tresort;
    public $choose_transaction;

    public $operators;
    public $operatorsCompagnie;
    // Compagnies
    public $showRemonterTresortCButton = false;
    public $operatorsTransaction;
    public $isPasswordValid = false;
    public $showRemonterTresortButton = false;
    public $password_validated = '';
    public $commission_transaction_val_nat_tresort_pay;
    public $commission_transaction_val_admin_tresort_receive;
    public $commission_transaction_val_nat_tresort_receive;
    public $need_identity_number = false;
    public $identity_number;

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
            'Active Tresort Money' => 'showRemonterTresortButton'
        ];

        $restrictionCompangieMap = [
            'Active Tresort Money' => 'showRemonterTresortCButton'
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

    public function updated($property, $value)
    {
        if ($property === 'amount_tresort') {
            $amount = (int) $value;
            $this->need_identity_number = $amount >= 100000 && $amount <= 200000;
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

    public function addTresort()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId(106)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers = app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();

        $this->validate(
            [
                'number_phone' => 'required',
                'full_name_tresort' => 'required',
                'amount_tresort' => 'required',
                'choose_transaction' => 'required',

            ],
            [
                'number_phone.required' =>
                'Numero est obligatoire',
                'full_name_tresort.required' =>
                'Nom & prénom sont obligatoire',
                'amount_tresort.required' =>
                'Montant est obligatoire',
                'choose_transaction.required' => 'Le choix est obligatoire est obligatoire',

            ]
        );


        try {

            if ($provider) {



                $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageTresortMoney();

                if ($walletUser->country->code != 'CI') {
                    $this->resetTresortField();
                    $this->emit(
                        'warning',
                        'Cette operation est uniquement pour la CIV !'
                    );
                    return;
                }
                if ($som_transfers >= $valeur_ceiling) {

                    $this->resetTresortField();
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                    return;
                }

                $reel_amount = $this->search_amount($this->amount_tresort);

                $val_amount = $reel_amount;

                if ($val_amount > $walletUser->wallet->amount) {
                    $this->resetTresortField();
                    $this->emit(
                        'error',
                        'Verifier votre montant  svp !'
                    );
                    return;
                }

                $this->stepTresort = 2;
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetTresortField();
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedTresort()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId(106)->first();
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
                'number_phone' => 'required',
                'full_name_tresort' => 'required',
                'amount_tresort' => 'required',
                'choose_transaction' => 'required',
            ], [
                'password_validated.required' => 'Mot de passe est obligatoire',
                'number_phone.required' => 'Numero est obligatoire',
                'full_name_tresort.required' => 'Nom & prénom sont obligatoire',
                'amount_tresort.required' => 'Montant est obligatoire',
                'choose_transaction.required' => 'Le choix est obligatoire est obligatoire',
            ]);

            if (!Hash::check($this->password_validated, sabdy()->password)) {
                $this->resetTresortField();
                $this->emit('error', 'Vérifiez votre mot de passe SVP !');
                return;
            }


            if ($walletUser->country->code != 'CI') {
                $this->resetTresortField();
                $this->emit(
                    'warning',
                    'Ce réchargement est uniquement pour la CIV !'
                );
                return;
            }


            if ($som_transfers >= $valeur_ceiling) {
                $this->resetTresortField();
                $this->emit('warning', 'Votre montant journier est atteint !');
                return;
            }


            $reel_amount = $this->search_amount($this->amount_tresort);
            $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageTresortMoney();

            $val_amount = $reel_amount;

            if ($val_amount > $walletUser->wallet->amount) {
                $this->resetTresortField();
                $this->emit('error', 'Vérifier votre montant SVP !');
                return;
            }

            $amount_solde =  $walletUser->wallet->amount -  $val_amount;

            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                $amountReceved = $reel_amount;

                $walletTresort =  app(TransfertServiceBackend::class)->createTresortTransaction(
                    $val_amount,
                    $provider,
                    $percent_robus,
                    $amount_solde,
                    $this->full_name_tresort,
                    $this->number_phone,
                    $amountReceved,
                    $this->identity_number,
                    $this->choose_transaction
                );

                $message =
                    'Vous avez effectué un rechargement par tresort Money de ' .
                    $walletTresort->amount .
                    $walletUser->wallet->currencie->code .
                    ' ' .
                    'par ' .
                    $walletTresort->provider->name .
                    ' le ' .
                    $walletTresort->created_at .
                    ' votre demande est en cours de validation ' .
                    'Robus vous remercie';


                app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);

                if ($walletTresort != null  && $walletTresort->status == 'PENDING') {
                    $this->updateamountTresortReceveid($admin_civ, $percent_robus, $walletTresort,  $walletUser, $reel_amount);
                }

                event(new CreateWalletTresortEvent($walletTresort));

                app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Réchargement Tresort', 'Demande de rechargement Tresort est en cours de validation', 'Demande de paiement de ' . ' ' . $walletTresort->wallet->sidUser->firstname);

                if ($admins->count() > 0) {
                    app(NotificationServiceBackend::class)->notifyAdmins($admins);
                }

                $this->resetTresortField();
                $this->emit('success', 'Votre demande de transaction a bien été prise en compte !');
                if ($walletUser->email != null) {
                    app(NotificationServiceBackend::class)->sendMailPushNotification($walletTresort);
                }
            } else {
                $this->resetTresortField();
                $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                return;
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->resetTresortField();
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }


    public function calculateAmountTresorts()
    {

        $this->commission_transaction_val_admin_tresort_receive = 0;
        $this->commission_transaction_val_nat_tresort_receive = 0;
        $this->commission_transaction_val_nat_tresort_pay = 0;


        if ($this->amount_tresort) {

            $reel_amount = $this->search_amount($this->amount_tresort);

            if ($reel_amount != '') {

                $commission_transaction = app(CommissionServicePercentBackend::class)->getRobusPercentageTresortMoney();

                if ($this->choose_transaction == 1) {
                    $commission_percent_transaction = (float) $commission_transaction->percent * $reel_amount;
                    $this->commission_transaction_val_admin_tresort_receive = (float) $commission_percent_transaction;
                    $this->commission_transaction_val_nat_tresort_receive = (float) $reel_amount;
                    $this->commission_transaction_val_nat_tresort_pay = (float) $commission_transaction->percent_merchands * $reel_amount;
                } else {
                    $commission_percent_transaction = (float) $commission_transaction->percent * $reel_amount;
                    $this->commission_transaction_val_admin_tresort_receive = (float) $commission_percent_transaction;
                    $this->commission_transaction_val_nat_tresort_receive = (float) $reel_amount;
                    $this->commission_transaction_val_nat_tresort_pay = (float) $commission_transaction->percent_with_drawal * $reel_amount;
                }
            }
        }
    }

    public function updatedAmountTresort()
    {
        $this->calculateAmountTresorts();
    }


    public function resetTresortField()
    {
        $this->number_phone = null;
        $this->amount_tresort = '';
        $this->full_name_tresort = '';
        $this->stepTresort = 1;
        $this->emit('modal-hide-tresort');
        $this->emit('menu-tresort-active');
    }

    public function render()
    {
        return view(
            'livewire.transferts.transfert-tresort',
            [
                'showRemonterTresortButton' => $this->showRemonterTresortButton,
                'countrie_number_local' => Auth::guard('web')->user()->country
                    ->indecative,
                'amount_transaction_nat_val_tresort_percent' => $this->commission_transaction_val_nat_tresort_pay,
                'amount_transaction_nat_val_tresort_receive' => $this->commission_transaction_val_nat_tresort_receive,
                'amount_transaction_nat_val_tresort_commission' => $this->commission_transaction_val_admin_tresort_receive,
                'usersCountry' => sabdy()->wallet->currencie->code,
            ]
        );
    }

    public function updateamountTresortReceveid($admin_civ, $percent_robus, $walletTresort,  $walletUser, $amount)
    {

        $amount_percent = $percent_robus->percent * $amount;

        $percent = ($this->choose_transaction == 1)
            ? $percent_robus->percent_merchands
            : $percent_robus->percent_with_drawal;

        $valuer_deposit = (float) $percent * $amount;

        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = sabdy()->wallet->amount - $amount;

        $walletTresort->update([
            'amount_solde' => $amount_solde,
        ]);

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount);

        if (sabdy()->is_under_distributer) {

            if ($this->showRemonterTresortButton) {

                $valuer_companie = $percent_robus->percent_companie * $amount;

                $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                $adminAmount = (float) $amount_percent - (float) $cumul_valeurs;

                $walletTresort->increment('commissions', $valuer_deposit);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterTresortCButton) {
                    $walletTresort->increment('commission_companie', $valuer_companie);

                    $totalCommission =  $valuer_companie + $valuer_deposit;

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_tresort_money_id', $walletTresort->id, $totalCommission, $adminAmount);
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_tresort_money_id', $walletTresort->id, $adminAmount, $valuer_deposit);
                }
            } else {

                app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_tresort_money_id', $walletTresort->id, $amount_percent);
            }
        } else {

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_tresort_money_id', $walletTresort->id, $amount_percent);
        }


        return true;
    }

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }
}
