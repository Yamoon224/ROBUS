<?php

namespace App\Http\Services;


use App\Events\AdminNotifEvent;
use App\Events\CreateCardBankEvent;
use App\Events\CreateWalletBankEvent;
use App\Events\CreateWalletOneXbet;
use App\Events\CreateWalletPaiementEvent;
use App\Events\CreateWalletPhoneCreditEvent;
use App\Events\CreateReloadWebEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Events\CreateWaveEvent;
use App\Http\Controllers\Admin\MailController;
use App\Models\Sid\SidUser;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\constants\NotificationMessages;


class NotificationWebService
{



    public function sendNotificationReloadCard($walletRelaodCard)
    {


        $admins = $this->getAdminCIVProperty();
        event(new CreateReloadWebEvent($walletRelaodCard));

        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_CARD)
        );

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        return response()->json([
            'success' => true,
            'message' => ('Votre demande de réchargement de carte a bien été prise en compte.')
        ], 200);
    }

    public function sendNotificationTransactiobWeb($walletTransaction)
    {


        $admins = $this->getAdminCIVProperty();

        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_ROBUS)
        );

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }
        return response()->json([
            'success' => true,
            'message' => ('VotrnotificationOrderCarde demande de réchargement de carte a bien été prise en compte.')
        ], 200);
    }

    public function sendNotificationTransactionInterWeb($walletInter)
    {


        $admins = $this->getAdminCIVProperty();
        event(new CreateReloadWebEvent($walletInter));
        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_INTER)
        );

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        return response()->json([
            'success' => true,
            'message' => ('Votre demande a bien été prise en compte.')
        ], 200);
    }



    public function sendNotificationTransactionXBetWeb($walletXbet)
    {


        $admins = $this->getAdminCIVProperty();
        event(new CreateReloadWebEvent($walletXbet));

        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_XBET)
        );

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }
        return response()->json([
            'success' => true,
            'message' => ('Votre demande de réchargement de carte a bien été prise en compte.')
        ], 200);
    }


    public function sendNotificationTransactionPhoneCreditWeb($walletPhone)
    {


        $admins = $this->getAdminCIVProperty();
        event(new CreateReloadWebEvent($walletPhone));

        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::PHONE_CREDIT_RELOAD_CARD)
        );

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        return response()->json([
            'success' => true,
            'message' => ('Votre demande de réchargement de carte a bien été prise en compte.')
        ], 200);
    }

    public function sendNotification1xbets($walletXbet)
    {


        if (robus()->is_custumers) {
            if (robus()->country->code == "CI") {

                $message = 'Votre demande de ' . $walletXbet->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                    . $walletXbet->provider->name  .  ' le '
                    . $walletXbet->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                sendTryCashMessage(robus()->phone, $message);
            }
        }
        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title =  $walletXbet->choose_type_transaction == 1 ? 'Réchargement 1Xbet' : 'Retrait 1Xbet';
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletOneXbet($walletXbet));
        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_XBET)
        );

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (robus()->email != null) {

            $notify = new MailController;
            $notify->newPhoneCredit($walletXbet);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletXbet->id,
                'amount' => $walletXbet->amount,
                'status' => $walletXbet->status,
            ],
            'message' => ('Votre demande ce retrait 1xbet est en cours de traitement .')
        ], 200);
    }

    public function sendNotification($walletTransaction)
    {

        if (robus()->is_custumers) {
            if (robus()->country->code == "CI") {

                $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  robus()->wallet->currencie->code . ' ' .   ' par '
                    . $walletTransaction->provider->name  .  ' le '
                    . $walletTransaction->created_at . ' votre transfert effectué avec success ' . 'Robus vous remercie';

                sendTryCashMessage(robus()->phone, $message);
            }
        }

        $msg_title = 'Transfert National Robus';
        $msg = 'Vous avez effectué un transfert vers Robus avec succes';
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletTransfersEvent($walletTransaction));
        $admins = $this->getAdminCIVProperty();

        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_ROBUS)
        );


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

                sendTryCashMessage(robus()->phone, $message);
            }
        }

        $msg_title = 'Transfert Mobile money';
        $msg = 'Vous avez effectué un transfert vers Mobile Money ';
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletTransfersEvent($walletTransaction));
        // $this->emit('transaction_inter_sends', $walletTransaction->id);
        $admins = $this->getAdminCIVProperty();
        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_ROBUS)
        );

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
