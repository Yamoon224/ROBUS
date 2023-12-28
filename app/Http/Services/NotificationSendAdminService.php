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


class NotificationSendAdminService
{


    public function sendNotificationPayment($walletPayment)
    {
        $msg = 'Demande en cours de validation';
        $msg_title = 'Paiement facture';
        $admins = $this->getAdminCIVProperty();
        sabdy()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletPaiementEvent($walletPayment));
        Notification::send($admins, new AdminNotification('Demande de paiement'));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
      
        } 

        if (sabdy()->email != null) {

            $notify = new MailController;
            $notify->newPaiement($walletPayment);
        }
        return true ;
    }

    public function sendNotificationPaymentOffer($walletPayment)
    {
        $msg = 'Demande en cours de validation';
        $msg_title = 'Paiement canal';
        $admins = $this->getAdminCIVProperty();
        sabdy()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletPaiementEvent($walletPayment));
        Notification::send($admins, new AdminNotification('Demande de paiement'));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
      
        } 

        if (sabdy()->email != null) {

            $notify = new MailController;
            $notify->newPaiement($walletPayment);
        }
        return true;
    }
    public function sendNotificationReloadCard($walletRelaodCard)
    {
        if (sabdy()->is_custumers) {
            if (sabdy()->country->code == "CI") {

                $message = 'Vous avez effectué une demande de rechargement de  ' . $walletRelaodCard->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                    . $walletRelaodCard->provider->name  .  ' le '
                    . $walletRelaodCard->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                $encode = substr(sabdy()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        $msg = 'Demande en cours de validation';
        $msg_title = 'Rechargement de carte';
        $admins = $this->getAdminCIVProperty();
        sabdy()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletReloadCardEvent($walletRelaodCard));
        Notification::send($admins, new AdminNotification('Demande de rechargement de carte de ' . ' ' . $walletReloadCard->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }
        if (sabdy()->email != null) {

            $notify = new MailController;
            $notify->newReload($walletRelaodCard);
        }
        return true;
    }

    public function sendNotificationOrderCard($walletOrderCard)
    {

        if (sabdy()->is_custumers) {
            if (sabdy()->country->code == "CI") {

                $message = 'Vous avez effectué une commande de carte de  ' . $walletOrderCard->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                    . $walletOrderCard->provider->name  .  ' le '
                    . $walletOrderCard->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                $encode = substr(sabdy()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }
        $msg = 'Votre demande est en cours de validation';
        $msg_title = 'Commande carte';
        sabdy()->notify(new UserNotification(
            [
                'title' => $msg_title,
                'message' => $msg
            ]
        ));

        $admins = $this->getAdminCIVProperty();
        $notifs = sabdy()->unreadNotifications;
        event(new CreateCardBankEvent($walletOrderCard));
        Notification::send($admins, new AdminNotification('Commande de carte de ' . ' ' . $walletOrderCard->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }

            if (sabdy()->email != null) {

                $notify = new MailController;
                $notify->newCardOrder($walletOrderCard);
            }

            return true;
        }
    }
    public function sendNotificationPhoneCredit($walletPhone)
    {


        if (sabdy()->is_custumers) {
            if (sabdy()->country->code == "CI") {

                $message = 'Votre transaction de ' . $walletPhone->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                    . $walletPhone->provider->name  .  ' le '
                    . $walletPhone->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                $encode = substr(sabdy()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }
        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title = 'Credit téléphonique';
        sabdy()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletPhoneCreditEvent($walletPhone));
        Notification::send($admins, new AdminNotification('Demande de credit de ' . ' ' . $walletPhone->wallet->sidUser->firstname));
        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (sabdy()->email != null) {

            $notify = new MailController;
            $notify->newPhoneCredit($walletPhone);
        }
        return true;
    }
    public function sendNotificationVirement($walletBank)
    {

        if (sabdy()->is_custumers) {
            if (sabdy()->country->code == "CI") {

                $message = 'Votre transaction de ' . $walletBank->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                    . $walletBank->provider->name  .  ' le '
                    . $walletBank->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                $encode = substr(sabdy()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }
        $msg = 'Votre demande de virement est en cours ';
        $msg_title = 'Demande de virement';

        sabdy()->notify(new UserNotification([
            'title' => $msg_title,
            'message' => $msg,
        ]));


        $admins = $this->getAdminCIVProperty();
        $notifs =  sabdy()->unreadNotifications;
        event(new CreateWalletBankEvent($walletBank));
        Notification::send($admins, new AdminNotification('Demande de virement de ' . ' ' . $walletBank->wallet->sidUser->firstname));
        if ($admins->count() > 0) {


            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (sabdy()->email != null) {

            $notify = new MailController;
            $notify->newVirement($walletBank);
        }

        return true;
    }

    public function sendNotification($walletTransaction)
    {

        if (sabdy()->is_custumers) {
            if (sabdy()->country->code == "CI") {

                $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                    . $walletTransaction->provider->name  .  ' le '
                    . $walletTransaction->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';

                $encode = substr(sabdy()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        $msg_title = 'Transfert Notional Robus';
        $msg = 'Vous avez effectué un transfert vers Robus avec succes';
        sabdy()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = sabdy()->unreadNotifications;
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

        if (sabdy()->email != null) {
            $notify = new MailController();
            $notify->newTransaction($walletTransaction);
        }

        return true;
    }

    public function sendNotificationMobile($walletTransaction)
    {
        if (sabdy()->is_custumers) {
            if (sabdy()->country->code == "CI") {

                $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                    . $walletTransaction->provider->name  .  ' le '
                    . $walletTransaction->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';

                $encode = substr(sabdy()->phone,  -10);

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            }
        }

        $msg_title = 'Transfert Mobile money';
        $msg = 'Vous avez effectué un transfert vers Mobile Money ';
        sabdy()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = sabdy()->unreadNotifications;
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
