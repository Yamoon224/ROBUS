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

                $valeur_message = ' un sous distributeur ';
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

        $validator = $this->userValidatorService->validateUserData($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis', 'data' => $validator->errors()
            ], 333);
        }

        try {

            $response = $this->createUserService->createUnderMaster(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Masters crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message =  ' Un MASTER ' ;
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

    public function store_under_custumers(Request $request)
    {

        $validator = $this->userValidatorService->validateUserData($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis', 'data' => $validator->errors()
            ], 333);
        }

        // try {
         
            $response = $this->createUserService->createCustumer(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Un client crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' un client ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                return response()->json(['success' => true, 'message' =>  $msg, 'user' => $response['user']], 200);
            } else {
                return response()->json(['success' => false, 'message' => $response['message']], 333);
            }
        // } catch (\Throwable $th) {
        //     return response()->json(
        //         [
        //             'success' => false,
        //             'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
        //         ],
        //         301
        //     );
        // }
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
