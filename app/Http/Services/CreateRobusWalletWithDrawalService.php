<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Mail\CodeValidated;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Payments\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Events\CreateWalletTransfersEvent;
use App\Models\Invoices\InvoiceTransaction;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class CreateRobusWalletWithDrawalService
{

    /** @var  TransactionRepository */
    public $transctionRepository;

    public function __construct(TransactionRepository $transactionReposi)
    {
        $this->transctionRepository = $transactionReposi;
    }


    public function createRobusWalletWithDrawalTransaction($transaction_amount_robus, $provider, $compte_number_robus, $wallet_search)
    {


        $todays = strtotime(Carbon::now());
        $compare_date = date('H:i:s', $todays);
        $temps_ecoule = strtotime($compare_date);
        $ecoule_min = date('s', $temps_ecoule);
        $robus = str_shuffle('1234567894568392344224');
        $rt = rand(40, 906769895) * time() + $ecoule_min;
        $code_valdated = sprintf('%0.4s', str_shuffle($rt . $temps_ecoule . $robus));
        $val_amount = $transaction_amount_robus;

        if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

            $encode = substr($wallet_search->sidUser->phone,  -10);

            $message = 'Code de validation : ' . $code_valdated  . ' . Ne partagez ce code qu\'avec un agent ROBUS lors d\'une transaction ' . ' Expire dans 5 min';

            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
        }

        if ($wallet_search->sidUser->email != null) {

            Mail::to($wallet_search->sidUser->email)->send(new CodeValidated(' CODE : '  . $code_valdated));
        }

        return  $this->transctionRepository->create([
            'wallet_id' =>  robus()->wallet->id,
            'amount' => $val_amount,
            'description'  => 'Demande de retrait Robus',
            'transaction_cost_id' => 1,
            'created_by' => robus()->id,
            'provider_id'  => $provider,
            // 'costs_amount' => $percent_robus->percent * $request->amount,
            'num_wallet' => strtoupper($compte_number_robus),
            'choose_type_transaction' => 1,
            'type_transaction' => 'Retrait',
            'choose_type_transaction' => 2,
            'code_validated' => $code_valdated,
            'phone'  =>   null,
            'status'  => "PENDING"
        ]);
    }

    public function updateDrawalTransaction($verify_code, $wallet_user_search, $valuer_with_drawal)
    {

        $rt = rand(40, 500000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));

        // Update Info valeurs
        $walletAmountSend = Wallet::where('wallet_id',  $wallet_user_search->wallet_id)->first();

        $amount_solde_drawal = (float)$walletAmountSend->amount - (float)$verify_code->amount;

        $amount_solde = (float)robus()->wallet->amount + (float)$verify_code->amount;

        $wallet_user_search->update([
            'reload_by' =>  robus()->firstname .
                ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-' . $verify_code->amount),
        ]);

        // Update valeurs WalletTrasaction
        $verify_code->update(
            [
                'amount_solde'  => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_solde_drawal ?? null
            ]
        );

        $transaction = Transaction::create([
            'wallet_transaction_id' => $verify_code->id,
            'sid_user_id' => robus()->id,
            'transaction_id' => 'RB_' . $verify_code->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $verify_code->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $verify_code->amount,
            'fee_amount' => 0,
            'received_amount' => $verify_code->amount,
            'status' => $verify_code->status,
        ]);

        InvoiceTransaction::create([
            'title' => "Transfers d'argent",
            'wallet_transaction_id' => $verify_code->id,
            'transactions_id' => $transaction->id,
            'company_id' => robus()->company_id,
            'description' => $verify_code->description,
            'status' => $verify_code->status,
            'created_by' => robus()->id,
        ]);

        // Update  account in distributer
        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount+'  .  $verify_code->amount),
        ]);


        $verify_code->update([
            'commissions' => DB::raw('commissions+' . $valuer_with_drawal),
        ]);

        return true;
    }

    public function sendNotificationWithDrawal($verify_code, $admin_civ, $valuer_with_drawal, $wallet_user_search, $admins)
    {

        $country_civ = Country::whereId(107)
            ->first();


        if ($country_civ->currencie->value > robus()->country->currencie->value) {

            //   Convertion  in CFA
            $drawal_valeur_commission =   (float)  $valuer_with_drawal *   (float) robus()->country->currencie->value;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {

            $drawal_valeur_commission =   (float)  $valuer_with_drawal;
        } else {

            $drawal_valeur_commission =   (float)  $valuer_with_drawal *   (float) robus()->country->currencie->value;
        }


        if ($admin_civ->count() > 0) {

            $nmbre_admin = $admin_civ->count();

            $valeur_single = $drawal_valeur_commission / $nmbre_admin;

            foreach ($admin_civ as $user) {

                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'amount_under' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_transaction_id' => $verify_code->id,
                    'wallet_bank_id' => null,
                    'wallet_deposit_id' => null,
                    'wallet_inter_id' =>  null,
                    'wallet_payment_id' => null,
                    'wallet_phone_credit_id' => null,
                    'wallet_reload_id' => null,
                    'bank_card_id' => null,
                    'description' => 'Retrait d\'argent',
                    'status'  => "SUCCESS",
                    'description_underdistributer' => 'Paiement commission sous-distributer',
                ]);

                $admin_single->wallet->update([
                    'reload_by' => robus()->firstname .
                        ' ' .
                        robus()->lastname,
                    'sid_user_relod' => robus()->id,
                    'amount' => DB::raw('amount-' . $valeur_single),
                ]);
            }
        }

        // Notify And User an Admins
        $msg_title = 'Transactions';
        $msg = 'Retrait a été validé';
        robus()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
        $notifs = robus()->unreadNotifications;
        $wallet_user_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
        $notifs = $wallet_user_search->sidUser->unreadNotifications;
        event(new CreateWalletTransfersEvent($verify_code));
        Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $verify_code->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (robus()->email != null) {

            $notify = new MailController;
            $notify->newTransaction($verify_code);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $verify_code->id,
                'provider' => $verify_code->provider->name,
                'amount' => $verify_code->amount,
                'phone' => $verify_code->phone,
                'num_wallet' => $verify_code->num_wallet,
                'status' => $verify_code->status,
            ],
            'message' => ('Votre retrait a été validé avec succéss .')
        ], 200);
    }

    public function validateAmountDrawel($transaction_amount_robus, $num_wallet)
    {


        $wallet_search = Wallet::where('wallet_id',  $num_wallet)
            ->where('country_id',  robus()->country->id)
            ->whereStatus(true)
            ->first();

        $val_amount = $transaction_amount_robus;

        if ($val_amount > $wallet_search->amount) {
            return false;
        }


        //  Send message in phone
        return true;
    }
}