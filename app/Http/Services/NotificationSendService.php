<?php

namespace App\Http\Services;


use App\Models\Sid\SidUser;
use App\Events\AdminNotifEvent;
use App\Events\CreateCardBankEvent;
use Illuminate\Support\Facades\Http;
use App\Events\CreateWalletBankEvent;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Events\CreateWalletPaiementEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Events\CreateWalletReloadCardEvent;
use App\Events\CreateWalletPhoneCreditEvent;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;


class NotificationSendService
{


    public function sendNotificationPayment($walletPayment)
    {
        $msg = 'Demande en cours de validation';
        $msg_title = 'Paiement facture';
        $admins = $this->getAdminCIVProperty();
        robus()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletPaiementEvent($walletPayment));
        Notification::send($admins, new AdminNotification('Demande de paiement'));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
      
        } 

        if (robus()->email != null) {

            $notify = new MailController;
            $notify->newPaiement($walletPayment);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletPayment->id,
                'amount' => $walletPayment->amount,
                // 'provider' => $walletPayment->provider->name,
                // 'number_decoder' => $walletPayment->number_decoder,
                // 'id_invoice' => $walletPayment->id_invoice,
                // 'number_contract' => $walletPayment->number_contract,
                // 'name' => $walletPayment->name,
                // 'number_phone' => $walletPayment->number_phone,
                // 'offer_id' => $walletPayment->offer_id,
                // 'name' => $walletPayment->name,
                'status' => $walletPayment->status,
            ],
            'message' => ('Votre commande de facture a bien été prise en compte.')
        ], 200);
    }

    public function sendNotificationPaymentOffer($walletPayment)
    {
        $msg = 'Demande en cours de validation';
        $msg_title = 'Paiement canal';
        $admins = $this->getAdminCIVProperty();
        robus()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletPaiementEvent($walletPayment));
        Notification::send($admins, new AdminNotification('Demande de paiement'));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
      
        } 

        if (robus()->email != null) {

            $notify = new MailController;
            $notify->newPaiement($walletPayment);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletPayment->id,
                'amount' => $walletPayment->amount,
                // 'provider' => $walletPayment->provider->name,
                // 'number_decoder' => $walletPayment->number_decoder,
                // 'id_invoice' => $walletPayment->id_invoice,
                // 'number_contract' => $walletPayment->number_contract,
                // 'name' => $walletPayment->name,
                // 'number_phone' => $walletPayment->number_phone,
                // 'offer_id' => $walletPayment->offer_id,
                // 'name' => $walletPayment->name,
                'status' => $walletPayment->status,
            ],
            'message' => ('Votre demande de paiement de facture a bien été prise en compte.')
        ], 200);
    }
    public function sendNotificationReloadCard($walletRelaodCard)
    {
        if (robus()->is_custumers) {
            if (robus()->country->code == "CI") {

                $message = 'Vous avez effectué une demande de rechargement de  ' . $walletRelaodCard->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                    . $walletRelaodCard->provider->name  .  ' le '
                    . $walletRelaodCard->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                $encode = substr(robus()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        $msg = 'Demande en cours de validation';
        $msg_title = 'Rechargement de carte';
        $admins = $this->getAdminCIVProperty();
        robus()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletReloadCardEvent($walletRelaodCard));
        Notification::send($admins, new AdminNotification('Demande de rechargement de carte de ' . ' ' . $walletRelaodCard->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }
        if (robus()->email != null) {

            $notify = new MailController;
            $notify->newReload($walletRelaodCard);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletRelaodCard->id,
                // 'provider' => $walletRelaodCard->provider->name,
                // 'amount' => $walletRelaodCard->amount,
                // 'num_carte' => $walletRelaodCard->num_carte,
                // 'name' => $walletRelaodCard->name,
                // 'description' => $walletRelaodCard->description,
                'status' => $walletRelaodCard->status,
            ],
            'message' => ('Votre demande de réchargement de carte a bien été prise en compte.')
        ], 200);
    }

    public function sendNotificationOrderCard($walletOrderCard)
    {

        if (robus()->is_custumers) {
            if (robus()->country->code == "CI") {

                $message = 'Vous avez effectué une commande de carte de  ' . $walletOrderCard->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                    . $walletOrderCard->provider->name  .  ' le '
                    . $walletOrderCard->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                $encode = substr(robus()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }
        $msg = 'Votre demande est en cours de validation';
        $msg_title = 'Commande carte';
        robus()->notify(new UserNotification(
            [
                'title' => $msg_title,
                'message' => $msg
            ]
        ));

        $admins = $this->getAdminCIVProperty();
        $notifs = robus()->unreadNotifications;
        event(new CreateCardBankEvent($walletOrderCard));
        Notification::send($admins, new AdminNotification('Commande de carte de ' . ' ' . $walletOrderCard->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }

            if (robus()->email != null) {

                $notify = new MailController;
                $notify->newCardOrder($walletOrderCard);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id'      => $walletOrderCard->id,
                    // 'phone' => $walletOrderCard->wallet->sidUser->phone,
                    // 'email' => $walletOrderCard->wallet->sidUser->email,
                    'status' => $walletOrderCard->status,
                ],
                'message' => ('Votre commande de carte a bien été prise en compte.')
            ], 200);
        }
    }
    public function sendNotificationPhoneCredit($walletPhone)
    {


        if (robus()->is_custumers) {
            if (robus()->country->code == "CI") {

                $message = 'Votre transaction de ' . $walletPhone->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                    . $walletPhone->provider->name  .  ' le '
                    . $walletPhone->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                $encode = substr(robus()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }
        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title = 'Credit téléphonique';
        robus()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletPhoneCreditEvent($walletPhone));
        Notification::send($admins, new AdminNotification('Demande de credit de ' . ' ' . $walletPhone->wallet->sidUser->firstname));
        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (robus()->email != null) {

            $notify = new MailController;
            $notify->newPhoneCredit($walletPhone);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletPhone->id,
                // 'provider' => $walletPhone->provider->name,
                'amount' => $walletPhone->amount,
                // 'phone' => $walletPhone->phone,
                // 'description' => $walletPhone->description,
                'status' => $walletPhone->status,
            ],
            'message' => ('Le crédit téléphonique a été créé avec succès.')
        ], 200);
    }
    public function sendNotificationVirement($walletBank)
    {

        if (robus()->is_custumers) {
            if (robus()->country->code == "CI") {

                $message = 'Votre transaction de ' . $walletBank->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                    . $walletBank->provider->name  .  ' le '
                    . $walletBank->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                $encode = substr(robus()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }
        $msg = 'Votre demande de virement est en cours ';
        $msg_title = 'Demande de virement';

        robus()->notify(new UserNotification([
            'title' => $msg_title,
            'message' => $msg,
        ]));


        $admins = $this->getAdminCIVProperty();
        $notifs =  robus()->unreadNotifications;
        event(new CreateWalletBankEvent($walletBank));
        Notification::send($admins, new AdminNotification('Demande de virement de ' . ' ' . $walletBank->wallet->sidUser->firstname));
        if ($admins->count() > 0) {


            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (robus()->email != null) {

            $notify = new MailController;
            $notify->newVirement($walletBank);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletBank->id,
                // 'provider' => $walletBank->provider->name,
                'amount' => $walletBank->amount,
                // 'phone' => $walletBank->phone,
                // 'firstname' => $walletBank->firstname,
                // 'lastname' => $walletBank->lastname,
                'status' => $walletBank->status,
            ],
            'message' => ('Votre transaction a bien été prise en compte.')
        ], 200);
    }

    public function sendNotification($walletTransaction)
    {

        if (robus()->is_custumers) {
            if (robus()->country->code == "CI") {

                $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                    . $walletTransaction->provider->name  .  ' le '
                    . $walletTransaction->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';

                $encode = substr(robus()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        $msg_title = 'Transfert Notional Robus';
        $msg = 'Vous avez effectué un transfert vers Robus avec succes';
        robus()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletTransfersEvent($walletTransaction));
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus vers Client ' .
                    ' ' . $walletTransaction->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfert d\'argent Robus vers ' .
            ' ' . $walletTransaction->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (robus()->email != null) {
            $notify = new MailController();
            $notify->newTransaction($walletTransaction);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletTransaction->id,
                // 'provider' => $walletTransaction->provider->name,
                'amount' => $walletTransaction->amount,
                // 'phone' => $walletTransaction->phone,
                // 'num_wallet' => $walletTransaction->num_wallet,
                'status' => $walletTransaction->status,
            ],
            'message' => ('La transaction a été effectuée avec succès.')
        ], 200);
    }

    public function sendNotificationMobile($walletTransaction)
    {
        if (robus()->is_custumers) {
            if (robus()->country->code == "CI") {

                $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                    . $walletTransaction->provider->name  .  ' le '
                    . $walletTransaction->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';

                $encode = substr(robus()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        $msg_title = 'Transfert Mobile money';
        $msg = 'Vous avez effectué un transfert vers Mobile Money ';
        robus()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletTransfersEvent($walletTransaction));
        // $this->emit('transaction_inter_sends', $walletTransaction->id);
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus vers le client ' .
                    ' ' . $walletTransaction->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfert d\'argent Mobile Money vers ' .
            ' ' . $walletTransaction->wallet->sidUser->firstname));


        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
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
}
