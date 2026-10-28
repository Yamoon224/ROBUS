<?php

namespace App\Http\Services;


use App\Events\AdminNotifEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Http\Controllers\Admin\MailController;
use App\Mail\CodeValidated;
use App\Models\Invoices\InvoiceTransaction;
use App\Models\Payments\Transaction;
use App\Models\Sid\SidUser;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class MasterService
{

        /** @var  VirementRepository */
        private $transctionRepository;

        public function __construct(TransactionRepository $transactionReposi)
        {
                $this->transctionRepository = $transactionReposi;
        }

        public function mastersId()
        {
          return robus()->master_id;
        }

        public function createRobusUnlocked($walletUser, $relaod_unlock_amout_robus, $provider, $account_unlock_uv, $amount_solde, $amount_drawal, $commission_send)
        {


                return $this->transctionRepository->create([
                        'wallet_id' => $walletUser->wallet->id,
                        'amount' => $relaod_unlock_amout_robus,
                        'description' =>  'Déchargement du compte' ?? null,
                        'transaction_cost_id' => 26,
                        'phone' =>  null,
                        'num_wallet' =>  $account_unlock_uv,
                        'created_by' => robus()->id,
                        'provider_id' => $provider->id,
                        'code_validated' => null,
                        'amount_received'  => $commission_send,
                        'choose_type_transaction' => 2,
                        'type_transaction' => 'Compense',
                        'type_received' => 'Transfert',
                        'status' => 'SUCCESS',
                        'amount_solde' => $amount_solde ?? null,
                        'amount_solde_drawal' => $amount_drawal ?? null
                ]);
        }

        public function updateAmountRobusReceived($commission_send, $walletUser, $wallet_search, $walletTransaction, $amount_transaction)
        {


                $rt = rand(40, 5000) * time();
                $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                $transactionCode = sprintf("%0.9s", str_shuffle($rt . $tr));
                $transactionCompiCode = sprintf("%0.8s", str_shuffle($rt . $tr));

                // Update decrement in compte Client
                $wallet_search->update([
                        'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                        'sid_user_relod' => robus()->id,
                        'amount' => DB::raw('amount-' . $commission_send),
                ]);

                // Send in compte Client
                $walletUser->wallet->update([
                        'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                        'sid_user_relod' => robus()->id,
                        'amount' => DB::raw('amount+'  .  $amount_transaction),
                ]);


                $transaction = Transaction::create([
                        'wallet_transaction_id' => $walletTransaction->id,
                        'sid_user_id' => robus()->id,
                        'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode,
                        'operator_transaction_name' => $walletTransaction->provider->name,
                        'trans_comp_id' => 'RB_' . $transactionCompiCode,
                        'pay_amount' => $walletTransaction->amount,
                        'fee_amount' => 0,
                        'received_amount' => $walletTransaction->amount,
                        'status' => $walletTransaction->status,
                ]);

                InvoiceTransaction::create([
                        'title' => "Transfers d'argent",
                        'wallet_transaction_id' => $walletTransaction->id,
                        'transactions_id' => $transaction->id,
                        'company_id' => robus()->company->id,
                        'description' => $walletTransaction->description,
                        'status' => $walletTransaction->status,
                        'created_by' => robus()->id,
                ]);

                return true;
        }

        public function sendNotification($wallet_search, $walletUser, $walletTransaction, $admins)
        {

                if ($wallet_search->sidUser->email != null) {

                        notifyQuietly(fn () => Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Robus vous a retiré la somme de  : ' . $walletTransaction->amount . ' Sur votre compte ' . ' Votre montant est de : ' .  $walletUser->wallet->amount)));
                }

                if ($walletUser->email != null) {
                        $notify = new MailController();
                        $notify->newTransaction($walletTransaction);
                }

                // Notify And User an Admins
                $msg_title = 'Transactions';
                $msg = 'Demande de transfert est en cours de validation ';
                $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                $notifs = $walletUser->unreadNotifications;
                $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                $notifs = $wallet_search->sidUser->unreadNotifications;
                event(new CreateWalletTransfersEvent($walletTransaction));
                Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $walletTransaction->wallet->sidUser->firstname));
                $admin = SidUser::where('status', true)
                        ->where('is_admin', true)
                        ->first();
                $notifs = $admin->unreadNotifications;
                event(new AdminNotifEvent($notifs));

                return response()->json([
                        'success' => true,
                        'data' => [
                                'id'      => $walletTransaction->id,
                                'status' => $walletTransaction->status,
                        ],
                        'message' => ('Votre demande de déchargement a bien été prise en compte.')
                ], 200);
        }
}
