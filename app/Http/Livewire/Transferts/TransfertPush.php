<?php

namespace App\Http\Livewire\Transferts;

use Livewire\Component;
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
use App\Models\Costs\PhoneCreditCost;
use App\Events\CreatewalletPushCreditEvent;
use App\Events\AdminNotifEvent;
use App\Http\Services\CommissionServicePercentBackend;
use App\Events\CreateWalletXbetEvent;


class TransfertPush extends Component
{

    public $stepPush = 1;

    public $number_phone;
    public $amount_push;
    public $full_name_push;

    public $operators;
    public $operatorsCompagnie;
    // Compagnies
    public $showTransRemontePushCButton = false;
    public $operatorsTransaction;
    public $isPasswordValid = false;
    public $showRemonterPushButton = false;
    public $password_validated = '';
    // Phone credit
    public $commission_transaction_val_nat_push_pay;
    public $commission_transaction_val_admin_push_receive;
    public $commission_transaction_val_nat_push_receive;
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
            'Active Push' => 'showRemonterPushButton'
        ];

        $restrictionCompangieMap = [
            'Active Push' => 'showRemonterPushCButton'
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
            return;
        }

        if (Hash::check($this->password_validated, sabdy()->password)) {
            $this->isPasswordValid = true;
        } else {
            $this->isPasswordValid = false;
        }
    }

    public function addPush()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId(91)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers =  $this->getSumValTransactionProperty();

        $this->validate(
            [
                'number_phone' => 'required',
                'full_name_push' => 'required',
                'amount_push' => 'required',
            ],
            [
                'number_phone.required' =>
                'Numero est obligatoire',
                'full_name_push.required' =>
                'Nom & prénom sont obligatoire',
                'amount_push.required' =>
                'Montant est obligatoire',
            ]
        );


        try {

            if ($provider) {



                $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentagePush();

                if ($walletUser->country->code != 'CI') {
                    $this->resetPushField();
                    $this->emit(
                        'warning',
                        'Cette operation est uniquement pour la CIV !'
                    );
                    return;
                }
                if ($som_transfers >= $valeur_ceiling) {

                    $this->resetPushField();
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                    return;
                }

                $reel_amount = $this->search_amount($this->amount_push);
                
                $val_amount = $reel_amount ;

                if ($val_amount > $walletUser->wallet->amount) {
                    $this->resetPushField();
                    $this->emit(
                        'error',
                        'Verifier votre montant  svp !'
                    );
                    return;
                }

                $this->stepPush = 2;
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetPushField();
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedPush()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId(91)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminValeurProperty();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        try {


            if (!$provider) {
                $this->emit('error', 'Rechargement existant !');
                return;
            }


            $this->validate([
                'password_validated' => 'required',
                'number_phone' => 'required',
                'full_name_push' => 'required',
                'amount_push' => 'required',
            ], [
                'password_validated.required' => 'Mot de passe est obligatoire',
                'number_phone.required' => 'Numero est obligatoire',
                'full_name_push.required' => 'Nom & prénom sont obligatoire',
                'amount_push.required' => 'Montant est obligatoire',
            ]);

            if (!Hash::check($this->password_validated, sabdy()->password)) {
                $this->resetPushField();
                $this->emit('error', 'Vérifiez votre mot de passe SVP !');
                return;
            }


            if ($walletUser->country->code != 'CI') {
                $this->resetPushField();
                $this->emit(
                    'warning',
                    'Ce réchargement est uniquement pour la CIV !'
                );
                return;
            }


            if ($som_transfers >= $valeur_ceiling) {
                $this->resetPushField();
                $this->emit('warning', 'Votre montant journier est atteint !');
                return;
            }


            $reel_amount = $this->search_amount($this->amount_push);
            $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentagePush();
            
            $val_amount = $reel_amount;

            if ($val_amount > $walletUser->wallet->amount) {
                $this->resetPushField();
                $this->emit('error', 'Vérifier votre montant SVP !');
                return;
            }

            $amount_solde =  $walletUser->wallet->amount -  $val_amount;

            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                $amountReceved = $reel_amount;

                $walletPush =  app(TransfertServiceBackend::class)->createNewPhoneCredit(
                    $walletUser,
                    $reel_amount,
                    $provider,
                    $percent_robus,
                    $this->number_phone_credit,
                    $amount_solde,
                    $amountReceved
                );

                $message =
                    'Vous avez effectué un rechargement de carte Push de ' .
                    $walletPush->amount .
                    $walletUser->wallet->currencie->code .
                    ' ' .
                    'par ' .
                    $walletPush->provider->name .
                    ' le ' .
                    $walletPush->created_at .
                    ' votre demande est en cours de validation ' .
                    'Robus vous remercie';


                app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);


                if ($walletPush != null  && $walletPush->status == 'PENDING') {
                    $this->updateamountPushReceveid($admin_civ, $percent_robus, $walletPush,  $walletUser, $reel_amount);
                }

                event(new CreateWalletXbetEvent($walletPush));

                app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Réchargement Push', 'Demande de rechargement push est en cours de validation', 'Demande de paiement de ' . ' ' . $walletPush->wallet->sidUser->firstname);

                if ($admins->count() > 0) {
                    app(NotificationServiceBackend::class)->notifyAdmins($admins);
                }

                $this->resetPushField();
                $this->emit('success', 'Votre demande de transaction a bien été prise en compte !');
                // if ($walletUser->email != null) {
                //     app(NotificationServiceBackend::class)->sendMailPhoneCreditNotification($walletPush);
                // }
            } else {
                $this->resetPushField();
                $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                return;
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->resetPushField();
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }
  


    public function calculateAmountPushs()
    {

        $this->commission_transaction_val_admin_push_receive = 0;
        $this->commission_transaction_val_nat_push_receive = 0;
        $this->commission_transaction_val_nat_push_pay = 0;


        if ($this->amount_xbet) {

            $reel_amount = $this->search_amount($this->amount_xbet);

            if ($reel_amount != '') {

                $commission_transaction = app(CommissionServicePercentBackend::class)->getRobusPercentagePush();

                $commission_percent_transaction = (float) $commission_transaction->percent * $reel_amount;
                $this->commission_transaction_val_admin_push_receive = (float) $commission_percent_transaction;
                $this->commission_transaction_val_nat_push_receive = (float) $reel_amount;
                $this->commission_transaction_val_nat_push_pay = (float) $commission_transaction->percent_merchands * $reel_amount;
            }
        }
    }



    public function updatedAmountPush()
    {
        $this->calculateAmountPushs();
    }


    public function resetPushField()
    {
        $this->number_phone = null;
        $this->amount_push = '';
        $this->full_name_push = '';
        $this->stepPush = 1;
        $this->emit('modal-hide-push');
        $this->emit('menu-push-active');
    }

    public function render()
    {
        return view('livewire.transferts.transfert-push', [
            'showRemonterPushButton' => $this->showRemonterPushButton,
            'countrie_number_local' => Auth::guard('web')->user()->country
                ->indecative,
            'amount_transaction_nat_val_push_percent' => $this->commission_transaction_val_nat_push_pay,
            'amount_transaction_nat_val_push_receive' => $this->commission_transaction_val_nat_push_receive,
            'amount_transaction_nat_val_push_commission' => $this->commission_transaction_val_admin_push_receive,
            'usersCountry' => sabdy()->wallet->currencie->code,
        ]);
    }

    public function updateamountPushReceveid($admin_civ, $percent_robus, $walletPush,  $walletUser, $amount)
    {

        $amount_percent = $percent_robus->percent * $amount;

        $valuer_deposit = (float) $percent_robus->percent_merchands * $amount;

        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = sabdy()->wallet->amount - $amount;

        $walletPush->update([
            'amount_solde' => $amount_solde,
        ]);

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount);

        if (sabdy()->is_under_distributer) {

            if ($this->showRemonterPushButton) {

                $valuer_companie = $percent_robus->percent_companie * $amount;

                $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                $adminAmount = (float) $amount_percent - (float) $cumul_valeurs;

                $walletPush->increment('commissions', $valuer_deposit);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterPushCButton) {
                    $walletPush->increment('commission_companie', $valuer_companie);

                    $totalCommission =  $valuer_companie + $valuer_deposit;

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_push_id', $walletPush->id, $totalCommission, $adminAmount);
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_push_id', $walletPush->id, $adminAmount, $valuer_deposit);
                }
            } else {

                app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_push_id', $walletPush->id, $amount_percent);
            }
        } else {

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_push_id', $walletPush->id, $amount_percent);
        }


        return true;
    }

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }

    public function getSumValTransactionProperty()
    {
        $service = app(TotalAmoutUserServiveBackend::class);

        $somm_valeur = $service->getWalletBanksCeilingProperty()
            + $service->getWalletDepositsCeilingProperty()
            + $service->getWalletIntersCeilingProperty()
            + $service->getWalletPaymentsCeilingProperty()
            + $service->getWalletTransactionsCeilingProperty()
            + $service->getwalletPushCreditsCeilingProperty()
            + $service->getWalletReloadsCeilingProperty()
            + $service->getWalletBankCardCeilingProperty();

        return $somm_valeur;
    }
}
