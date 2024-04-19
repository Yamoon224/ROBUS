<?php

use App\Models\CloseApp;
use App\Models\CloseAppWeb;
use App\Models\Costs\DepositCost;
use App\Models\Custumers\Country;
use App\Models\OperatorService;
use App\Models\Payments\PaymentReload;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;





function reloadCardPercent()
{

    return DepositCost::whereId(2)->first();
}

function SearchWalletRobus($num_wallet)
{
    return   Wallet::where('wallet_id',  $num_wallet)->first();
}

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

function getCountriesForVisitorsUser($user)
{
    $isDistributor = $user->wallet->sidUser->is_distributor;

    $countries = Country::whereRobus(true)->whereId(107)->first();

    if ($isDistributor) {
        $countries->where('id', $user->wallet->country->id);
    }

    return $countries;
}

function getOperators($userId)
{

    return OperatorService::where('sid_user_id', $userId)
        ->pluck('operator_id')
        ->toArray();
}

function getReloadCartes()
{

    return Provider::whereStatus(true)->where('provider_type_id',  5)
        ->orderBy('name', 'asc')->get();
}

function getReloadMasterCards()
{

    return Provider::whereStatus(true)->where('provider_type_id',  5)->where('id', '!=', 91)
        ->orderBy('name', 'asc')->get();
}

function getProviderInters()
{

    return Provider::whereStatus(true)->where('provider_type_id',  8)->where('id', '!=', 51)
        ->orderBy('name', 'asc')->get();
}

function getReloadPushs()
{

    return Provider::whereStatus(true)->whereId(92)->first();
}

function getAppCloseWeb()
{

    $currentDayOfWeek = Carbon::now()->dayOfWeek;

    return CloseAppWeb::whereStatus(true)->whereId($currentDayOfWeek)->first();
}


function getAppCloseTomorowWeb()
{

    $currentDayOfWeek = Carbon::now()->dayOfWeek;

    $add_days =   $currentDayOfWeek + 1 ;

    return CloseAppWeb::whereStatus(true)->whereId($add_days)->first();
}



function getReloadPaiements()
{

    return Provider::whereStatus(true)->where('provider_type_id',  1)->where('id', '!=', 9)
        ->orderBy('name', 'asc')->get();
}


function getReloadMoneysPaiements()
{

    return Provider::whereStatus(true)->where('provider_type_id',  1)
        ->orderBy('name', 'asc')->get();
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

if (!function_exists('asset_url')) {
    function asset_url($path)
    {
        return asset('/' . $path);
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
