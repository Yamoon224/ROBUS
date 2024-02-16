<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Services\UserCreateService;
use App\Http\Services\ValidatorService;
use App\Mail\NewAdmin;
use App\Models\Kyc;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class ComZoneController extends Controller
{


    protected $userValidatorService;
    protected $createUserService;

    public function __construct(
        ValidatorService $userValidatorService,
        UserCreateService $createUserService
    ) {
        $this->userValidatorService = $userValidatorService;
        $this->createUserService = $createUserService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_under_distributors(Request $request)
    {


        $role = Role::findById(7);
        dd($role);
        $validator = $this->userValidatorService->validateUserData($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis', 'data' => $validator->errors()
            ], 333);
        }

        try {

            $response = $this->createUserService->createUnderDistributor(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Sous distributeur crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = $user->is_master == true ?  ' Un MASTER ' : ' un sous distributeur ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                return response()->json(['success' => true, 'message' =>  $msg, 'user' => $response['user']], 200);
            } else {
                return response()->json(['success' => false, 'message' => $response['message']], 333);
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }

    public function store_under_masters(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'zone' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Champ est requis',
                    'data' => $validator->errors()
                ],
                333
            );
        }



        $user = DB::table('sid_users')->where('email', $request->email)->first();


        try {

            $rt = rand(40, 5000) * time();
            $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));

            $user = new SidUser();
            $user->firstname  = $request->firstname;
            $user->company_id  = 1;
            $user->lastname  = $request->lastname;
            $user->country_id  = comzone()->country->id ?? null;
            $user->city_id  = $request->city ?? null;
            $user->zone_id  = $request->zone_id ?? null;
            $user->zone  = $request->zone ?? null;
            $user->email =  $request->email;
            $user->phone  = $request->phone;
            $user->created_by = comzone()->id;
            $file_image = $request->file('image');
            $file_image_recto = $request->file('image_recto');
            $file_image_verso = $request->file('image_verso');

            if ($file_image) {
                $destination_path = public_path('profil/' . $user->id . '/');
                $front_file = date('YmdHis') . "." . $file_image->getClientOriginalExtension();
                $file_image->move($destination_path, $front_file);
                $user->image =  env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
            }


            $user->password  = Hash::make($request->password);
            $user->is_under_distributer  = false;
            $user->is_distributer  = false;
            $user->is_accounting  = false;
            $user->is_admin  =  false;
            $user->is_custumers  = false;
            $user->is_supervisor  =  false;
            $user->is_company  = false;
            $user->is_master   = true;
            $user->is_com_zone = false;
            $user->assignRole(7);

            if (!$user->country->currencie) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Pas de devise dans se pays\n \nveillez activer la devise',
                        'data' => $validator->errors()
                    ],
                    333
                );
            }



            $user->save();

            if (isset($file_image_recto) && !empty($file_image_recto) && isset($file_image_verso) && !empty($file_image_verso)) {

                $destination_path = public_path('kyc_masters/' . $user->id . '/');
                $front_file =  'face_' .  date('YmdHis') . "." . $file_image_recto->getClientOriginalExtension();
                $back_file = 'arriere_' . date('YmdHis') . "." . $file_image_verso->getClientOriginalExtension();
                $file_image_recto->move($destination_path, $front_file);
                $file_image_verso->move($destination_path, $back_file);
                Kyc::updateOrCreate(
                    [
                        'sid_user_id' => $user->id,
                        'company_id'  =>  $user->company->id
                    ],
                    [
                        'motif' => 'Validation de compte',
                        'screen' => env('APP_URL') . "/public/kyc_masters/" . $user->id . "/$front_file",
                        'screen_back' => env('APP_URL') . "/public/kyc_masters/" . $user->id . "/$back_file",
                        'sid_user_id' => $user->id,
                        'status' => true,
                        'company_id'  =>  $user->company->id
                    ]
                );
            }


            $wallet_user = Wallet::create([
                'company_id' =>   1,
                'wallet_id' =>   $user->country->code . $walletCode,
                'country_id' =>  $user->country->id,
                'created_by' => comzone()->id,
                'sid_user_id' =>  $user->id,
                'currencie_id' =>  $user->country->currencie->id,
                'amount' => 0.0,
                'reload_by' => comzone()->firstname . ' ' . comzone()->lastname,
                'status' => true
            ]);

            $user->update([
                'company_id'  => 1,
                'wallet_id'  => $wallet_user->wallet_id,
                'master_id'  => null,
                'is_accepted' => false,
                'phone'  =>   $user->country->indecative . $user->phone,
                'silver_ceiling'  =>   $user->country->silver_ceiling
            ]);


            $valeur_message = $user->is_master == true ?  ' Un MASTER ' : ' un sous distributeur ';
            Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

            $msg = "Utilisateur crée avec succès";


            return response()->json([
                'success' => true,
                'message' =>  $msg,
                'data'  => $user->status ?? null,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }

    public function store_under_custumers(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'zone' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Champ est requis',
                    'data' => $validator->errors()
                ],
                333
            );
        }



        $user = DB::table('sid_users')->where('email', $request->email)->first();


        try {

            $rt = rand(40, 5000) * time();
            $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));

            $user = new SidUser();
            $user->firstname  = $request->firstname;
            $user->company_id  = 1;
            $user->lastname  = $request->lastname;
            $user->country_id  = robus()->country->id ?? null;
            $user->city_id  = $request->city ?? null;
            $user->zone_id  = $request->zone_id ?? null;
            $user->zone  = $request->zone ?? null;
            $user->email =  $request->email;
            $user->phone  = $request->phone;
            $user->created_by = robus()->id;
            $file_image = $request->file('image');
            $file_image_recto = $request->file('image_recto');
            $file_image_verso = $request->file('image_verso');

            if ($file_image) {
                $destination_path = public_path('profil/' . $user->id . '/');
                $front_file = date('YmdHis') . "." . $file_image->getClientOriginalExtension();
                $file_image->move($destination_path, $front_file);
                $user->image =  env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
            }


            $user->password  = Hash::make($request->password);
            $user->is_under_distributer  = false;
            $user->is_distributer  = false;
            $user->is_accounting  = false;
            $user->is_admin  =  false;
            $user->is_custumers  = true;
            $user->is_supervisor  =  false;
            $user->is_company  = false;
            $user->is_master   = false;
            $user->is_com_zone = false;

            if (!$user->country->currencie) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Pas de devise dans se pays\n \nveillez activer la devise',
                        'data' => $validator->errors()
                    ],
                    333
                );
            }



            $user->save();

            if (isset($file_image_recto) && !empty($file_image_recto) && isset($file_image_verso) && !empty($file_image_verso)) {

                $destination_path = public_path('kyc_custumers/' . $user->id . '/');
                $front_file =  'face_' .  date('YmdHis') . "." . $file_image_recto->getClientOriginalExtension();
                $back_file = 'arriere_' . date('YmdHis') . "." . $file_image_verso->getClientOriginalExtension();
                $file_image_recto->move($destination_path, $front_file);
                $file_image_verso->move($destination_path, $back_file);
                Kyc::updateOrCreate(
                    [
                        'sid_user_id' => $user->id,
                        'company_id'  =>  $user->company->id
                    ],
                    [
                        'motif' => 'Validation de compte',
                        'screen' => env('APP_URL') . "/public/kyc_custumers/" . $user->id . "/$front_file",
                        'screen_back' => env('APP_URL') . "/public/kyc_custumers/" . $user->id . "/$back_file",
                        'sid_user_id' => $user->id,
                        'status' => true,
                        'company_id'  =>  $user->company->id
                    ]
                );
            }


            $wallet_user = Wallet::create([
                'company_id' =>   1,
                'wallet_id' =>   $user->country->code . $walletCode,
                'country_id' =>  $user->country->id,
                'created_by' => robus()->id,
                'sid_user_id' =>  $user->id,
                'currencie_id' =>  $user->country->currencie->id,
                'amount' => 0.0,
                'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                'status' => true
            ]);

            $user->update([
                'company_id'  => 1,
                'wallet_id'  => $wallet_user->wallet_id,
                'master_id'  => null,
                'is_accepted' => false,
                'phone'  =>   $user->country->indecative . $user->phone,
                'silver_ceiling'  =>   $user->country->silver_ceiling
            ]);


            $valeur_message = $user->is_custumers == true ?  ' Un Client ' : ' un sous distributeur ';
            Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

            $msg = "Utilisateur crée avec succès";

            return response()->json([
                'success' => true,
                'message' =>  $msg,
                'data'  => $user->status ?? null,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
