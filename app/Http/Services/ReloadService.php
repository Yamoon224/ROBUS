<?php

namespace App\Http\Services;


use App\Events\AdminNotifEvent;
use App\Events\CreateReloadEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Http\Controllers\Admin\MailController;
use App\Mail\CodeValidated;
use App\Mail\UvSend;
use App\Models\Invoices\InvoiceReload;
use App\Models\Payments\PaymentReload;
use App\Models\Sid\SidUser;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use App\Repositories\ReloadRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ReloadService
{

        /** @var  VirementRepository */
        private $relaodRepository;

        public function __construct(ReloadRepository $reloadReposit)
        {
                $this->relaodRepository = $reloadReposit;
        }

        public function createWAlletReloadRobus($walletUser, $relaod_uv_amout_robus, $provider, $account_reload_uv, $valeur_receive , $amount_solde, $amount_drawal)
        {


                return $this->relaodRepository->create([
                        'wallet_id' =>   $walletUser->wallet->id,
                        'amount' => $relaod_uv_amout_robus,
                        'description'  => 'Rechargement de compte UV',
                        'reload_cost_id' => 1,
                        'created_by' => master()->id,
                        'provider_id'  =>  $provider->id,
                        'account_wallet'  => $account_reload_uv,
                        'amount_received'  => $valeur_receive,
                        'type_transaction' => 'Réequilibrage',
                        'type_received' => 'Transfert',
                        'phone'  =>  null,
                        'status'  => "SUCCESS",
                        'amount_solde'  => $amount_solde ?? null,
                        'amount_solde_drawal' => $amount_drawal ?? null
                ]);
        }

        public function updateAmountRobusReceived($reload_unders, $walletUser, $wallet_search, $valeur_receive)
        {


                $rt = rand(40, 5000) * time();
                $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                $transactionCode = sprintf("%0.9s", str_shuffle($rt . $tr));
                $transactionCompiCode = sprintf("%0.8s", str_shuffle($rt . $tr));

                $commission =  ($reload_unders->amount);

                $walletUser->wallet->update([
                        'reload_by' => master()->firstname . ' ' . master()->lastname,
                        'sid_user_relod' => master()->id,
                        'amount' => DB::raw('amount-'  .  $commission),
                ]);

                // Send in compte Client
                $wallet_search->update([
                        'reload_by' => master()->firstname . ' ' . master()->lastname,
                        'sid_user_relod' => master()->id,
                        'amount' => DB::raw('amount+'  . $valeur_receive),
                ]);


                $paymentRealod = PaymentReload::create([
                        'wallet_reload_id' => $reload_unders->id,
                        'sid_user_id' => master()->id,
                        'transaction_id' => 'RB_' . $reload_unders->provider->name . '_' . $transactionCode,
                        'operator_transaction_name' =>  $reload_unders->provider->name,
                        'trans_comp_id' => 'RB_' . $transactionCompiCode,
                        'pay_amount' =>  $reload_unders->amount,
                        'fee_amount' =>  $reload_unders->amount,
                        'received_amount' => ($reload_unders->amount),
                        'status'  =>    $reload_unders->status,
                ]);

                InvoiceReload::create([
                        'title' => "Rechargement de compte",
                        'wallet_reload_id' => $reload_unders->id,
                        'payment_reload_id' =>  $paymentRealod->id,
                        'company_id' => master()->company->id,
                        'description' => $reload_unders->description,
                        'status'  =>    $reload_unders->status,
                        'created_by'  =>   master()->id,
                ]);

                return true;
        }

        public function sendNotification($wallet_search, $walletUser, $reload_unders, $admins)
        {

                $msg_title = 'Achat d\'UV';
                $msg = 'Votre demande de rechargement d\'UV ';
                $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $notifs = $walletUser->unreadNotifications;
                $notifs = $wallet_search->sidUser->unreadNotifications;
                event(new CreateReloadEvent($reload_unders));
                Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $reload_unders->wallet->sidUser->firstname));
        
                if ($admins->count() > 0) {
        
        
                    foreach ($admins  as $user) {
        
                        $admin_single = SidUser::find($user->id);
        
                        $notifs = $admin_single->unreadNotifications;
                        event(new AdminNotifEvent($notifs));
                    }
        
                    if ($walletUser->email != null) {
        
                        $notify = new MailController;
                        $notify->newReloadUV($reload_unders);
                    }
        
                    if ($wallet_search->sidUser->email != null) {
        
                        Mail::to($wallet_search->sidUser->email)->send(new UvSend('Votre demande d\'achat d\'UV de ' . $reload_unders->amount_received . ' ' . $wallet_search->currencie->code  . ' chez  : '  . $walletUser->firstname . $walletUser->lastname . ' ' . ' Le ' .  $reload_unders->created_at  . ' Validé avec succes'));
                    }
                } else {
        
                    if ($walletUser->email != null) {
        
                        $notify = new MailController;
                        $notify->newReloadUV($reload_unders);
                    }
        
                    if ($wallet_search->sidUser->email != null) {
        
                        Mail::to($wallet_search->sidUser->email)->send(new UvSend('Votre demande d\'achat d\'UV de ' . $reload_unders->amount_received . ' ' . $wallet_search->currencie->code  . ' chez  : '  . $walletUser->firstname . $walletUser->lastname . ' ' . ' Le ' .  $reload_unders->created_at  . ' Validé avec succes'));
                    }
                }

      
                return response()->json([
                        'success' => true,
                        'data' => [
                                'id'      => $reload_unders->id,
                                'status' => $reload_unders->status,
                        ],
                        'message' => ('Votre demande de déchargement a bien été prise en compte.')
                ], 200);
        }
}
