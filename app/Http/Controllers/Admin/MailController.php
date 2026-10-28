<?php

namespace App\Http\Controllers\Admin;

use App\Mail\OtpSend;
use App\Mail\NewAdmin;
use App\Mail\WalletBank;
use App\Mail\WalletCard;
use App\Mail\WalletInter;
use App\Mail\WalletXbet;
use App\Mail\WalletReload;
use App\Mail\WalletBankSendEmail;
use App\Mail\WalletPush;
use App\Mail\WalletDjamo;
use App\Mail\sendValidated;
use App\Mail\modifyPassword;
use App\Mail\WalletPaiement;
use Illuminate\Http\Request;
use App\Mail\ValdatedAccount;
use App\Mail\authNotification;
use App\Mail\WalletReloadCard;
use App\Mail\WalletPhoneCredit;
use App\Mail\WalletTransaction;
use App\Mail\WalletVersement;
use App\Mail\WalletReloadAcompte;
use App\Mail\WalletTresort;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodeValidated;
use App\Models\Wallets\WalletBankSend;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{


    public function newWithdrawal($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new CodeValidated($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateWithdrawal($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new CodeValidated($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newAdmin($data)
    {
        try {
            Mail::to($data->email)->queue(new NewAdmin($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function activeAccount($data)
    {
        try {
            Mail::to($data->email)->queue(new ValdatedAccount($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newOtp($data)
    {
        try {
            Mail::to($data->email)->queue(new OtpSend($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newOtpAgent($data)
    {
        try {
            Mail::to($data)->queue(new OtpSend($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newVirement($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletBank($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function newXbet($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletXbet($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newPush($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletPush($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newVersementBanque($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletVersement($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function newDjamo($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletDjamo($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newTresort($data)
    {
        try {

            $email = optional($data->wallet->sidUser)->email;
            if ($email) {
                Mail::to($data->wallet->sidUser->email)->queue(new WalletTresort($data));
            } else {
                Log::warning('Impossible d’envoyer le mail Tresort : email introuvable', [
                    'transaction_id' => $data->id
                ]);
            }
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newTransaction($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletTransaction($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newPaiement($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletPaiement($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newPaiementInter($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletInter($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newReload($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletReloadCard($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newReloadUV($data)
    {

        try {
            $email = $data->wallet->sidUser->email;

            if ($email) {
                $mailable = new WalletReload($email);
                $mailable->onQueue('emails'); 

                Mail::to($email)->queue($mailable);

                Log::info('Email dispatché vers queue emails', [
                    'transaction_id' => $data->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur dispatch email', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function newReloadAcompteCommission($data)
    {
        try {
            Mail::to($data->sidUser->email)->queue(new WalletReloadAcompte($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newPhoneCredit($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletPhoneCredit($data));
        } catch (\Throwable $th) {
            Log::error('Erreur mail crédit téléphonique', [
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function newWithTransaction($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletPush($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newBankSend($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletBankSendEmail($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newCardOrder($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->queue(new WalletCard($data));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function modifyPassword($dt)
    {
        try {
            Mail::to($dt->email)->queue(new modifyPassword($dt));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function emailSend($dt)
    {
        try {
            Mail::to($dt->email)->queue(new modifyPassword($dt));
        } catch (\Throwable $e) {

            Log::error('Erreur mail send Users', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
