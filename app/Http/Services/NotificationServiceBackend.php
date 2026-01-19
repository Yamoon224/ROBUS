<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use App\Events\AdminNotifEvent;
use App\Notifications\UserNotification;
use App\Events\CreateWalletTransfersEvent;
use App\Events\CreateWalletInterEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminNotification;
use App\Http\Controllers\Admin\MailController;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodeValidated;
use Illuminate\Support\Facades\Log;

class NotificationServiceBackend
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */

    public function sendCivGroupeNotification($walletTransaction)
    {

        try {

            if (sabdy()->is_custumers) {
                if (sabdy()->country->code == "CI") {

                    $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  sabdy()->wallet->currencie->code . ' ' .   ' par '
                        . $walletTransaction->provider->name  .  ' le '
                        . $walletTransaction->created_at . ' votre transfert effectué avec success ' . 'Robus vous remercie';

                     sendTryCashMessage(sabdy()->phone , $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
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
        // $this->emit('transaction_inter_sends', $walletTransaction->id);
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus vers le client ' .
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
    }


    public function sendNotificationTransactionMobile($walletTransaction)
    {

        try {

            if (sabdy()->is_custumers) {
                if (sabdy()->country->code == "CI") {

                    $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                        . $walletTransaction->provider->name  .  ' le '
                        . $walletTransaction->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';

                    

                     sendTryCashMessage(sabdy()->phone , $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
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

        if (sabdy()->email != null) {
            $notify = new MailController();
            $notify->newTransaction($walletTransaction);
        }

        return true;
    }

    public function sendTransactionGroupeNotification($walletTransaction)
    {

        try {

            if (sabdy()->is_custumers) {
                if (sabdy()->country->code == "CI") {

                    $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  sabdy()->wallet->currencie->code . ' ' .   ' par '
                        . $walletTransaction->provider->name  .  ' le '
                        . $walletTransaction->created_at . ' votre transfert effectué avec success ' . 'Robus vous remercie';

                    

                     sendTryCashMessage(sabdy()->phone , $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
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
        // $this->emit('transaction_inter_sends', $walletTransaction->id);
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus vers le client ' .
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


    public function sendNotificationRobus($walletInter)
    {

        $msg_title = 'Transfert internationnal Robus';
        $msg = 'Votre transfert vers Robus effectué avec success ';
        sabdy()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletInterEvent($walletInter));
        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert  Robus de ' .
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
        }
    }

    public function sendMailReloadCardNotification($check)
    {
        $notify = new MailController;
        $notify->newReload($check);
    }
    public function notifyAdmins($admins)
    {
        foreach ($admins as $user) {
            $admin_single = SidUser::find($user->id);
            $notifs = $admin_single->unreadNotifications;
            event(new AdminNotifEvent($notifs));
        }
    }

    public function sendMailPhoneCreditNotification($check)
    {
        $notify = new MailController;
        $notify->newPhoneCredit($check);
    }

    public function sendMailVersementNotification($check)
    {
        $notify = new MailController;
        $notify->newVirement($check);
    }
    public function sendMailXbetNotification($check)
    {
        $notify = new MailController;
        $notify->newXbet($check);
    }

    public function sendMailPushNotification($check)
    {
        $notify = new MailController;
        $notify->newPush($check);
    }

    public function sendMailTresortNotification($check)
    {
        $notify = new MailController;
        $notify->newTresort($check);
    }

    public function sendMailVersementBanqueNotification($check)
    {
        $notify = new MailController;
        $notify->newVersementBanque($check);
    }


    public function sendMailDjamoNotification($check)
    {
        $notify = new MailController;
        $notify->newDjamo($check);
    }

    public function sendMailPaiementNotification($check)
    {
        $notify = new MailController;
        $notify->newPaiement($check);
    }

    public function sendMailReloadUVNotification($check)
    {
        $notify = new MailController;
        $notify->newReloadUV($check);
    }

    public function sendMailReloadAcompteCommissionNotification($check)
    {
        $notify = new MailController;
        $notify->newReloadAcompteCommission($check);
    }
    public function sendSuccessMessageCreateNotification($walletUser, $message)
    {

        try {

            if ($walletUser->country->code == 'CI' && $walletUser->is_custumers == 1) {
                $encode = substr($walletUser->phone, -10);
                http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function notifyDrawalUsers($walletUser, $wallet_search, $walletTransaction)
    {
        try {


            if ($wallet_search->sidUser->email != null) {

                Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Robus vous a retiré la somme de  : ' . $walletTransaction->amount . ' Sur votre compte ' . ' Votre montant est de : ' .  $walletUser->wallet->amount));
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function notifyCreateUsers($walletUser, $admins, $msg_title, $msg, $admin_msg)
    {
        if ($walletUser) {
            $walletUser->notify(new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ]));
        }

        Notification::send($admins, new AdminNotification($admin_msg));
    }


    public function notificationSmsDepotSend($wallet_search, $message)
    {

        try {

            if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

                $encode = substr($wallet_search->sidUser->phone,  -10);

                 sendTryCashMessage(sabdy()->phone , $message);
            }

            if ($wallet_search->sidUser->email != null) {

                Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Message dépot : '  . $message));
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function notificationRobusSend($message)
    {

        try {

            if (sabdy()->country->code == 'CI') {

                $encode = substr(sabdy()->phone, -10);

                 sendTryCashMessage(sabdy()->phone , $message);
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function sendNotification($walletTransaction)
    {

        try {

            if (sabdy()->is_custumers) {
                if (sabdy()->country->code == "CI") {

                    $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  sabdy()->wallet->currencie->code . ' ' .   ' par '
                        . $walletTransaction->provider->name  .  ' le '
                        . $walletTransaction->created_at . ' votre transfert effectué avec success ' . 'Robus vous remercie';

                    

                     sendTryCashMessage(sabdy()->phone , $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
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
        // $this->emit('transaction_inter_sends', $walletTransaction->id);
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus vers le client ' .
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

        // if (sabdy()->email != null) {
        //     $notify = new MailController();
        //     $notify->newTransaction($walletTransaction);
        // }
        return true;
    }

    public function sendNotificationMobile($walletTransaction)
    {

        try {

            if (sabdy()->is_custumers) {
                if (sabdy()->country->code == "CI") {

                    $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                        . $walletTransaction->provider->name  .  ' le '
                        . $walletTransaction->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';

                    

                     sendTryCashMessage(sabdy()->phone , $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
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

        if (sabdy()->email != null) {
            // $notify = new MailController();
            // $notify->newTransaction($walletTransaction);
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


    public function getAdminDrawalCIVProperty()
    {

        $admin_drawal =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->whereId(1)
            ->where('country_id', 107)
            ->first();

        return $admin_drawal;
    }
}
