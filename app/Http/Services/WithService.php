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

    public function __construct(
        TresortRepository $tresortRepository,
        PushRepository $pushRepository,
        DjamoRepository $djamoRepository,
        OperatorRestrictionService $operatorRestrictionService,
    ) {
        $this->tresortRepository = $tresortRepository;
        $this->djamoRepository = $djamoRepository;
        $this->pushRepository = $pushRepository;
        $this->operatorRestrictionService = $operatorRestrictionService;
    }


    public function createWalletTransactionTresortMoney($amount_valeur, $provider, $percent_robus, $phone, $fullName, $amountReceved)
    {


        $amount_solde = (float)robus()->wallet->amount;


        return $this->tresortRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande  de transaction Tresort Money',
            'PushPercent_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'choose_type_transaction' => 1,
            'phone'  =>  $phone,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }


    public function createWalletTransactionDjamo($amount_valeur, $provider, $percent_robus, $phone, $fullName, $amountReceved,  $imagePath)
    {


        $amount_solde = (float)robus()->wallet->amount;


        return $this->djamoRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande  de transaction Djamo',
            'DjamoPercent_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'choose_type_transaction' => 1,
            'phone'  =>  $phone,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
            'image'  =>  $imagePath ?? null,
        ]);
    }



    public function createWalletTransactionPush($amount_valeur, $provider, $percent_robus, $phone, $fullName, $amountReceved)
    {


        $amount_solde = (float)robus()->wallet->amount;


        return $this->pushRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande  de transaction Push',
            'PushPercent_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'choose_type_transaction' => 1,
            'phone'  =>  $phone,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }



    public function updateamountPushReceveid($admin_civ, $percent_robus, $walletWith, $amount)
    {

        $userId = robus()->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = robus()->is_accepted_companie ?? false;
        $company_id = robus()->company_id ?? null;

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
        if (robus()->is_under_distributer) {
            $amount_commission = $amount;
        } else {
            $amount_commission = $amount + ($percent_robus->percent * $amount);
        }

        $amount_solde = robus()->wallet->amount - $amount_commission;

        $walletWith->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $valuer_deposit = $percent_robus->percent_merchands *  $amount;
        $percent_valeur_compagnie = $percent_robus->percent_companie *  $amount;

        // Commissions Inters 
        if (robus()->is_under_distributer == 1) {

            $valeur_amount_admin  = $percent_robus->percent_admin * $amount;
            $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;

            if (!empty($activeButtons['showRemonterPushButton'])) {

                $walletWith->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterPushCButton'])) {
                    $walletWith->increment('commission_companie', $percent_valeur_compagnie);
                }
            } else {
                $valeur_percent_admin  = $percent_robus->percent * $amount;
            }
        } else {

            if (robus()->country->code == 'CI') {

                $message =  'Vous avez effectué un paiement de ' . $walletWith->amount .  robus()->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletWith->provider->name .
                    ' a ' . $walletWith->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                $encode = substr(robus()->phone, -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        $admin_valeur_convertion =   (float)  $valeur_percent_admin;


        if ($admin_civ->count() > 0) {

            $nmbre_admin = $admin_civ->count();

            $valeur_single = $admin_valeur_convertion / $nmbre_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_push_id' =>  $walletWith->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $valuer_deposit,
                    'description_underdistributer' => 'Paiement commission sous-distributer'
                ]);
            }
        }

        return true;
    }

    public function updateamountDjamoReceveid($admin_civ, $percent_robus, $walletWith, $amount)
    {

        $userId = robus()->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = robus()->is_accepted_companie ?? false;
        $company_id = robus()->company_id ?? null;

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
        if (robus()->is_under_distributer) {
            $amount_commission = $amount;
        } else {
            $amount_commission = $amount + ($percent_robus->percent * $amount);
        }

        $amount_solde = robus()->wallet->amount - $amount_commission;

        $walletWith->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $valuer_deposit = $percent_robus->percent_merchands *  $amount;
        $percent_valeur_compagnie = $percent_robus->percent_companie *  $amount;

        // Commissions Inters 
        if (robus()->is_under_distributer == 1) {

            $valeur_amount_admin  = $percent_robus->percent_admin * $amount;
            $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;

            if (!empty($activeButtons['showRemonterDjamoButton'])) {

                $walletWith->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterDjamoCButton'])) {
                    $walletWith->increment('commission_companie', $percent_valeur_compagnie);
                }
            } else {
                $valeur_percent_admin  = $percent_robus->percent * $amount;
            }
        } else {

            if (robus()->country->code == 'CI') {

                $message =  'Vous avez effectué un réchargement de ' . $walletWith->amount .  robus()->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletWith->provider->name .
                    ' a ' . $walletWith->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                $encode = substr(robus()->phone, -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        $admin_valeur_convertion =   (float)  $valeur_percent_admin;


        if ($admin_civ->count() > 0) {

            $nmbre_admin = $admin_civ->count();

            $valeur_single = $admin_valeur_convertion / $nmbre_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_djamo_id' =>  $walletWith->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $valuer_deposit,
                    'description_underdistributer' => 'Paiement commission sous-distributer'
                ]);
            }
        }

        return true;
    }

    public function updateamountTresortReceveid($admin_civ, $percent_robus, $walletWith, $amount)
    {

        $userId = robus()->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = robus()->is_accepted_companie ?? false;
        $company_id = robus()->company_id ?? null;

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
        if (robus()->is_under_distributer) {
            $amount_commission = $amount;
        } else {
            $amount_commission = $amount + ($percent_robus->percent * $amount);
        }

        $amount_solde = robus()->wallet->amount - $amount_commission;

        $walletWith->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $valuer_deposit = $percent_robus->percent_merchands *  $amount;
        $percent_valeur_compagnie = $percent_robus->percent_companie *  $amount;

        // Commissions Inters 
        if (robus()->is_under_distributer == 1) {

            $valeur_amount_admin  = $percent_robus->percent_admin * $amount;
            $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;

            if (!empty($activeButtons['showRemonterTresortButton'])) {

                $walletWith->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterTresortCButton'])) {
                    $walletWith->increment('commission_companie', $percent_valeur_compagnie);
                }
            } else {
                $valeur_percent_admin  = $percent_robus->percent * $amount;
            }
        } else {

            if (robus()->country->code == 'CI') {

                $message =  'Vous avez effectué un paiement de ' . $walletWith->amount .  robus()->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletWith->provider->name .
                    ' a ' . $walletWith->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                $encode = substr(robus()->phone, -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        $admin_valeur_convertion =   (float)  $valeur_percent_admin;


        if ($admin_civ->count() > 0) {

            $nmbre_admin = $admin_civ->count();

            $valeur_single = $admin_valeur_convertion / $nmbre_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_tresort_money_id' =>  $walletWith->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $valuer_deposit,
                    'description_underdistributer' => 'Paiement commission sous-distributer'
                ]);
            }
        }

        return true;
    }

}
