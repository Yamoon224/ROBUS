<?php


use App\Models\CloseAppWeb;
use App\Models\Costs\DepositCost;
use App\Models\Custumers\Country;
use App\Models\OperatorService;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
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

function getReloadLimitPaiements()
{

    return Provider::whereStatus(true)->where('provider_type_id',  8)->where('id', '!=', 51)
        ->orderBy('name', 'asc')->limit(9)->get();
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

    $currentDaySearch = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek;

    return CloseAppWeb::whereStatus(true)->whereId($currentDaySearch)->first();
}

if (!function_exists('asset_url')) {
        function asset_url($path)
        {
            return asset('public/' . $path);
        }
}


function getAppCloseTomorowWeb()
{

    $currentDayOfWeek = Carbon::now()->dayOfWeek;

    $currentDaySearch = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek;

    
    $add_days =   $currentDaySearch + 1  ;
  
    $days_decremente =   $add_days == 8 ? 1 : $add_days ;

    return CloseAppWeb::whereStatus(true)->whereId($days_decremente)->first();
}



function getReloadPaiements()
{

    return Provider::whereStatus(true)->where('provider_type_id',  8)->where('id', '!=', 51)
        ->orderBy('name', 'asc')->get();
}



function getReloadMoneysPaiements()
{

    return Provider::whereStatus(true)->where('provider_type_id',  1)->where('id', '!=', 9)->where('id', '!=', 6)
        ->orderBy('name', 'asc')->get();
}


function firm()
{
    return Auth::guard('company')->user();
}


function sabdy()
{
    return Auth::guard('web')->user();
}

function robus()
{
    return Auth::guard('api')->user();
}



function sabdyHomeVerify()
{
    $allowedRoles = ['root', 'Distributeur', 'superviseur'];

    $user = Auth::guard('web')->user();

    if ($user && $user->hasAnyRole($allowedRoles)) {
        return true;
    } else {
        return false;
    }
}

function sabdyWebVerify()
{
    $allowedRoles = ['root', 'Distributeur', 'superviseur' ,  'sous-distributeur'];

    $user = Auth::guard('web')->user();

    if ($user && $user->hasAnyRole($allowedRoles)) {
        return true;
    } else {
        return false;
    }
}

function json_message_response($status ,$message, $status_code)
{
    return response()->json(['success' => $status,'message' => $message], $status_code);
}

function json_custom_response($response, $status_code = 200)
{
    return response()->json($response, $status_code);
}

function json_list_response($data)
{
    return response()->json(['data' => $data]);
}