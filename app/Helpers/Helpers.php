<?php

use App\Models\CloseApp;
use App\Models\Custumers\Country;
use App\Models\OperatorService;
use App\Models\Payments\PaymentReload;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;




function provideId($id)
{
    return Provider::find($id);
}

function walletId($id)
{
    return Wallet::find($id);
}

function paymentId($id)
{
    return WalletReload::find($id);
}

function firm()
{
    return Auth::guard('company')->user();
}


function sabdy()
{

    return Auth::guard('web')->user();
}


function sabdyVerify()
{
    $allowedRoles = ['root', 'Distributeur', 'superviseur', 'com_zone'];

    $user = Auth::guard('web')->user();

    if ($user && $user->hasAnyRole($allowedRoles)) {
        return true;
    } else {
        return false;
    }
}

function getCountriesForUser($user)
{
    $isDistributor = $user->wallet->sidUser->is_distributor;

    $countries = Country::whereRobus(true)
        ->orderBy('name', 'asc');

    if ($isDistributor) {
        $countries->where('id', $user->wallet->country->id);
    }

    return $countries->get();
}


function getOperators($userId)
{
 
    return OperatorService::where('sid_user_id', $userId)
                            ->pluck('operator_id')
                            ->toArray();

}

function sabdyWebVerify()
{
    $allowedRoles = ['root', 'Distributeur', 'superviseur'];

    $user = Auth::guard('web')->user();

    if ($user && $user->hasAnyRole($allowedRoles)) {
        return true;
    } else {
        return false;
    }
}


function sabdyMasterVerify()
{


    $user = Auth::guard('master')->user();

    if ($user && $user->hasRole('master')) {
        return true;
    } else {
        return false;
    }
}


function sabdyCompanyVerify()
{

    $user = Auth::guard('company')->user();

    if ($user && $user->hasRole('societe')) {
        return true;
    } else {
        return false;
    }
}

function master()
{
    return Auth::guard('master')->user();
}


function comzone()
{
    return Auth::guard('web')->user();
}

function robus()
{
    return Auth::guard('api')->user();
}

function getOpenRobus()
{
    return CloseApp::orderBy('id', 'asc')->get();
}
function json_message_response($status, $message, $status_code)
{
    return response()->json(['success' => $status, 'message' => $message], $status_code);
}

function json_custom_response($response, $status_code = 200)
{
    return response()->json($response, $status_code);
}

function json_list_response($data)
{
    return response()->json(['data' => $data]);
}
