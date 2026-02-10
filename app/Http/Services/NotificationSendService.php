<?php

namespace App\Http\Services;


use App\Events\AdminNotifEvent;
use App\Events\CreateCardBankEvent;
use App\Events\CreateWalletBankEvent;
use App\Events\CreateWalletOneXbet;
use App\Events\CreateWalletPaiementEvent;
use App\Events\CreateWalletVersementEvent;
use App\Events\CreateWalletDrawalEvent;
use App\Events\CreateWalletPhoneCreditEvent;
use App\Events\CreateWalletReloadCardEvent;
use App\Events\CreateWalletBankSend;
use App\Events\CreateWalletPushEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Events\CreateWalletTresortEvent;
use App\Events\CreateWalletDjamoEvent;
use App\Events\CreateWaveEvent;
use App\Http\Controllers\Admin\MailController;
use App\Models\Sid\SidUser;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use App\constants\NotificationMessages;
use Illuminate\Support\Facades\Log;


class NotificationSendService
{


    public function sendNotificationPayment($walletPayment)
    {

        try {

            $msg = 'Demande en cours de validation';
            $msg_title = 'Paiement facture';

            safeNotify(
                robus(),
                new UserNotification([
                    'title' => $msg_title,
                    'message' => $msg
                ])
            );
        } catch (\Throwable $e) {
            Log::warning('Notification utilisateur échouée', [
                'error' => $e->getMessage()
            ]);
        }
        try {
            event(new CreateWalletPaiementEvent($walletPayment));
        } catch (\Throwable $e) {
            Log::warning('Event CreateWalletPaiementEvent échoué', [
                'error' => $e->getMessage()
            ]);
        }
        // ===============================
        // 3. Notifications Admins
        // ===============================
        try {

            $admins = $this->getAdminCIVProperty();

            if ($admins->count() > 0) {

                safeNotifyMany(
                    $admins,
                    new AdminNotification(NotificationMessages::ADMIN_PAYMENT_REQUEST)
                );
                foreach ($admins as $user) {

                    $admin_single = SidUser::find($user->id);

                    if ($admin_single) {
                        $notifs = $admin_single->unreadNotifications;
                        event(new AdminNotifEvent($notifs));
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Notifications admin échouées', [
                'error' => $e->getMessage()
            ]);
        }

        // ===============================
        // 4. Email (non bloquant)
        // ===============================
        try {
            if (!empty(robus()->email)) {
                (new MailController)->newPaiement($walletPayment);
            }
        } catch (\Throwable $e) {
            Log::warning('Email paiement échoué', [
                'error' => $e->getMessage()
            ]);
        }

        // ===============================
        // 5. RÉPONSE TOUJOURS OK
        // ===============================
        return response()->json([
            'success' => true,
            'data' => [
                'id'     => $walletPayment->id,
                'amount' => $walletPayment->amount,
                'status' => $walletPayment->status,
            ],
            'message' => 'Votre commande de facture a bien été prise en compte.'
        ], 200);
    }

    public function sendNotificationVersement($walletPayment)
    {
        $msg = 'Demande en cours de validation';
        $msg_title = 'Versement Bancaire';
        $admins = $this->getAdminCIVProperty();

        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletVersementEvent($walletPayment));

        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::ADMIN_BANK_TRANSFER_REQUEST)
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
            $notify->newPaiement($walletPayment);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletPayment->id,
                'amount' => $walletPayment->amount,
                'status' => $walletPayment->status,
            ],
            'message' => ('Votre demande de versement bancaire a bien été prise en compte.')
        ], 200);
    }

    public function sendNotificationPaymentOffer($walletPayment)
    {
        $msg = 'Demande en cours de validation';
        $msg_title = 'Paiement canal';
        $admins = $this->getAdminCIVProperty();
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletPaiementEvent($walletPayment));

        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::ADMIN_PAYMENT_REQUEST)
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
            $notify->newPaiement($walletPayment);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletPayment->id,
                'amount' => $walletPayment->amount,
                'status' => $walletPayment->status,
            ],
            'message' => ('Votre demande de paiement de facture a bien été prise en compte.')
        ], 200);
    }
    public function sendNotificationReloadCard($walletRelaodCard)
    {


        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Vous avez effectué une demande de rechargement de  ' . $walletRelaodCard->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletRelaodCard->provider->name  .  ' le '
                        . $walletRelaodCard->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }


        $msg = 'Demande en cours de validation';
        $msg_title = 'Rechargement de carte';
        $admins = $this->getAdminCIVProperty();
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletReloadCardEvent($walletRelaodCard));

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
        if (robus()->email != null) {

            $notify = new MailController;
            $notify->newReload($walletRelaodCard);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletRelaodCard->id,
                'status' => $walletRelaodCard->status,
            ],
            'message' => ('Votre demande de réchargement de carte a bien été prise en compte.')
        ], 200);
    }

    public function sendNotificationOrderCard($walletOrderCard)
    {

        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Vous avez effectué une commande de carte de  ' . $walletOrderCard->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletOrderCard->provider->name  .  ' le '
                        . $walletOrderCard->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }


        $msg = 'Votre demande est en cours de validation';
        $msg_title = 'Commande carte';
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );

        $admins = $this->getAdminCIVProperty();
        $notifs = robus()->unreadNotifications;
        event(new CreateCardBankEvent($walletOrderCard));
        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::ORDER_RELOAD_CARD)
        );
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
                    'status' => $walletOrderCard->status,
                ],
                'message' => ('Votre commande de carte a bien été prise en compte.')
            ], 200);
        }
    }
    public function sendNotificationPhoneCredit($walletPhone)
    {

        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Votre transaction de ' . $walletPhone->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletPhone->provider->name  .  ' le '
                        . $walletPhone->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }


        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title = 'Credit téléphonique';
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletPhoneCreditEvent($walletPhone));

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

        if (robus()->email != null) {

            $notify = new MailController;
            $notify->newPhoneCredit($walletPhone);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletPhone->id,
                'amount' => $walletPhone->amount,
                'status' => $walletPhone->status,
            ],
            'message' => ('Le crédit téléphonique a été créé avec succès.')
        ], 200);
    }

    public function sendNotificationPush($walletPush)
    {



        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Votre transaction de ' . $walletPush->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletPush->provider->name  .  ' le '
                        . $walletPush->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }


        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title = 'Transction Push';
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletPushEvent($walletPush));
        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_PUSH)
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
            $notify->newPush($walletPush);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletPush->id,
                'provider' => $walletPush->provider->name,
                'amount' => $walletPush->amount,
                'description' => $walletPush->description,
                'status' => $walletPush->status,
            ],
            'message' => ('La demande de Push a été créé avec succès.')
        ], 200);
    }

    public function sendNotificationTresort($walletTransaction)
    {

        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Votre transaction de ' . $walletTransaction->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletTransaction->provider->name  .  ' le '
                        . $walletTransaction->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }


        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title = 'Transction Tresort Money';
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletTresortEvent($walletTransaction));
        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_TRESORT)
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
            $notify->newTresort($walletTransaction);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletTransaction->id,
                'provider' => $walletTransaction->provider->name,
                'amount' => $walletTransaction->amount,
                'description' => $walletTransaction->description,
                'status' => $walletTransaction->status,
            ],
            'message' => ('La demande de transaction tresort Money a été créé avec succès.')
        ], 200);
    }



    public function sendNotificationDjamo($walletTransaction)
    {

        try {
            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Votre transaction de ' . $walletTransaction->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletTransaction->provider->name  .  ' le '
                        . $walletTransaction->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }

        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title = 'Transction Djamo';
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletDjamoEvent($walletTransaction));
        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_DJAMO)
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
            $notify->newDjamo($walletTransaction);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletTransaction->id,
                'provider' => $walletTransaction->provider->name,
                'amount' => $walletTransaction->amount,
                'description' => $walletTransaction->description,
                'status' => $walletTransaction->status,
            ],
            'message' => ('La demande de transaction tresort Money a été créé avec succès.')
        ], 200);
    }

    public function sendNotification1xbets($walletXbet)
    {

        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Votre demande de ' . $walletXbet->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletXbet->provider->name  .  ' le '
                        . $walletXbet->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
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
            new AdminNotification($walletXbet->choose_type_transaction == 1  ? NotificationMessages::WALLET_RELOAD_XBET  : 'Demande de retrait 1xbet')
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

    public function sendNotificationWiths($walletWith)
    {


        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Votre demande de ' . $walletWith->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletWith->provider->name  .  ' le '
                        . $walletWith->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';


                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }


        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title =  "Réchargement";
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;



        if ($walletWith->choose_transaction == 2) {
            event(new CreateWalletDrawalEvent($walletWith));
        } else {
            if ($walletWith->provider_id == 91 || $walletWith->provider_id == 143) {
                event(new CreateWalletPushEvent($walletWith));
            } else if ($walletWith->provider_id == 122 || $walletWith->provider_id == 144) {
                event(new CreateWalletTresortEvent($walletWith));
            } else if ($walletWith->provider_id == 121  || $walletWith->provider_id == 142) {
                event(new CreateWalletDjamoEvent($walletWith));
            } else {
                event(new CreateWalletTresortEvent($walletWith));
            }
        }



        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_WITH)
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

            if ($walletWith->provider_id == 91 || $walletWith->provider_id == 143) {
                $notify->newPush($walletWith);
            } else if ($walletWith->provider_id == 122 || $walletWith->provider_id == 144) {
                $notify->newTresort($walletWith);
            } else if ($walletWith->provider_id == 121  || $walletWith->provider_id == 142) {
                $notify->newDjamo($walletWith);
            } else {
                $notify->newWithTransaction($walletWith);
            }
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletWith->id,
                'amount' => $walletWith->amount,
                'status' => $walletWith->status,
            ],
            'message' => ('Votre demande ce réchargement  est en cours de traitement .')
        ], 200);
    }

    public function sendNotificationBankSend($walletWith)
    {

        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Votre demande de ' . $walletWith->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletWith->provider->name  .  ' le '
                        . $walletWith->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }


        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title =  "Bank send";
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletBankSend($walletWith));
        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_BANK)
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
            $notify->newBankSend($walletWith);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletWith->id,
                'amount' => $walletWith->amount,
                'status' => $walletWith->status,
            ],
            'message' => ('Votre demande ce réchargement de bank send est en cours de traitement .')
        ], 200);
    }

    public function sendNotificationWaves($walletWaves)
    {

        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Votre demande de ' . $walletWaves->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletWaves->provider->name  .  ' le '
                        . $walletWaves->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';


                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }


        $admins = $this->getAdminCIVProperty();
        $msg = 'Demande en cours de validation';
        $msg_title =  'Réchargement Wave ';
        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWaveEvent($walletWaves));

        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_WAVE)
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
            $notify->newPhoneCredit($walletWaves);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletWaves->id,
                'amount' => $walletWaves->amount,
                'status' => $walletWaves->status,
            ],
            'message' => ('Votre demande de rechargement UV par Wave est en cours de traitement .')
        ], 200);
    }

    public function sendNotificationVirement($walletBank)
    {

        $userAuth = auth_user();

        try {

            if ($userAuth->is_custumers) {
                if ($userAuth->country->code == "CI") {

                    $message = 'Votre transaction de ' . $walletBank->amount .  $userAuth->wallet->currencie->code . ' ' .   'par '
                        . $walletBank->provider->name  .  ' le '
                        . $walletBank->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }

        $msg = 'Votre demande de virement est en cours ';
        $msg_title = 'Demande de virement';

        safeNotify(
            robus(),
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg
            ])
        );

        $admins = $this->getAdminCIVProperty();
        $notifs =  $userAuth->unreadNotifications;
        event(new CreateWalletBankEvent($walletBank));

        safeNotifyMany(
            $admins,
            new AdminNotification(NotificationMessages::WALLET_RELOAD_VIREMENT)
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
            $notify->newVirement($walletBank);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletBank->id,
                'amount' => $walletBank->amount,
                'status' => $walletBank->status,
            ],
            'message' => ('Votre transaction a bien été prise en compte.')
        ], 200);
    }

    public function sendNotification($walletTransaction)
    {

        try {


            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  robus()->wallet->currencie->code . ' ' .   ' par '
                        . $walletTransaction->provider->name  .  ' le '
                        . $walletTransaction->created_at . ' votre transfert effectué avec success ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
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
                'amount' => $walletTransaction->amount,
                'status' => $walletTransaction->status,
            ],
            'message' => ('La transaction a été effectuée avec succès.')
        ], 200);
    }

    public function sendNotificationMobile($walletTransaction)
    {

        try {

            if (robus()->is_custumers) {
                if (robus()->country->code == "CI") {

                    $message = 'Vous avez effectué un transfert de ' . $walletTransaction->amount .  robus()->wallet->currencie->code . ' ' .   'par '
                        . $walletTransaction->provider->name  .  ' le '
                        . $walletTransaction->created_at . ' votre demande est en cours de validation ' . 'Robus vous remercie';



                    sendTryCashMessage(robus()->phone, $message);
                }
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
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

        if ($walletTransaction->type_transaction == 'Retrait') {
            event(new CreateWalletDrawalEvent($walletTransaction));
        } else {
            event(new CreateWalletTransfersEvent($walletTransaction));
        }

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
