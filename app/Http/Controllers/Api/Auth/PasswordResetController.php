<?php

namespace App\Http\Controllers\Api\Auth;

use App\Mail\EmailManager;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Mail;
use App\Http\Controllers\Controller;


/**
 * forgot password
 * forgotPassword: Send an email with the reset link with the token
 *
 * @param  mixed $request
 * @return
 *      code: 200 messages: ResetLink.sent
 *      code: 422 messages: email invalid
 */
class PasswordResetController extends Controller
{

/**
 * Verify
 * login with username or email
 * @method POST
 * @param  mixed $request
 * @return
 *      code: 210 message: No user found with this email address. !!
 *      code: 215 message: Code does not match.
 *      code: 422 message: Wrong credentials, user not found
 *      code : 301 'Wrong credentials ! Authentication failed
 *      code : 400 message : Verify compte
 */
    public function reset(Request $request){

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' =>'No user found with this email address.'
            ],  422);
        }
        if($user->verification_code != $request->code){
            return response()->json([
                'success' => false,
                'message' => 'Code does not match.'
            ], 400);
        }else{

            if($request->password){
                $user->update([
                    'password' => bcrypt($request->password),
                ]);
            }
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Your password has been updated.'
            ], 200);
        }
    }
}