<?php

namespace App\Http\Controllers\Admin;

use App\Mail\OtpSend;
use App\Mail\NewAdmin;
use App\Mail\WalletBank;
use App\Mail\WalletCard;
use App\Mail\WalletInter;
use App\Mail\WalletXbet;
use App\Mail\WalletReload;
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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodeValidated;

class MailController extends Controller
{


    public function newWithdrawal($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new CodeValidated($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateWithdrawal($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new CodeValidated($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newAdmin($data)
    {
        try {
            Mail::to($data->email)->send(new NewAdmin($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function activeAccount($data)
    {
        try {
            Mail::to($data->email)->send(new ValdatedAccount($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newOtp($data)
    {
        try {
            Mail::to($data->email)->send(new OtpSend($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newOtpAgent($data)
    {
        try {
            Mail::to($data)->send(new OtpSend($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newVirement($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletBank($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function newXbet($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletXbet($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newPush($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletPush($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

      public function newVersementBanque($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletVersement($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }


     public function newDjamo($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletDjamo($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newTransaction($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletTransaction($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newPaiement($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletPaiement($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newPaiementInter($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletInter($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newReload($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletReloadCard($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newReloadUV($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletReload($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newPhoneCredit($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletPhoneCredit($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newCardOrder($data)
    {
        try {
            Mail::to($data->wallet->sidUser->email)->send(new WalletCard($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function modifyPassword($dt)
    {
        try {
            Mail::to($dt->email)->send(new modifyPassword($dt));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
