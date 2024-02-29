<?php

use Illuminate\Support\Facades\Auth;


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

function sabdyMasterVerify()
{
    $allowedRoles = ['master'];

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
