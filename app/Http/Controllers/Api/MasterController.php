<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCitieCollection;
use App\Http\Services\ValidatorService;
use App\Mail\NewAdmin;
use App\Models\Custumers\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\UserCreateService;


class MasterController extends Controller
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
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function create_under_distributor(Request $request)
        {
                $validator = $this->userValidatorService->validateUserData($request->all());

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Champ est requis', 'data' => $validator->errors()
                    ], 333);
                }


                try {

                        $response = $this->createUserService->createUnderMasterApi(
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



        public function citie_masters()
        {

                $countrie_master = robus()->country->id;
                $cities  = City::whereCountryId($countrie_master)->orderBy('name', 'asc')->get();
                return  new AllCitieCollection($cities);
        }

}
