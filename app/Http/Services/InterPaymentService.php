<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use Illuminate\Support\Facades\Http;
use App\Models\Invoices\InvoiceInter;
use App\Events\CreateWalletInterEvent;
use App\Repositories\IntersRepository;
use App\Notifications\UserNotification;
use App\Http\Services\ConvertionService;
use App\Notifications\AdminNotification;
use App\Models\Payments\TransactionInter;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;
use App\Http\Services\ValidateDailyLimitService;
use App\Http\Services\OperatorRestrictionService;
use App\Http\Services\CommissionService;

class InterPaymentService
{

    /** @var  IntersRepository */
    public $intersRepository;
    public $convertionService;
    public $validatedamountDays;
    public $operatorRestrictionService;
    protected $commissionServices;

    public function __construct(
        IntersRepository $inter_Reposi,
        ConvertionService $convertionService,
        ValidateDailyLimitService $validatedamountDays,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices

    ) {
        $this->intersRepository = $inter_Reposi;
        $this->convertionService = $convertionService;
        $this->validatedamountDays = $validatedamountDays;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
    }

    public function updateAmountReceived($walletInter, $country_robus, $valeur_received)
    {

        if (robus()->country->currencie->value > $country_robus->currencie->value) {


            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) robus()->country->currencie->value;

            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        } elseif (robus()->country->currencie->value == $country_robus->currencie->value) {

            $commission_transaction_val_inter_convertion_send = (float)   $valeur_received;
        } elseif (robus()->country->currencie->value < $country_robus->currencie->value) {

            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) robus()->country->currencie->value;

            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        }

        if ($commission_transaction_val_inter_convertion_send) {

            $walletInter->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            return true;
        } else {
            return false;
        }
    }

    public function processCommissionMobiles($amount, $percent_robus, $country_robus, $walletInter, $admin_civ)
    {


        $wallet = robus()->wallet;
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

        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount - ' . $amount),
        ];

        $wallet->update($updatedValues);


        $valuer_deposit = $percent_robus->percent_deposit_under_distributer *  $amount;
        $percent_valeur_compagnie = $percent_robus->percent_companie *  $amount;

        // Commissions Inters 
        if (robus()->is_under_distributer == 1 || robus()->is_api == 1) {

            $valeur_amount_admin  = $percent_robus->percent_under_distributer * $amount;
            $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;
            $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);
            $deposit_valeur_convertion =  $this->convertionService->convertAdminCommission($valuer_deposit);

            if (!empty($activeButtons['showRemonterInterButton'])) {

                $walletInter->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterInterCButton'])) {
                    $walletInter->increment('commission_companie', $percent_valeur_compagnie);
                    $company_valeur_convertion =  $this->convertionService->convertAdminCommission($percent_valeur_compagnie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ,  'wallet_inter_id',  $walletInter->id, $company_valeur_convertion, $deposit_valeur_convertion);
                }
            } else {
                $valeur_percent_admin  = $percent_robus->percent_admin * $amount;
                $valeur_percent_banque  = $percent_robus->percent_banque * $amount;
                $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);
                $banque_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_banque);
                $this->commissionServices->distributeAdminBanqueCommissions($admin_civ, 'wallet_inter_id',  $walletInter->id, $admin_valeur_convertion, $valeur_percent_banque, $banque_valeur_convertion);
            }
        } else {

            $valeur_percent_admin  = $percent_robus->percent_admin * $amount;
            $valeur_percent_banque  = $percent_robus->percent_banque * $amount;
            $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);
            $banque_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_banque);
            $this->commissionServices->distributeAdminBanqueCommissions($admin_civ, 'wallet_inter_id',  $walletInter->id, $admin_valeur_convertion, $valeur_percent_banque, $banque_valeur_convertion);
        }

        return true;
    }

    public function processCommissionWithMobiles($amount, $percent_robus, $country_robus, $walletInter, $admin_civ)
    {


        $wallet = robus()->wallet;
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

        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount - ' . $amount),
        ];

        $wallet->update($updatedValues);


        $valuer_deposit = $percent_robus->percent_merchands *  $amount;
        $percent_valeur_compagnie = $percent_robus->percent_companie *  $amount;


        // Commissions Inters 
        if (robus()->is_under_distributer == 1 || robus()->is_api == 1) {

            $valeur_amount_admin  = $percent_robus->percent_admin * $amount;
            $valeur_percent_admin =  (float) $valeur_amount_admin - (float) $valuer_deposit;
            $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);

            if (!empty($activeButtons['showRemonterInterButton'])) {

                $walletInter->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;
                $deposit_valeur_convertion =  $this->convertionService->convertAdminCommission($valuer_deposit);

                if ($account_accepted && !empty($activeCButtons['showRemonterInterCButton'])) {
                    $walletInter->increment('commission_companie', $percent_valeur_compagnie);
                    $company_valeur_convertion =  $this->convertionService->convertAdminCommission($percent_valeur_compagnie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ,  'wallet_inter_id',  $walletInter->id, $company_valeur_convertion, $deposit_valeur_convertion);
                }
            } else {
                $valeur_percent_admin  = $percent_robus->percent * $amount;
                $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);
                $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ,  'wallet_inter_id',  $walletInter->id, $admin_valeur_convertion, $valuer_deposit);
            }
        } else {

            $valeur_percent_admin  = $percent_robus->percent * $amount;
            $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);
            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_inter_id',  $walletInter->id, $admin_valeur_convertion);
        }

        return true;
    }



    public function processCommissionsMobileRobus($amount_valeur, $percent_robus, $walletInter)
    {


        $wallet = robus()->wallet;
        $admins = $this->getAdminCIVProperty();

        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount - ' . $amount_valeur),
        ];

        $wallet->update($updatedValues);


        if (robus()->country->code == 'CI') {

            $message =  'Vous avez effectué un paiement de ' . $walletInter->amount .  robus()->wallet->currencie->code .
                ' ' .
                'par ' . $walletInter->provider->name .
                ' a ' . $walletInter->created_at .
                ' votre demande est en-cours de validation ' .
                'Robus vous remercie';

            sendTryCashMessage(robus()->phone, $message);
        }

        $valeur_percent_admin  = $percent_robus;

        $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);

        $this->commissionServices->distributeAdminCommissions($admins, 'wallet_inter_id', $walletInter->id, $admin_valeur_convertion);


        return true;
    }


    public function sendNotification($walletInter)
    {

        $msg_title = 'Transfert internationnal Mobile money';
        $msg = 'Demande de transfert est en cours de validation ';
        robus()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletInterEvent($walletInter));
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Demande de transfert de ' .
                    ' ' . $walletInter->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfers d\'argent Money de ' .
            ' ' . $walletInter->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (robus()->email != null) {
            $notify = new MailController();
            $notify->newPaiementInter($walletInter);
        }

        return true;
    }

    public function sendNotificationPayment($walletInter)
    {

        $msg_title = 'Demande de paiement ';
        $msg = 'Demande de transaction est en cours de validation ';
        robus()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletInterEvent($walletInter));
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Demande de transaction de ' .
                    ' ' . $walletInter->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transaction de ' .
            ' ' . $walletInter->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (robus()->email != null) {
            $notify = new MailController();
            $notify->newPaiementInter($walletInter);
        }

        return true;
    }
    public function sendNotificationRobus($walletInter)
    {

        $msg_title = 'Transfert internationnal Robus';
        $msg = 'Votre transfert vers Robus effectué avec success ';
        robus()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletInterEvent($walletInter));
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus de ' .
                    ' ' . $walletInter->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfers d\'argent Robus de ' .
            ' ' . $walletInter->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }

            if (robus()->email != null) {
                $notify = new MailController();
                $notify->newPaiementInter($walletInter);
            }
        }

        if (robus()->email != null) {
            $notify = new MailController();
            $notify->newPaiementInter($walletInter);
        }

        return true;
    }

    public function sendNotificationVirementInters($walletInter)
    {

        $msg_title = 'Transfert internationnal Robus';
        $msg = 'Votre transfert vers Robus effectué avec success ';
        robus()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletInterEvent($walletInter));
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus de ' .
                    ' ' . $walletInter->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfers d\'argent Robus de ' .
            ' ' . $walletInter->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }

            if (robus()->email != null) {
                $notify = new MailController();
                $notify->newVirement($walletInter);
            }
        }

        if (robus()->email != null) {
            $notify = new MailController();
            $notify->newVirement($walletInter);
        }

        return true;
    }

    // Validated WALLET mobile
    public function createMobileWalletInter(
        $amount_transaction,
        $percent_robus,
        $country_robus,
        $provider_payment,
        $phone,
        $provider_type,
        $name_custumers,
        $num_piece_custumers,
        $date_piece,
        $name,
        $name_sender,
        $account_number,
        $imagePath,
        $imageBackendPath,
        $type_piece,
    ) {

        $amount_solde = robus()->wallet->amount - $amount_transaction;

        return WalletInter::create([
            'wallet_id' => robus()->wallet->id,
            'amount' => $amount_transaction,
            'balance_before' =>  robus()->wallet->amount,
            'description' => null,
            'inter_cost_id' => null,
            'transaction_cost_id' => $percent_robus->id,
            'phone' => $country_robus->indecative . $phone ?? null,
            'number_compte' => $account_number,
            'created_by' => robus()->id,
            'provider_id' => $provider_payment,
            'name_complet' => $name_custumers ?? null,
            'name' => $name ?? null,
            'num_piece' => $num_piece_custumers ?? null,
            'name_benef' => $name_sender ?? null,
            'validated_date' => $date_piece ?? null,
            'costs_amount' => (robus()?->is_under_distributer || robus()?->is_api) ?  ($percent_robus->percent_under_distributer * $amount_transaction) : ($percent_robus->percent * $amount_transaction),
            'provider_type' => 1,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => $provider_type,
            'amount_solde' => $amount_solde ?? null,
            'image' => $imagePath ?? null,
            'image_bakend' => $imageBackendPath ?? null,
            'type_piece' => $type_piece ?? null,
        ]);
    }
    public function createWithMobileWalletInter(
        $amount_transaction,
        $percent_robus,
        $country_robus,
        $provider_payment,
        $phone,
        $provider_type,
        $name_custumers,
        $num_piece_custumers,
        $date_piece,
        $name,
        $name_sender,
        $account_number,
        $action_percent_id,
        $imagePath,
        $imageBackendPath,
        $type_piece

    ) {

        $amount_solde = robus()->wallet->amount - $amount_transaction;

        return WalletInter::create([
            'wallet_id' => robus()->wallet->id,
            'amount' => $amount_transaction,
            'balance_before' =>  robus()->wallet->amount,
            'description' => null,
            'inter_cost_id' => null,
            'transaction_cost_id' => $percent_robus->id,
            'virement_percent_id' => null,
            'djamo_percent_id' => $provider_payment == 104 ? $action_percent_id : null,
            'push_percent_id'       => $provider_payment == 105 ? $action_percent_id : null,
            'argent_tresort_id'       => $provider_payment == 106 ? $action_percent_id : null,
            'phone' => $country_robus->indecative . $phone ?? null,
            'number_compte' => $account_number,
            'created_by' => robus()->id,
            'provider_id' => $provider_payment,
            'name_complet' => $name_custumers ?? null,
            'name' => $name ?? null,
            'num_piece' => $num_piece_custumers ?? null,
            'name_benef' => $name_sender ?? null,
            'validated_date' => $date_piece ?? null,
            'costs_amount' => (robus()?->is_under_distributer || robus()?->is_api) ?  ($percent_robus->percent_merchands * $amount_transaction) : ($percent_robus->percent * $amount_transaction),
            'provider_type' => 1,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => $provider_type,
            'amount_solde' => $amount_solde ?? null,
            'image' => $imagePath ?? null,
            'image_bakend' => $imageBackendPath ?? null,
            'type_piece' => $type_piece ?? null,

        ]);
    }

    public function createWithVersementWalletInter(
        $amount_transaction,
        $percent_robus,
        $country_robus,
        $provider_payment,
        $provider_type,
        $name_custumers,
        $guichet_code,
        $bank_code,
        $account_number,

    ) {

        $amount_solde = robus()->wallet->amount - $amount_transaction;

        return WalletInter::create([
            'wallet_id' => robus()->wallet->id,
            'amount' => $amount_transaction,
            'description' => null,
            'balance_before' =>  robus()->wallet->amount,
            'inter_cost_id' => null,
            'virement_percent_id' => $percent_robus->id ?? null,
            'number_compte' => $account_number,
            'created_by' => robus()->id,
            'provider_id' => $provider_payment,
            'num_authers' => $name_custumers ?? null,
            'bank_code' => $bank_code ?? null,
            'guichet_code' => $guichet_code ?? null,
            'costs_amount' => (robus()?->is_under_distributer || robus()?->is_api) ?  ($percent_robus->percent_merchands * $amount_transaction) : ($percent_robus->percent * $amount_transaction),
            'provider_type' => 1,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => $provider_type,
            'amount_solde' => $amount_solde ?? null,
        ]);
    }


    public function createMobileWalletInterCIV($amount_transaction, $percent_robus, $country_robus, $provider_payment, $phone, $provider_type,  $amount_cost, $name_custumers, $num_piece_custumers, $date_piece)
    {

        $amount_solde = robus()->wallet->amount - $amount_transaction;

        return WalletInter::create([
            'wallet_id' => robus()->wallet->id,
            'amount' => $amount_transaction,
            'description' => null,
            'inter_cost_id' => null,
            'balance_before' =>  robus()->wallet->amount,
            'transaction_cost_id' => $percent_robus->id,
            'phone' => $country_robus->indecative . $phone ?? null,
            'number_compte' => null,
            'created_by' => robus()->id,
            'provider_id' => $provider_payment,
            'costs_amount' => $amount_cost,
            'name_complet' => $name_custumers ?? null,
            'num_piece' => $num_piece_custumers ?? null,
            'validated_date' => $date_piece ?? null,
            'provider_type' => 1,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => $provider_type,
            'amount_solde' => $amount_solde ?? null
        ]);
    }

    public function createRobusWalletInterCIV($val_amount, $percent_robus, $country_robus, $provider_payment, $number_compte, $provider_type, $amount_cost)
    {
        $amount_solde = robus()->wallet->amount - $val_amount;

        return WalletInter::create([
            'wallet_id' => robus()->wallet->id,
            'amount' => $val_amount,
            'description' => "Transfert par Robus",
            'inter_cost_id' => null,
            'transaction_cost_id' => $percent_robus->id,
            'phone' =>  null,
            'balance_before' =>  robus()->wallet->amount,
            'number_compte' => strtoupper($number_compte),
            'created_by' => robus()->id,
            'provider_id' => $provider_payment,
            'costs_amount' => $amount_cost,
            'provider_type' => 1,
            'status' => 'SUCCESS',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => $provider_type,
            'amount_solde' => $amount_solde ?? null
        ]);
    }
    // Wallet Robus

    public function updateAmountRobusReceived($walletInter, $country_robus, $valeur_received, $wallet)
    {

        $wallet_search = Wallet::where('wallet_id', $wallet)
            ->where('country_id', $country_robus->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));


        if (robus()->country->currencie->value != $country_robus->currencie->value) {

            $commission_transaction_val_inter_convertion_cfa = (float) $valeur_received * (float) robus()->country->currencie->value;

            $commission_transaction_val_inter_convertion_send = (float) $commission_transaction_val_inter_convertion_cfa / (float) $country_robus->currencie->value;
        } else {

            $commission_transaction_val_inter_convertion_send = (float) $valeur_received;
        }

        $walletAmountSend = Wallet::where('wallet_id',  $wallet)->first();

        $amount_solde_drawal = (float)$walletAmountSend->amount + $commission_transaction_val_inter_convertion_send;

        if ($commission_transaction_val_inter_convertion_send) {

            $walletInter->update([
                'amount_received' => $commission_transaction_val_inter_convertion_send,
                'amount_solde_drawal' => $amount_solde_drawal
            ]);

            $wallet_search->update([
                'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                'sid_user_relod' => robus()->id,
                'amount' => DB::raw('amount+' . $commission_transaction_val_inter_convertion_send),
            ]);


            // Valuer in Ivoice transaction
            $transaction = TransactionInter::create([
                'wallet_inter_id' => $walletInter->id,
                'sid_user_id' => robus()->id,
                'transaction_id' => 'RB_' . $walletInter->provider->name . '_' . $transactionCode,
                'operator_transaction_name' => $walletInter->provider->name,
                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                'pay_amount' => $walletInter->amount,
                'fee_amount' =>  $walletInter->costs_amount,
                'received_amount' => $commission_transaction_val_inter_convertion_send,
                'status' => $walletInter->status,
            ]);

            InvoiceInter::create([
                'title' => "Transfers d'argent",
                'transaction_inter_id' => $transaction->id,
                'description' => $walletInter->description,
                'is_send' => true,
                'status' => $walletInter->status,
                'created_by' => robus()->id,
            ]);

            return true;
        } else {
            return false;
        }
    }

    public function updateAmountRobusReceivedSendCiv($walletInter, $country_robus, $valeur_received, $wallet)
    {

        $wallet_search = Wallet::where('wallet_id', $wallet)
            ->where('country_id', $country_robus->id)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));

        if (robus()->country->currencie->value != $country_robus->currencie->value) {

            $commission_transaction_val_inter_convertion_cfa = (float) $valeur_received * (float) robus()->country->currencie->value;

            $commission_transaction_val_inter_convertion_send = (float) $commission_transaction_val_inter_convertion_cfa / (float) $country_robus->currencie->value;
        } else {

            $commission_transaction_val_inter_convertion_send = (float) $valeur_received;
        }


        if ($commission_transaction_val_inter_convertion_send) {

            $walletInter->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            $wallet_search->update([
                'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                'sid_user_relod' => robus()->id,
                'amount' => DB::raw('amount+' . $commission_transaction_val_inter_convertion_send),
            ]);


            // Valuer in Ivoice transaction
            $transaction = TransactionInter::create([
                'wallet_inter_id' => $walletInter->id,
                'sid_user_id' => robus()->id,
                'transaction_id' => 'RB_' . $walletInter->provider->name . '_' . $transactionCode,
                'operator_transaction_name' => $walletInter->provider->name,
                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                'pay_amount' => $walletInter->amount,
                'fee_amount' =>  $walletInter->costs_amount,
                'received_amount' => $commission_transaction_val_inter_convertion_send,
                'status' => $walletInter->status,
            ]);

            InvoiceInter::create([
                'title' => "Transfers d'argent",
                'transaction_inter_id' => $transaction->id,
                // 'company_id' => robus()->company_id,
                'description' => $walletInter->description,
                'is_send' => true,
                'status' => $walletInter->status,
                'created_by' => robus()->id,
            ]);

            return true;
        } else {
            return false;
        }
    }

    public function updateAmountMoneyReceivedSendCiv($walletInter, $country_robus, $valeur_received, $wallet)
    {

        $wallet_search = Wallet::where('wallet_id', $wallet)
            ->where('country_id', $country_robus->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));

        if (robus()->country->currencie->value != $country_robus->currencie->value) {

            $commission_transaction_val_inter_convertion_cfa = (float) $valeur_received * (float) robus()->country->currencie->value;

            $commission_transaction_val_inter_convertion_send = (float) $commission_transaction_val_inter_convertion_cfa / (float) $country_robus->currencie->value;
        } else {

            $commission_transaction_val_inter_convertion_send = (float) $valeur_received;
        }


        if ($commission_transaction_val_inter_convertion_send) {

            $walletInter->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            $wallet_search->update([
                'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                'sid_user_relod' => robus()->id,
                'amount' => DB::raw('amount+' . $commission_transaction_val_inter_convertion_send),
            ]);


            // Valuer in Ivoice transaction
            $transaction = TransactionInter::create([
                'wallet_inter_id' => $walletInter->id,
                'sid_user_id' => robus()->id,
                'transaction_id' => 'RB_' . $walletInter->provider->name . '_' . $transactionCode,
                'operator_transaction_name' => $walletInter->provider->name,
                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                'pay_amount' => $walletInter->amount,
                'fee_amount' =>  $walletInter->costs_amount,
                'received_amount' => $commission_transaction_val_inter_convertion_send,
                'status' => $walletInter->status,
            ]);

            InvoiceInter::create([
                'title' => "Transfers d'argent",
                'transaction_inter_id' => $transaction->id,
                // 'company_id' => robus()->company_id,
                'description' => $walletInter->description,
                'is_send' => true,
                'status' => $walletInter->status,
                'created_by' => robus()->id,
            ]);

            return true;
        } else {
            return false;
        }
    }
    public function createRobusWalletInter($val_amount, $percent_robus, $country_robus, $provider_payment, $number_compte, $provider_type, $amount_drawal)
    {
        $amount_solde = robus()->wallet->amount - $val_amount;

        return WalletInter::create([
            'wallet_id' => robus()->wallet->id,
            'amount' => $val_amount,
            'description' => "Transfert par Robus",
            'balance_before' =>  robus()->wallet->amount,
            'inter_cost_id' => null,
            'transaction_cost_id' => $percent_robus->id,
            'phone' =>  null,
            'number_compte' => strtoupper($number_compte),
            'created_by' => robus()->id,
            'provider_id' => $provider_payment,
            'costs_amount' => (robus()?->is_under_distributer || robus()?->is_api) ?  ($percent_robus->percent_under_distributer * $val_amount) : ($percent_robus->percent * $val_amount),
            'provider_type' => 1,
            'type_transaction' => 'Depot',
            'type_received' => 'Transfert',
            'status' => 'SUCCESS',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => $provider_type,
            'amount_solde' => $amount_solde ?? null,
            'amount_solde_drawal' => $amount_drawal ?? null
        ]);
    }

    public function processCommissionsRobus($amount_valeur, $percent_robus, $walletInter)
    {


        $wallet = robus()->wallet;
        $admin_civ = $this->validatedamountDays->adminCiv();
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

        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount - ' . $amount_valeur),
        ];

        $wallet->update($updatedValues);

        $valeur_amount_admin  = $percent_robus->percent_under_distributer * $amount_valeur;

        $valuer_deposit = $percent_robus->percent_deposit_under_distributer *  $valeur_amount_admin;

        // Commissions Inters 
        if (robus()->is_under_distributer == 1 || robus()->is_api == 1) {

            $valuer_admin = $percent_robus->percent_admin_under_distributer * $valeur_amount_admin;

            $valuer_with_drawal =  $percent_robus->percent_with_drawal_under_distributer * $valeur_amount_admin;

            $valeur_percent_admin  = $valuer_with_drawal + $valuer_admin;

            $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);


            if (!empty($activeButtons['showRemonterInterButton'])) {

                $walletInter->update([
                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterInterCButton'])) {

                    $valuer_companie = $percent_robus->percent_companie * $valeur_amount_admin;

                    $walletInter->increment('commission_companie', $valuer_companie);

                    if ($admin_civ->count() > 0) {

                        $nmbre_admin = $admin_civ->count();

                        $valeur_single = ($admin_valeur_convertion) / $nmbre_admin;

                        foreach ($admin_civ as $user) {
                            $this->processAdminCommissionSDistributer($user->id, $walletInter->id, $valeur_single, $valuer_deposit);
                        }
                    }
                } else {

                    if ($admin_civ->count() > 0) {

                        $nmbre_admin = $admin_civ->count();

                        $valeur_single = ($admin_valeur_convertion) / $nmbre_admin;

                        foreach ($admin_civ as $user) {
                            $this->processAdminCommissionSDistributer($user->id, $walletInter->id, $valeur_single, $valuer_deposit);
                        }
                    }
                }
            } else {

                // Percent admin 
                $valeur_percent_admin  = $percent_robus->percent * $amount_valeur;
                $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);
                if ($admin_civ->count() > 0) {
                    $nmbre_admin = $admin_civ->count();
                    $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                    foreach ($admin_civ as $user) {
                        $this->processAdminCommission($user->id, $walletInter->id, $valeur_single);
                    }
                }
            }
        } else {

            if (robus()->country->code == 'CI') {

                $message =  'Vous avez effectué un paiement de ' . $walletInter->amount .  robus()->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletInter->provider->name .
                    ' a ' . $walletInter->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                sendTryCashMessage(robus()->phone, $message);
            }

            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent * $amount_valeur;

            $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);

            if ($admin_civ->count() > 0) {
                $nmbre_admin = $admin_civ->count();
                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $this->processAdminCommission($user->id, $walletInter->id, $valeur_single);
                }
            }
        }

        return true;
    }

    private function processAdminCommission($userId, $walletTransactionId, $amount)
    {
        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'status'  => "SUCCESS",
            'wallet_inter_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
        ]);

        $admin_single->wallet->increment('amount', $amount);
    }

    private function processAdminCommissionSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
    {
        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_inter_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            'amount_under' => (robus()?->is_under_distributer || robus()?->is_api) ?  $valuer_deposit : 0,
            'description_underdistributer' => (robus()?->is_under_distributer || robus()?->is_api) ?  'Paiement commission sous-distributer' : null
        ]);

        $admin_single->wallet->increment('amount', $amount);
    }

    public function processCommissionsRobusOutCiv($amount_valeur, $percent_robus, $walletInter)
    {


        $wallet = robus()->wallet;
        $admin_civ = $this->validatedamountDays->adminCiv();


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount - ' . $amount_valeur),
        ];

        $wallet->update($updatedValues);

        $valeur_amount_admin  = $percent_robus->percent * $amount_valeur;

        $valuer_deposit = $percent_robus->percent_deposit *  $valeur_amount_admin;

        // Commissions Inters 
        if (robus()->is_under_distributer == 1 || robus()->is_api == 1) {

            // 50 %
            $valuer_admin = $percent_robus->percent_admin * $valeur_amount_admin;

            // 20%

            $valuer_with_drawal =  $percent_robus->percent_with_drawal * $valeur_amount_admin;

            $valeur_percent_admin  = $valuer_with_drawal + $valuer_admin;

            $walletInter->update([
                'commissions' => DB::raw('commissions+'  . $valuer_deposit),
            ]);
        } else {

            if (robus()->country->code == 'CI') {

                $message =  'Vous avez effectué un paiement de ' . $walletInter->amount .  robus()->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletInter->provider->name .
                    ' a ' . $walletInter->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                sendTryCashMessage(robus()->phone, $message);
            }

            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent * $amount_valeur;
        }

        $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);

        if ($admin_civ->count() > 0) {

            $nmbre_admin = $admin_civ->count();

            $valeur_single = ($admin_valeur_convertion) / $nmbre_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_inter_id' =>  $walletInter->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $valuer_deposit,
                    'status'  => "SUCCESS",
                    'description_underdistributer' => 'Paiement commission sous-distributer'
                ]);
                $admin_single->wallet->update([
                    'reload_by' => robus()->firstname .
                        ' ' .
                        robus()->lastname,
                    'sid_user_relod' => robus()->id,
                    'amount' => DB::raw('amount+' . $valeur_single),
                ]);
            }
        }

        return true;
    }

    public function processCommissionsRobusSendCiv($amount_valeur, $percent_robus, $walletInter, $admin_civ)
    {


        $wallet = robus()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount - ' . $amount_valeur),
        ];

        $wallet->update($updatedValues);

        $valeur_amount_admin  = $percent_robus->percent * $amount_valeur;

        $valuer_deposit = $percent_robus->percent_deposit *  $valeur_amount_admin;

        // Commissions Inters 
        if (robus()->is_under_distributer == 1 || robus()->is_api == 1) {

            // 50 %
            $valuer_admin = $percent_robus->percent_admin * $valeur_amount_admin;

            // 20%


            $valuer_with_drawal =  $percent_robus->percent_with_drawal * $valeur_amount_admin;

            $valeur_percent_admin  = $valuer_with_drawal + $valuer_admin;

            $walletInter->update([
                'commissions' => DB::raw('commissions+'  . $valuer_deposit),
            ]);
        } else {

            if (robus()->country->code == 'CI') {

                $message =  'Vous avez effectué un paiement de ' . $walletInter->amount .  robus()->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletInter->provider->name .
                    ' a ' . $walletInter->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                sendTryCashMessage(robus()->phone, $message);
            }
            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent_admin * $amount_valeur;
        }

        $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valeur_percent_admin);

        if ($admin_civ->count() > 0) {

            $nmbre_admin = $admin_civ->count();

            $valeur_single = ($admin_valeur_convertion) / $nmbre_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_inter_id' =>  $walletInter->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $valuer_deposit,
                    'description_underdistributer' => 'Paiement commission sous-distributer'
                ]);
                $admin_single->wallet->update([
                    'reload_by' => robus()->firstname .
                        ' ' .
                        robus()->lastname,
                    'sid_user_relod' => robus()->id,
                    'amount' => DB::raw('amount+' . $valeur_single),
                ]);
            }
        }

        return true;
    }
    public function convertAdminCommission($amount)
    {
        $country_civ = Country::find(107);
        $robus_currency = robus()->country->currencie->value;

        if ($country_civ->currencie->value > robus()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }

    // Validated phone
    public function validatePhone($phone)
    {
        if ($phone == robus()->phone) {
            return false;
        }
        return true;
    }

    public function getAdminCIVProperty()
    {

        $admin_civ =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        return  $admin_civ;
    }

    // Validated wallet
    public function validateWallet($country_robus, $wallet)
    {

        // Search Wallet Send
        $wallet_search = Wallet::where('wallet_id', $wallet)
            ->where('country_id', $country_robus->id)
            //   ->where('status', true)
            ->first();

        if ($wallet_search != null) {

            if ($wallet == robus()->wallet->wallet_id || $wallet_search->sidUser->is_custumers == false) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }
}
