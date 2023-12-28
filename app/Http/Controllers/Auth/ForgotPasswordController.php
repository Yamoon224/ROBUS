<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\Sid\SidUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\MailController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showAdminRecover()
    {
        return view('auth.passwords.email', ['url' => 'admin']);
    }

    public function reset_admin_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:sid_users,email',
        ], [
            'email.exists' => 'Ce mail n\'existe pas dans chez nous '
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de connexion')
                ->withInput();
        }

        try {

            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            $status = Mail::send(
                'pages.emails.forgetpassword',
                [
                    'token' => $token,
                ],
                function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Réinitialiser le mot de passe');
                }
            );

            return redirect()->route('admin.login')
                    ->with('color', 'success')
                    ->with('status', 'Robus vous a envoyer un email de renitialisation !!')
                    ->withInput();
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Une erreure est survenue')
                ->withInput();
        }
    }

    public function showAdminResetPassword($token)
    {
        return view('auth.passwords.reset', ['url' => 'admin', 'token' => $token]);
    }

    public function submitAdminResetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:sid_users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.exists' => 'Ce mail n\'existe pas dans chez nous ou n\'existe pas',
            'required.password' => 'Le mot de passe est obligatoire'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de renitialisation')
                ->withInput();
        }


        DB::beginTransaction();

        $updatePassword = DB::table('password_resets')->where([
            'email' => $request->email,
            // 'token' => $request->token
        ])->first();
        if (!$updatePassword) {
            DB::rollback();
           
            return back()
                ->with('color', 'danger')
                ->with('status', 'Votre jeton est invalide !')
                ->withInput();
        
        }

        $user = SidUser::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
         
        $user_send = SidUser::where('email', $request->email)->first();

        $notify = new MailController;
        $notify->modifyPassword($user_send);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        DB::commit();

        return redirect()->route('admin.login')
        ->with('color', 'success')
        ->with('status', 'Votre mot de passe a été renitialisation !!')
        ->withInput();

    }
}