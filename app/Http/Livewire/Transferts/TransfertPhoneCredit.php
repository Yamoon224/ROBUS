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
use App\Events\CreateWalletPhoneCreditEvent;
use App\Events\AdminNotifEvent;

class TransfertPhoneCredit extends Component
{

    public $stepPhoneCredit = 1;
    // Phone credit

    public $provider_phone_credit;
    public $number_phone_credit;
    public $amount_phone_credit;
    public $description_phone_credit;

    public $operators;
    public $operatorsCompagnie;
    public $operatorsTransaction;
    public $isPasswordValid = false;
    public $showRemonterCreditButton = false;
    public $password_validated = '';
    // Phone credit
    public $commission_transaction_val_nat_phone_pay;
    public $commission_transaction_val_admin_phone_receive;
    public $commission_transaction_val_nat_phone_receive;
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
            'Active Crédits' => 'showRemonterCreditButton',
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


    public function addPhoneCredit()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_phone_credit)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers = app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();

        $this->validate(
            [
                'provider_phone_credit' => 'required',
                'number_phone_credit' => 'required',
                'amount_phone_credit' => 'required',
            ],
            [
                'provider_phone_credit.required' =>
                'Fournisseur est obligatoire',
                'number_phone_credit.required' =>
                'Numero de télephone est obligatoire',
                'amount_phone_credit.required' =>
                'Montant est obligatoire',
            ]
        );


        try {

            if ($provider) {



                $percent_robus = PhoneCreditCost::whereId(1)->first();

                if ($walletUser->country->code != 'CI') {
                    $this->resetPhoneCreditField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit(
                        'warning',
                        'Ce réchargement est uniquement pour la CIV !'
                    );
                    return;
                }
                if ($som_transfers >= $valeur_ceiling) {

                    $this->resetPhoneCreditField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                    return;
                }

                $reel_amount = $this->search_amount($this->amount_phone_credit);


                $val_amount = $reel_amount +  $percent_robus->percent *  $reel_amount;

                if ($val_amount > $walletUser->wallet->amount) {
                    $this->resetPhoneCreditField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit(
                        'error',
                        'Verifier votre montant  svp !'
                    );
                    return;
                }

                $this->stepPhoneCredit = 2;
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetPhoneCreditField();
            $this->emit('modal-hide-phoneCredit');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedPhoneCredit()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_phone_credit)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminValeurProperty();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();
        DB::beginTransaction();

        try {


            if (!$provider) {
                $this->emit('error', 'Rechargement existant !');
                return;
            }


            $this->validate([
                'password_validated' => 'required',
                'provider_phone_credit' => 'required',
                'number_phone_credit' => 'required',
                'amount_phone_credit' => 'required',
            ], [
                'password_validated.required' => 'Mot de passe est obligatoire',
                'provider_phone_credit.required' => 'Fournisseur est obligatoire',
                'number_phone_credit.required' => 'Numéro est obligatoire',
                'amount_phone_credit.required' => 'Montant est obligatoire',
            ]);

            if (!Hash::check($this->password_validated, sabdy()->password)) {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit');
                $this->emit('error', 'Vérifiez votre mot de passe SVP !');
                return;
            }


            if ($walletUser->country->code != 'CI') {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit');
                $this->emit(
                    'warning',
                    'Ce réchargement est uniquement pour la CIV !'
                );
                return;
            }


            if ($som_transfers >= $valeur_ceiling) {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit');
                $this->emit('warning', 'Votre montant journier est atteint !');
                return;
            }


            $reel_amount = $this->search_amount($this->amount_phone_credit);
            $percent_robus = PhoneCreditCost::find(1);
            $val_amount = $reel_amount + $percent_robus->percent * $reel_amount;

            if ($val_amount > $walletUser->wallet->amount) {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit');
                $this->emit('error', 'Vérifier votre montant SVP !');
                return;
            }

            $amount_solde =  $walletUser->wallet->amount -  $val_amount;

            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                $amountReceved = $reel_amount;

                $walletPhone =  app(TransfertServiceBackend::class)->createNewPhoneCredit(
                    $walletUser,
                    $reel_amount,
                    $provider,
                    $percent_robus,
                    $this->number_phone_credit,
                    $amount_solde,
                    $amountReceved
                );

                $message =
                    'Vous avez effectué un paiement de credit de ' .
                    $walletPhone->amount .
                    $walletUser->wallet->currencie->code .
                    ' ' .
                    'par ' .
                    $walletPhone->provider->name .
                    ' le ' .
                    $walletPhone->created_at .
                    ' votre demande est en cours de validation ' .
                    'Robus vous remercie';


                app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);


                if ($walletPhone != null  && $walletPhone->status == 'PENDING') {
                    $this->updateamountPhoneCreditReceveid($admin_civ, $percent_robus, $walletPhone,  $walletUser, $reel_amount);
                }

                event(new CreateWalletPhoneCreditEvent($walletPhone));

                app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Paiement crédit', 'Demande de paiement de credit est en cours de validation', 'Demande de paiement de ' . ' ' . $walletPhone->wallet->sidUser->firstname);

                if ($admins->count() > 0) {
                    app(NotificationServiceBackend::class)->notifyAdmins($admins);
                }

                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit');
                $this->emit('success', 'Votre demande de transaction a bien été prise en compte !');
                if ($walletUser->email != null) {
                    app(NotificationServiceBackend::class)->sendMailPhoneCreditNotification($walletPhone);
                }
            } else {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit');
                $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                return;
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->resetPhoneCreditField();
            $this->emit('modal-hide-phoneCredit');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function updateamountPhoneCreditReceveid($admin_civ, $percent_robus, $walletPhone,  $walletUser, $amount)
    {

        $amount_percent = $percent_robus->percent * $amount;

        $valuer_deposit = (float) $percent_robus->percent_merchands * $amount;

        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = sabdy()->wallet->amount - $amount_commission;

        $walletPhone->update([
            'amount_solde' => $amount_solde,
        ]);

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount_commission);

        if (sabdy()->is_under_distributer) {

            if ($this->showRemonterCreditButton) {

                $valuer_companie = $percent_robus->percent_companie * $amount;

                $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                $adminAmount = (float) $amount_percent - (float) $cumul_valeurs;

                $walletPhone->increment('commissions', $valuer_deposit);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterCreditCButton) {
                    $walletPhone->increment('commission_companie', $valuer_companie);

                    $totalCommission =  $valuer_companie + $valuer_deposit;

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_phone_credit_id', $walletPhone->id, $totalCommission, $adminAmount);
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_phone_credit_id', $walletPhone->id, $adminAmount, $valuer_deposit);
                }
            } else {

                app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_phone_credit_id', $walletPhone->id, $amount_percent);
            }
        } else {

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_phone_credit_id', $walletPhone->id, $amount_percent);
        }


        return true;
    }


    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }

   

    public function cancelPhoneCreditField()
    {
        $this->provider_phone_credit = null;
        $this->number_phone_credit = '';
        $this->amount_phone_credit = '';
        $this->stepPhoneCredit = 1;

        $this->emit('modal-hide-phoneCredit');
        $this->emit('modal-hide-phoneCredit-validated');
        $this->emit('menu-phoneCredit-active');
    }

    public function resetPhoneCreditField()
    {
        $this->provider_phone_credit = null;
        $this->number_phone_credit = '';
        $this->amount_phone_credit = '';
        $this->stepPhoneCredit = 1;
        $this->emit('modal-hide-phoneCredit-validated');
        $this->emit('menu-phoneCredit-active');
        // $this->emit('modal-hide-phoneCredit');
    }

    public function calculateAmountPhoneCredits()
    {

        $this->commission_transaction_val_admin_phone_receive = 0;
        $this->commission_transaction_val_nat_phone_receive = 0;
        $this->commission_transaction_val_nat_phone_pay = 0;


        if ($this->amount_phone_credit) {
            // Calcul pour les transactions Robus
            $reel_amount = $this->search_amount($this->amount_phone_credit);

            if ($reel_amount != '') {

                $commission_transaction = PhoneCreditCost::whereId(1)->first();

                $commission_percent_transaction = (float) $commission_transaction->percent * $reel_amount;
                // Calculs
                $this->commission_transaction_val_admin_phone_receive = (float) $commission_transaction->percent_merchands * $reel_amount;
                $this->commission_transaction_val_nat_phone_receive = (float) $reel_amount;
                $this->commission_transaction_val_nat_phone_pay = (float) $commission_percent_transaction;
            }
        }
    }

    public function updatedAmountPhoneCredit()
    {
        $this->calculateAmountPhoneCredits();
    }

    public function render()
    {
        return view('livewire.transferts.transfert-phone-credit', [
            'showRemonterCreditButton' => $this->showRemonterCreditButton,
            'provider_credits' => $this->PhoneCreditTransaction,
            'countrie_number_local' => Auth::guard('web')->user()->country
                ->indecative,
            'amount_transaction_nat_val_phone_percent' => $this->commission_transaction_val_nat_phone_pay,
            'amount_transaction_nat_val_phone_receive' => $this->commission_transaction_val_nat_phone_receive,
            'amount_transaction_nat_val_phone_commission' => $this->commission_transaction_val_admin_phone_receive,
            'usersCountry' => sabdy()->wallet->currencie->code,
        ]);
    }

    public function getPhoneCreditTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $phone_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 6)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $phone_transaction;
    }
}
