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
use App\Models\Costs\DepositCost;
use App\Models\Custumers\Country;
use App\Events\CreateWalletReloadCardEvent;
use App\Models\OperatorService;


class TransfertReloadCard extends Component
{

    public $stepReloadCard = 1;
    // Relaod cards
    public $reloadcard_description;
    public $reloadcardId;
    public $reloadcard_cost;
    public $reloadcardStatus;
    public $provider_reloadcard;
    public $amount_reloadcard;
    public $account_number_reload_card;
    public $name_reloadcard;
    public $password_validated = '';
    public $operators;
    public $operatorsCompagnie;
    public $operatorsTransaction;
    public $isPasswordValid = false;
    public $showRemonterRechargementButton = false;


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
            'Active Rechargements' => 'showRemonterRechargementButton',
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


    protected $validReloadCardProviders = [13, 14, 15, 16, 17];

    protected function validateReloadCard()
    {
        $this->validate([
            'amount_reloadcard' => 'required|numeric|min:2000',
            'account_number_reload_card' => 'required',
            'name_reloadcard' => 'required',
        ], [
            'amount_reloadcard.required' => 'Votre montant est obligatoire',
            'amount_reloadcard.numeric' => 'Le montant doit être numérique',
            'amount_reloadcard.min' => 'Le montant minimum est de 2000',
            'name_reloadcard.required' => 'Nom & prénom est obligatoire',
            'account_number_reload_card.required' => 'Numero est obligatoire',
        ]);
    }

    public function addReloadCard()
    {
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_reloadcard)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();
        DB::beginTransaction();

        $this->validateReloadCard();

        try {

            if ($provider) {

                $percent_robus = DepositCost::whereId(2)->first();


                if ($walletUser->country->code != 'CI') {
                    return $this->emit('error', 'Ce réchargement se fait que en CI !');
                }


                // Provider Bsic and Ecobank
                if (in_array($this->provider_reloadcard, $this->validReloadCardProviders)) {


                    if ($som_transfers >= $valeur_ceiling) {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-reloadCard');
                        $this->emit('warning', 'Votre montant journier est atteint !');
                        return;
                    }


                    if (sabdy()->is_under_distributer) {
                        $valeur_first = $percent_robus->levelDepositIvories->where('is_ivoires', true)->first();
                        $valeur_end = $percent_robus->levelDepositIvories->where('is_ivoires', true)->last();
                        $all_valeurs = $percent_robus->levelDepositIvories->where('is_ivoires', true);
                    } else {

                        $valeur_first = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->first();
                        $valeur_end = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->last();
                        $all_valeurs = $percent_robus->levelDepositIvories->where('is_inter_bank', true);
                    }

                    $reel_amount = $this->search_amount($this->amount_reloadcard);

                    if ($valeur_end->amount_end < $reel_amount) {
                        $this->resetReloadCardField();
                        $this->emit('modal-hide-reloadCard');
                        $this->emit('info', 'Vous avez depasser le montant max !'  . $$valeur_end->amount_end);
                        return;
                    }


                    if ($valeur_first->amount_begin > $reel_amount) {
                        $this->resetReloadCardField();
                        $this->emit('modal-hide-reloadCard');
                        $this->emit('info', 'Vous avez depasser le montant min !' . $valeur_first->amount_begin);
                        return;
                    }

                    $this->stepReloadCard = 2;
                } else {
                    $this->emit('error', 'Aucune selection !');
                }

                if ($som_transfers >= $valeur_ceiling) {
                    $this->resetReloadCardField();
                    $this->emit('modal-hide-inters');
                    $this->emit('warning', 'Votre montant journalier est atteint !');
                    return false;
                }
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->resetReloadCardField();
            $this->emit('modal-hide-reloadCard');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedReloadCard()
    {
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_reloadcard)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminValeurProperty();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();
        DB::beginTransaction();

        try {

            if ($provider) {

                $percent_robus = DepositCost::whereId(2)->first();


                if ($walletUser->country->code != 'CI') {
                    return $this->emit('error', 'Ce réchargement se fait que en CI !');
                }


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
                    // Provider Bsic and Ecobank
                    if (in_array($this->provider_reloadcard, $this->validReloadCardProviders)) {

                        $this->validateReloadCard();

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetPaiementField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                            return;
                        }


                        if (sabdy()->is_under_distributer) {
                            $valeur_first = $percent_robus->levelDepositIvories->where('is_ivoires', true)->first();
                            $valeur_end = $percent_robus->levelDepositIvories->where('is_ivoires', true)->last();
                            $all_valeurs = $percent_robus->levelDepositIvories->where('is_ivoires', true);
                        } else {

                            $valeur_first = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->first();
                            $valeur_end = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->last();
                            $all_valeurs = $percent_robus->levelDepositIvories->where('is_inter_bank', true);
                        }

                        $reel_amount = $this->search_amount($this->amount_reloadcard);

                        if ($valeur_end->amount_end < $reel_amount) {
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('info', 'Vous avez depasser le montant max !'  . $$valeur_end->amount_end);
                            return;
                        }


                        if ($valeur_first->amount_begin > $reel_amount) {
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('info', 'Vous avez depasser le montant min !' . $valeur_first->amount_begin);
                            return;
                        }



                        foreach ($all_valeurs as $amount_user) {

                            if ($reel_amount >= $amount_user->amount_begin && $reel_amount <= $amount_user->amount_end) {

                                //  If amount is Null

                                if ($amount_user->amount == null) {

                                    $percent_valeur = $amount_user->percent * $reel_amount;

                                    if (sabdy()->is_under_distributer) {

                                        $amount_verify = $reel_amount;
                                        $valuer_deposit_com = $amount_user->comission_distributer * $reel_amount;
                                        $valuer_admin_com = $percent_valeur - $valuer_deposit_com;
                                        $amount_cost = $amount_user->comission_distributer * $reel_amount;
                                        $commissionCompanie = $amount_user->percent_companie * $percent_valeur;
                                        $amountReceved = (float)$reel_amount;
                                    } else {
                                        $amount_verify = $reel_amount;
                                        $valuer_admin_com =  $amount_user->percent * $reel_amount;
                                        $amount_cost = $amount_user->percent * $percent_valeur;
                                        $valuer_deposit_com = 0;
                                        $amountReceved = (float)$reel_amount - (float)$percent_valeur;
                                    }
                                } else {

                                    $percent_valeur = (float)$amount_user->amount + (float)$reel_amount;

                                    if (sabdy()->is_under_distributer) {

                                        $amount_verify =  (float)$reel_amount;
                                        $valuer_deposit_com = $amount_user->comission_distributer;
                                        $valuer_admin_com = $reel_amount - $valuer_deposit_com;
                                        $amount_cost = $amount_user->comission_distributer;
                                        $commissionCompanie = $amount_user->percent_companie;
                                        $amountReceved = (float)$reel_amount;
                                    } else {
                                        $amount_verify = (float)$reel_amount;
                                        $amount_cost = $amount_user->amount;
                                        $valuer_deposit_com = 0;
                                        $valuer_admin_com = (float)$amount_user->amount;
                                        $amountReceved = (float)$reel_amount - (float)$amount_user->amount;
                                    }
                                }


                                $amount_solde =  $walletUser->wallet->amount -  $amount_verify;

                                $soldeWallet = $walletUser->wallet->amount;

                                if ($amount_verify > $soldeWallet) {

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit(
                                        'error',
                                        'Verifier votre montant  svp ou le montant minimum !'
                                    );
                                    return;
                                }

                                if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                                    $walletReloadCard = app(TransfertServiceBackend::class)->createNewReloadCard(
                                        $amount_solde,
                                        $walletUser,
                                        $reel_amount,
                                        $this->reloadcard_description,
                                        $this->account_number_reload_card,
                                        $this->name_reloadcard,
                                        $amount_cost,
                                        $provider,
                                        $amountReceved,
                                    );


                                    $message = 'Vous avez effectué un rechargement  de ' .
                                        $walletReloadCard->amount .
                                        $walletUser->wallet->currencie->code .
                                        ' ' .
                                        'par ' .
                                        $walletReloadCard->provider->name .
                                        ' le ' .
                                        $walletReloadCard->created_at .
                                        ' votre demande est en cours de validation ' .
                                        'Robus vous remercie';

                                    app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);


                                    if ($walletReloadCard != null && $walletReloadCard->status != 'PENDING') {
                                        $this->updateamountReloadCardReceveid($admin_civ, $valuer_deposit_com, $walletReloadCard, $valuer_admin_com, $walletUser, $amount_verify, $commissionCompanie);
                                    }

                                    event(new CreateWalletReloadCardEvent($walletReloadCard));

                                    app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Rechargement de carte', 'Demande de Rechargement de carte est en cours de validation', 'Demande de Rechargement de carte de ' . ' ' . $walletReloadCard->wallet->sidUser->firstname);

                                    if ($admins->count() > 0) {
                                        app(NotificationServiceBackend::class)->notifyAdmins($admins);
                                    }

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit(
                                        'success',
                                        'Votre demande de transaction a bien été prise en compte.'
                                    );

                                    if ($walletUser->email != null) {
                                        app(NotificationServiceBackend::class)->sendMailReloadCardNotification($walletReloadCard);
                                    }
                                } else {

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                                    return; // Arrête l'exécution si le montant est supérieur au solde
                                }
                            }
                        }
                    } else {
                        $this->emit('error', 'Aucune selection !');
                    }
                } else {
                    $this->resetReloadCardField();
                    $this->emit('modal-hide-reloadCard');
                    $this->emit('success', 'Verifiez votre mot de passe svp !');
                }

                DB::commit();
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetReloadCardField();
            $this->emit('modal-hide-reloadCard');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }


    protected function updateamountReloadCardReceveid($admin_civ, $percent_merchands, $walletOrderCard, $amount_commission, $walletUser, $amount_verify, $commissionCompanie)
    {


        if (sabdy()->is_under_distributer) {

            if ($this->showRemonterRechargementButton) {

                $valuer_deposit =  $percent_merchands;

                $walletOrderCard->update([
                    'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                ]);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterRechargementCButton) {
                    $valuer_companie = $commissionCompanie;
                    $walletOrderCard->increment('commission_companie', $valuer_companie);

                    $totalCommission =  $percent_merchands +  $valuer_companie;

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_deposit_id', $walletOrderCard->id, $totalCommission, $amount_commission);
                } else {

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_deposit_id', $walletOrderCard->id, $amount_commission, $valuer_deposit);
                }
            } else {

                $valuer_deposit =  $amount_commission;

                app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_deposit_id', $walletOrderCard->id, $valuer_deposit);
            }
        } else {
            $valuer_deposit =  $amount_commission;

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_deposit_id', $walletOrderCard->id, $valuer_deposit);
        }

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount_verify);


        return true;
    }

    public function resetReloadCardField()
    {
        $this->reloadcard_description = '';
        $this->reloadcardStatus = '';
        $this->provider_reloadcard = null;
        $this->amount_reloadcard = '';
        $this->account_number_reload_card = '';
        $this->name_reloadcard = '';
        $this->stepReloadCard = 1;
        $this->emit('modal-hide-reloadCard-validated');
        $this->emit('menu-reloadCarte-active');
    }

    public function cancelReloadCardField()
    {
        $this->reloadcard_description = '';
        $this->reloadcardStatus = '';
        $this->provider_reloadcard = null;
        $this->amount_reloadcard = '';
        $this->account_number_reload_card = '';
        $this->name_reloadcard = '';
        $this->stepReloadCard = 1;
        $this->emit('modal-hide-reloadCard');
        $this->emit('modal-hide-reloadCard-validated');
        $this->emit('menu-reloadCarte-active');
    }

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }

    // Fonction utilitaire pour calculer les commissions
    private function calculateCommission($levels, $amount)
    {
        $commission_pay = null;
        $commission_receive = null;

        $valeur_end   = $levels->last()?->amount_end;
        $valeur_first = $levels->first()?->amount_begin;


        if ($valeur_first !== null && $valeur_first > $amount) {
            $commission_pay = $commission_receive = "Montant min est : " . number_format($valeur_first, 2, ',', ' ');
            return [$commission_pay, $commission_receive];
        }

        if ($valeur_end !== null && $valeur_end < $amount) {
            $commission_pay = $commission_receive = "Montant maxi est : " . number_format($valeur_end, 2, ',', ' ');
            return [$commission_pay, $commission_receive];
        }


        foreach ($levels as $level) {
            if ($amount >= $level->amount_begin && $amount <= $level->amount_end) {

                if ($level->amount !== null) {
                    $commission_pay = (float) $level->amount;
                } else {

                    $commission_pay = (float) ($level->percent * $amount);


                    if (isset($level->admin_percent) && isset($level->comission_distributer)) {
                        $percent_admin = $level->percent * $amount;
                        $admin_valeur  = $percent_admin * $level->admin_percent;
                        $commission_pay = $level->comission_distributer * $amount;
                    }
                }

                $commission_receive = $amount < 0 ? 'Montant négatif' : (float) $amount;

                break;
            }
        }

        return [$commission_pay, $commission_receive];
    }



    public function render()
    {
        $walletUser = Auth::guard('web')->user();
        $commission_reload_val_nat_true_pay = null;
        $commission_reload_val_nat_true_receive = null;

        if (!empty($this->amount_reloadcard)) {
            $commission_reload = DepositCost::find(2);

            if ($walletUser->wallet->sidUser->is_under_distributer) {
                $levels = $commission_reload->levelDepositIvories->where('is_ivoires', true);
            } else {
                $levels = $commission_reload->levelDepositIvories->where('is_inter_bank', true);
            }

            [$commission_reload_val_nat_true_pay, $commission_reload_val_nat_true_receive] =
                $this->calculateCommission($levels, $this->amount_reloadcard);
        }
        
        return view(
            'livewire.transferts.transfert-reload-card',
            [
                'amount_deposit_nation_pay' => $commission_reload_val_nat_true_pay,
                'amount_deposit_nation_receive' => $commission_reload_val_nat_true_receive,
                'amount_deposit_nation_true_pay' => $commission_reload_val_nat_true_pay,
                'amount_deposit_nation_true_receive' => $commission_reload_val_nat_true_receive,
                'showRemonterRechargementButton' => $this->showRemonterRechargementButton,
                'user_auth' => $walletUser,
                'usersCountry' => sabdy()->wallet->currencie->code,
                'provider_reloads' => $this->ReloadTransaction,

            ]
        );
    }

    public function getReloadTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $reload_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 5)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $reload_transaction;
    }
}
