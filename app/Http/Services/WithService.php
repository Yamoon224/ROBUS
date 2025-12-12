<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Invoices\OneBetInvoice;
use App\Models\Payments\OneBetTransaction;
use App\Models\Sid\SidUser;
use App\Repositories\TresortRepository;
use App\Repositories\PushRepository;
use App\Repositories\DjamoRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Services\OperatorRestrictionService;
use Illuminate\Support\Facades\Http;

class WithService
{

    /** @var  TresortRepository */
    public $tresortRepository;
    public $djamoRepository;
    public $pushRepository;
    public $operatorRestrictionService;
    protected $commissionServices;

    public function __construct(
        TresortRepository $tresortRepository,
        PushRepository $pushRepository,
        DjamoRepository $djamoRepository,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices,
    ) {
        $this->tresortRepository = $tresortRepository;
        $this->djamoRepository = $djamoRepository;
        $this->pushRepository = $pushRepository;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
    }


    public function createWalletTransactionTresortMoney($amount_valeur, $provider, $percent_robus, $phone, $fullName, $amountReceved)
    {


        $user = auth_user();

        if (!$user || !$user->wallet) {
            throw new \Exception('Utilisateur ou wallet non disponible.');
        }

        $amount_solde = (float) $user->wallet->amount;


        return $this->tresortRepository->create([
            'wallet_id' =>  $user->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande de transaction Tresort Money',
            'argent_tresort_id' => 1,
            'created_by' => $user->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            // 'choose_type_transaction' => 1,
            'phone'  => robus()->country->indecative . $phone,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }


    public function createWalletTransactionDjamo($amount_valeur, $provider, $percent_robus, $phone, $fullName, $amountReceved,  $imagePath)
    {


        $user = auth_user();

        if (!$user || !$user->wallet) {
            throw new \Exception('Utilisateur ou wallet non disponible.');
        }

        $amount_solde = (float) $user->wallet->amount;


        return $this->djamoRepository->create([
            'wallet_id' =>  $user->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande  de transaction Djamo',
            'djamo_percent_id' => 1,
            'created_by' => $user->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'is_livewire' => false,
            'phone'  => robus()->country->indecative . $phone,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
            'image'  =>  $imagePath ?? null,
        ]);
    }



    public function createWalletTransactionPush($amount_valeur, $provider, $percent_robus, $phone, $fullName, $amountReceved)
    {



        $user = auth_user();

        if (!$user || !$user->wallet) {
            throw new \Exception('Utilisateur ou wallet non disponible.');
        }

        $amount_solde = (float) $user->wallet->amount;


        return $this->pushRepository->create([
            'wallet_id' =>  $user->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande  de transaction Push',
            'push_percent_id' => 1,
            'created_by' => $user->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'phone'  => robus()->country->indecative . $phone,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }



    public function updateamountPushReceveid($admin_civ, $percent_robus, $walletWith, $amount)
    {

        $user = auth_user();

        $userId = $user->id;

        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = $user->is_accepted_companie ?? false;
        $company_id      = $user->company_id ?? null;

        if ($company_id) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }

        if ($sidUserId) {
            $activeCButtons = $this->operatorRestrictionService->getActiveCButtons($sidUserId);
        } else {
            $activeCButtons = [];
        }
        if ($user && $user->is_under_distributer || $user->is_api) {
            $amount_commission = $amount;
        } else {
            $amount_commission = $amount + ($percent_robus->percent * $amount);
        }

        $amount_solde = $user->wallet->amount - $amount_commission;

        $walletWith->update([
            'amount_solde' => $amount_solde,
        ]);

        $user->wallet->update([
            'reload_by' => $user->firstname . ' ' . $user->lastname,
            'sid_user_relod' => $user->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $valuer_deposit = $percent_robus->percent_merchands *  $amount;
        $percent_valeur_compagnie = $percent_robus->percent_companie *  $amount;
        $valeur_percent_no_under  = $percent_robus->percent * $amount;

        // Commissions Inters 
        if ($user && $user->is_under_distributer || $user->is_api == 1) {

            $valeur_amount_admin  = $percent_robus->percent_admin * $amount;
            $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;

            if (!empty($activeButtons['showRemonterPushButton'])) {

                $walletWith->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = $user->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterPushCButton'])) {
                    $walletWith->increment('commission_companie', $percent_valeur_compagnie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_push_id', $walletWith->id, $percent_valeur_compagnie, $valuer_deposit);
                }
                else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ,  'wallet_push_id',  $walletWith->id, $valeur_percent_no_under, $valuer_deposit);
                }
            } else {

                $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_push_id', $walletWith->id, $valeur_amount_admin, $valuer_deposit);
            }
        } else {


            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_push_id', $walletWith->id, $valeur_percent_no_under);

            if ($user->country->code == 'CI') {

                $message =  'Vous avez effectué un paiement de ' . $walletWith->amount .  $user->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletWith->provider->name .
                    ' a ' . $walletWith->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                $encode = substr($user->phone, -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        return true;
    }

    public function updateamountDjamoReceveid($admin_civ, $percent_robus, $walletWith, $amount)
    {

        $user = auth_user();
        $userId = $user->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = $user->is_accepted_companie ?? false;
        $company_id      = $user->company_id ?? null;

        if ($company_id) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }

        if ($sidUserId) {
            $activeCButtons = $this->operatorRestrictionService->getActiveCButtons($sidUserId);
        } else {
            $activeCButtons = [];
        }
        if ($user && $user->is_under_distributer || $user->is_api) {
            $amount_commission = $amount;
        } else {
            $amount_commission = $amount + ($percent_robus->percent * $amount);
        }

        $amount_solde = $user->wallet->amount - $amount_commission;

        $walletWith->update([
            'amount_solde' => $amount_solde,
        ]);

        $user->wallet->update([
            'reload_by' => $user->firstname . ' ' . $user->lastname,
            'sid_user_relod' => $user->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $valuer_deposit = $percent_robus->percent_merchands *  $amount;
        $percent_valeur_compagnie = $percent_robus->percent_companie *  $amount;
        $valeur_percent_no_under  = $percent_robus->percent * $amount;

        // Commissions Inters 
        if ($user && $user->is_under_distributer || $user->is_api == 1) {

            $valeur_amount_admin  = $percent_robus->percent_admin * $amount;
            $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;

            if (!empty($activeButtons['showRemonterDjamoButton'])) {

                $walletWith->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = $user->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterDjamoCButton'])) {
                    $walletWith->increment('commission_companie', $percent_valeur_compagnie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_djamo_id', $walletWith->id, $percent_valeur_compagnie, $valuer_deposit);
                }else {
                   $this->commissionServices->distributeAdminCommissionUnders($admin_civ, 'wallet_djamo_id', $walletWith->id, $valuer_deposit);  
                }
            } else {
                $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_djamo_id', $walletWith->id, $valeur_amount_admin, $valuer_deposit);
            }
        } else {


            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_djamo_id', $walletWith->id, $valeur_percent_no_under);

            if ($user->country->code == 'CI') {

                $message =  'Vous avez effectué un réchargement de ' . $walletWith->amount .  $user->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletWith->provider->name .
                    ' a ' . $walletWith->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                $encode = substr($user->phone, -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }


        return true;
    }

    public function updateamountTresortReceveid($admin_civ, $percent_robus, $walletWith, $amount)
    {

        $user = auth_user();
        $userId = $user->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = $user->is_accepted_companie ?? false;
        $company_id      = $user->company_id ?? null;

        if ($company_id) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }

        if ($sidUserId) {
            $activeCButtons = $this->operatorRestrictionService->getActiveCButtons($sidUserId);
        } else {
            $activeCButtons = [];
        }
        if ($user && $user->is_under_distributer || $user->is_api) {
            $amount_commission = $amount;
        } else {
            $amount_commission = $amount + ($percent_robus->percent * $amount);
        }

        $amount_solde = $user->wallet->amount - $amount_commission;

        $walletWith->update([
            'amount_solde' => $amount_solde,
        ]);

        $user->wallet->update([
            'reload_by' => $user->firstname . ' ' . $user->lastname,
            'sid_user_relod' => $user->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $valuer_deposit = $percent_robus->percent_merchands *  $amount;
        $percent_valeur_compagnie = $percent_robus->percent_companie *  $amount;
        $valeur_percent_no_under  = $percent_robus->percent * $amount;

        // Commissions Inters 
        if ($user && $user->is_under_distributer || $user->is_api == 1) {

            $valeur_amount_admin  = $percent_robus->percent_admin * $amount;
            $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;

            if (!empty($activeButtons['showRemonterTresortButton'])) {


                $walletWith->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = $user->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterTresortCButton'])) {
                    $walletWith->increment('commission_companie', $percent_valeur_compagnie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_tresort_money_id', $walletWith->id, $percent_valeur_compagnie, $valuer_deposit);
                }else {
                   $this->commissionServices->distributeAdminCommissionUnders($admin_civ, 'wallet_tresort_money_id', $walletWith->id, $valuer_deposit);  
                }
            } else {

                $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_tresort_money_id', $walletWith->id, $valeur_amount_admin, $valuer_deposit);
            }
        } else {

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_tresort_money_id', $walletWith->id, $valeur_percent_no_under);

            if ($user->country->code == 'CI') {

                $message =  'Vous avez effectué un paiement de ' . $walletWith->amount .  $user->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletWith->provider->name .
                    ' a ' . $walletWith->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                $encode = substr($user->phone, -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        return true;
    }
}
