<?php

namespace App\Http\Services;



use App\Models\Wallets\WalletBankSend;
use App\Models\Sid\SidUser;
use App\Repositories\TresortRepository;
use App\Repositories\PushRepository;
use App\Repositories\DjamoRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Services\OperatorRestrictionService;
use Illuminate\Support\Facades\Http;
use App\Models\Custumers\Country;

class WithService
{

    /** @var  TresortRepository */
    public $tresortRepository;
    public $djamoRepository;
    public $pushRepository;
    public $operatorRestrictionService;
    protected $commissionServices;
    protected $convertionservice;

    public function __construct(
        TresortRepository $tresortRepository,
        PushRepository $pushRepository,
        DjamoRepository $djamoRepository,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices,
        ConvertionService $convertionservice,

    ) {
        $this->tresortRepository = $tresortRepository;
        $this->djamoRepository = $djamoRepository;
        $this->pushRepository = $pushRepository;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
        $this->convertionservice = $convertionservice;
    }


    public function createWalletTransactionTresortMoney($amount_valeur, $provider, $percent_robus, $phone, $fullName, $amountReceved, $is_drawal, $type, $numberPiece)
    {


        $user = auth_user();

        if (!$user || !$user->wallet) {
            throw new \Exception('Utilisateur ou wallet non disponible.');
        }

        $amount_solde = (float) $user->wallet->amount;

        $percent = $is_drawal
            ? $percent_robus->percent_with_drawal
            : $percent_robus->percent;


        return $this->tresortRepository->create([
            'wallet_id' =>  $user->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande de transaction Tresort Money',
            'argent_tresort_id' => 1,
            'created_by' => $user->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent * $amount_valeur),
            'choose_transaction'  => $is_drawal ? 2 : 1,
            'phone'  =>  $phone,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
            'type_piece'  =>  $type ?? null,
            'number_piece'  =>  $numberPiece ?? null,
        ]);
    }


    public function createWalletTransactionDjamo($amount_valeur, $provider, $percent_robus, $phone, $fullName, $amountReceved,  $imagePath, $is_drawal, $type, $numberPiece)
    {


        $user = auth_user();

        if (!$user || !$user->wallet) {
            throw new \Exception('Utilisateur ou wallet non disponible.');
        }

        $amount_solde = (float) $user->wallet->amount;

        $percent = $is_drawal
            ? $percent_robus->percent_with_drawal
            : $percent_robus->percent;


        return $this->djamoRepository->create([
            'wallet_id' =>  $user->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande  de transaction Djamo',
            'djamo_percent_id' => 1,
            'created_by' => $user->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent * $amount_valeur),
            'is_livewire' => false,
            'phone'  => $phone,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'choose_transaction'  => $is_drawal ? 2 : 1,
            'amount_received'  =>  $amountReceved ?? 0.0,
            'image'  =>  $imagePath ?? null,
            'type_piece'  =>  $type ?? null,
            'number_piece'  =>  $numberPiece ?? null,
        ]);
    }



    public function createWalletTransactionPush($amount_valeur, $provider, $percent_robus, $phone, $fullName, $amountReceved, $is_drawal, $type, $numberPiece)
    {


        $user = auth_user();

        if (!$user || !$user->wallet) {
            throw new \Exception('Utilisateur ou wallet non disponible.');
        }

        $amount_solde = (float) $user->wallet->amount;
        $percent = $is_drawal
            ? $percent_robus->percent_with_drawal
            : $percent_robus->percent;

        return $this->pushRepository->create([
            'wallet_id' =>  $user->wallet->id,
            'amount' => $amount_valeur,
            'description'  => $is_drawal ? 'Demande  de retrait Push' : 'Demande  de transaction Push',
            'push_percent_id' => 1,
            'created_by' => $user->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent * $amount_valeur),
            'phone'  => $phone,
            'full_name' =>  $fullName,
            'choose_transaction'  => $is_drawal ? 2 : 1,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
            'type_piece'  =>  $type ?? null,
            'number_piece'  =>  $numberPiece ?? null,
        ]);
    }


    public function createWalletTransactionBanSend(
        $amount_transaction,
        $percent_robus,
        $country_robus,
        $provider_payment,
        $phone,
        $name_custumers,
        $num_piece_custumers,
        $date_piece,
        $name,
        $name_sender,
        $account_number,
        $phone_inter_expe,
        $swit_code,
        $imagePath,
        $imageBackendPath,
        $type_piece
    ) {

        $user = auth_user();

        if (!$user || !$user->wallet) {
            throw new \Exception('Utilisateur ou wallet non disponible.');
        }

        $amount_solde = (float) $user->wallet->amount;

        $amount_cost_conversion =  $this->convertionservice->convertAdminCommissionPayment($percent_robus->amount);

        return WalletBankSend::create([
            'wallet_id' => robus()->wallet->id,
            'amount' => $amount_transaction,
            'description' => "Demande de transaction Bank send",
            'bank_cost_id' => $percent_robus->id,
            'phone' => $country_robus->indecative . $phone ?? null,
            'number_compte' => $account_number,
            'created_by' => robus()->id,
            'provider_id' => $provider_payment,
            'name_complet' => $name_custumers ?? null,
            'name' => $name ?? null,
            'num_piece' => $num_piece_custumers ?? null,
            'name_benef' => $name_sender ?? null,
            'phone_inter_expe' => $phone_inter_expe ?? null,
            'swit_code' => $swit_code ?? null,
            'validated_date' => $date_piece ?? null,
            'costs_amount' => robus()->is_under_distributer ? ($amount_cost_conversion) : ($amount_cost_conversion),
            'provider_type' => 1,
            'status' => 'PENDING',
            'countrie_id' => $country_robus->id,
            'choose_type_transaction' => 1,
            'amount_solde' => $amount_solde ?? null,
            'image' => $imagePath ?? null,
            'image_bakend' => $imageBackendPath ?? null,
            'type_piece' => $type_piece ?? null,
        ]);
    }

    public function updateamountPushReceveid($admin_civ, $percent_robus, $walletWith, $amount, $is_drawal)
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

        $percent = $is_drawal
            ? $percent_robus->percent_with_drawal
            : $percent_robus->percent;


        $percent_commission = $is_drawal
            ? $percent_robus->percent_with_drawal_merchands
            : $percent_robus->percent_merchands;

        $percent_compagnie = $is_drawal
            ? $percent_robus->percent_with_drawal_companie
            : $percent_robus->percent_companie;

        if ($user && $user->is_under_distributer || $user->is_api) {
            $amount_commission = $amount;
        } else {
            $amount_commission = $amount;
        }

        if ($is_drawal) {
            $amount_solde = $user->wallet->amount;
        } else {
            $amount_solde = $user->wallet->amount - $amount_commission;
        }

        $walletWith->update([
            'amount_solde' => $amount_solde,
            'balance_before' => $user->wallet->amount
        ]);

        if (!$is_drawal) {
            $user->wallet->update([
                'reload_by' => $user->firstname . ' ' . $user->lastname,
                'sid_user_relod' => $user->id,
                'amount' => DB::raw('amount-'  .  $amount_commission),
            ]);
        }

        $valuer_deposit = $percent_commission *  $amount;
        $percent_valeur_compagnie = $percent_compagnie *  $amount;
        $valeur_percent_no_under  = $percent * $amount;

        // Commissions Inters 
        if ($user && $user->is_under_distributer || $user->is_api == 1) {


            if (!empty($activeButtons['showRemonterPushButton'])) {

                $walletWith->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = $user->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterPushCButton'])) {
                    $walletWith->increment('commission_companie', $percent_valeur_compagnie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_push_id', $walletWith->id, $percent_valeur_compagnie, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionNoAccepteds($admin_civ,  'wallet_push_id',  $walletWith->id, $valuer_deposit);
                }
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

                sendTryCashMessage($user->phone, $message);
            }
        }

        return true;
    }

    public function updateamountReceveidBankSend($admin_civ, $percent_robus, $walletWith, $amount, $amountReceveid)
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

        $countryBank = Country::findOrFail(107);
        $userRate = robus()->country->currencie->value;
        $bankRate = $countryBank->currencie->value;


        $percent_commission =  $percent_robus->percent_under;

        $percent_compagnie = $percent_robus->percent_companie;

        $percent_amount_admin = $percent_robus->percent_admin;

        $percent_amount_bank = $percent_robus->percent_bank;

        if ($user && $user->is_under_distributer || $user->is_api) {
            $amount_commission = $amount;
        } else {
            $amount_commission = $amount;
        }

        $amount_solde = $user->wallet->amount - $amount_commission;

        $walletWith->update([
            'amount_solde' => $amount_solde,
            'amount_received' => $amountReceveid,
            'balance_before' => $user->wallet->amount
        ]);

        $user->wallet->update([
            'reload_by' => $user->firstname . ' ' . $user->lastname,
            'sid_user_relod' => $user->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $valuer_deposit = $percent_commission;
        $percent_valeur_compagnie = $percent_compagnie;

        // Commissions Inters 
        if ($user && $user->is_under_distributer || $user->is_api == 1) {

            if (!empty($activeButtons['showRemonterBankSendButton'])) {



                $amountUnderDeposit = $this->convertionservice->convertAdminCommissionInvoice($valuer_deposit);

                $walletWith->update([
                    'commissions' => DB::raw('COALESCE(commissions, 0) + ' . (float) $amountUnderDeposit),
                ]);

                $account_accepted = $user->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterBankSendCButton'])) {
                    $amountCompanieDeposit = $this->convertionservice->convertAdminCommissionInvoice($percent_valeur_compagnie);

                    $walletWith->increment('commission_companie', $amountCompanieDeposit);
                    $this->commissionServices->distributeAdminCommissionUnderBankAcceptedCompanies($admin_civ, 'wallet_bank_send_id', $walletWith->id,  $percent_amount_admin, $percent_valeur_compagnie, $valuer_deposit, $percent_amount_bank);
                } else {
                    $admin_cumul_amount = (float) $percent_amount_admin  +  (float) $percent_compagnie;
                    $this->commissionServices->distributeAdminNoCompagnieBankSendCommissions($admin_civ, 'wallet_bank_send_id', $walletWith->id, $admin_cumul_amount, $valuer_deposit, $percent_amount_bank);
                }
            } else {
                $percent_admin = (float) $percent_robus->percent_admin + (float) $percent_robus->percent_companie + (float) $percent_robus->percent_under;
                $this->commissionServices->distributeAdminAndBankCommissionAccepteds($admin_civ, 'wallet_bank_send_id', $walletWith->id, $percent_admin, $valuer_deposit, $percent_amount_bank);
            }
        } else {
            $percent_admin = (float) $percent_robus->percent_admin + (float) $percent_robus->percent_companie + (float) $percent_robus->percent_under;
            $this->commissionServices->distributeAdminAndBankCommissionAccepteds($admin_civ, 'wallet_bank_send_id', $walletWith->id, $percent_admin, $valuer_deposit, $percent_amount_bank);

            if ($user->country->code == 'CI') {

                $message =  'Vous avez effectué un paiement de ' . $walletWith->amount .  $user->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletWith->provider->name .
                    ' a ' . $walletWith->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                sendTryCashMessage($user->phone, $message);
            }
        }

        return true;
    }

    public function updateamountDjamoReceveid($admin_civ, $percent_robus, $walletWith, $amount, $is_drawal)
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
            $amount_commission = $amount;
        }

        if ($is_drawal) {
            $amount_solde = $user->wallet->amount;
        } else {
            $amount_solde = $user->wallet->amount - $amount_commission;
        }

        $walletWith->update([
            'amount_solde' => $amount_solde,
            'balance_before' => $user->wallet->amount
        ]);

        if (!$is_drawal) {

            $user->wallet->update([
                'reload_by' => $user->firstname . ' ' . $user->lastname,
                'sid_user_relod' => $user->id,
                'amount' => DB::raw('amount-'  .  $amount_commission),
            ]);
        }

        $percent = $is_drawal
            ? $percent_robus->percent_with_drawal
            : $percent_robus->percent;


        $percent_commission = $is_drawal
            ? $percent_robus->percent_with_drawal_merchands
            : $percent_robus->percent_merchands;

        $percent_compagnie = $is_drawal
            ? $percent_robus->percent_with_drawal_companie
            : $percent_robus->percent_companie;

        $valuer_deposit = $percent_commission *  $amount;
        $percent_valeur_compagnie = $percent_compagnie *  $amount;
        $valeur_percent_no_under  = $percent * $amount;

        // Commissions Inters 
        if ($user && $user->is_under_distributer || $user->is_api == 1) {

            if (!empty($activeButtons['showRemonterDjamoButton'])) {

                $walletWith->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = $user->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterDjamoCButton'])) {
                    $walletWith->increment('commission_companie', $percent_valeur_compagnie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_djamo_id', $walletWith->id, $percent_valeur_compagnie, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionUnders($admin_civ, 'wallet_djamo_id', $walletWith->id, $valuer_deposit);
                }
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

                sendTryCashMessage($user->phone, $message);
            }
        }


        return true;
    }

    public function updateamountTresortReceveid($admin_civ, $percent_robus, $walletWith, $amount, $is_drawal)
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
            $amount_commission = $amount;
        }

        if ($is_drawal) {
            $amount_solde = $user->wallet->amount;
        } else {
            $amount_solde = $user->wallet->amount - $amount_commission;
        }

        $walletWith->update([
            'amount_solde' => $amount_solde,
            'balance_before' => $user->wallet->amount
        ]);

        if (!$is_drawal) {
            $user->wallet->update([
                'reload_by' => $user->firstname . ' ' . $user->lastname,
                'sid_user_relod' => $user->id,
                'amount' => DB::raw('amount-'  .  $amount_commission),
            ]);
        }

        $percent = $is_drawal
            ? $percent_robus->percent_with_drawal
            : $percent_robus->percent;


        $percent_commission = $is_drawal
            ? $percent_robus->percent_with_drawal_merchands
            : $percent_robus->percent_merchands;

        $percent_compagnie = $is_drawal
            ? $percent_robus->percent_with_drawal_companie
            : $percent_robus->percent_companie;

        $valuer_deposit = $percent_commission *  $amount;
        $percent_valeur_compagnie = $percent_compagnie *  $amount;
        $valeur_percent_no_under  = $percent * $amount;

        // Commissions Inters 
        if ($user && $user->is_under_distributer || $user->is_api == 1) {


            if (!empty($activeButtons['showRemonterTresortButton'])) {


                $walletWith->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = $user->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterTresortCButton'])) {
                    $walletWith->increment('commission_companie', $percent_valeur_compagnie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_tresort_money_id', $walletWith->id, $percent_valeur_compagnie, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionUnders($admin_civ, 'wallet_tresort_money_id', $walletWith->id, $valuer_deposit);
                }
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

                sendTryCashMessage($user->phone, $message);
            }
        }

        return true;
    }
}
