<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthenticationService
{
        public function masterLogin($email, $password)
        {

                try {


                        if (Auth::attempt(['email' => $email, 'password' => $password])) {

                                $user = SidUser::whereStatus(true)->whereEmail($email)->first();

                                if (!$user) {
                                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée'];
                                }

                                if ($user->is_master && Hash::check($password, $user->password)) {
                                        Auth::user()->update(['last_login' => Carbon::now()->toDateTimeString(), 'last_logout' => null]);
                                        $token = $user->createToken('auth_token');
                                        return ['success' => true, 'token' => $token, 'user' => $user];
                                } else {
                                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée'];
                                }
                        } else {
                                return ['success' => false, 'message' => 'L\'ecriture du Email ou mot de passe incorrect.'];
                        }
                } catch (\Throwable $th) {

                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée.'];
                }
        }

        public function comZoneLogin($email, $password)
        {

                try {


                        if (Auth::attempt(['email' => $email, 'password' => $password])) {

                                $user = SidUser::whereStatus(true)->whereEmail($email)->first();

                                if (!$user) {
                                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée'];
                                }

                                if ($user->is_com_zone && Hash::check($password, $user->password)) {
                                        Auth::user()->update(['last_login' => Carbon::now()->toDateTimeString(), 'last_logout' => null]);
                                        $token = $user->createToken('auth_token');
                                        return ['success' => true, 'token' => $token, 'user' => $user];
                                } else {
                                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée'];
                                }
                        } else {
                                return ['success' => false, 'message' => 'L\'ecriture du  Email ou mot de passe incorrect.'];
                        }
                } catch (\Throwable $th) {

                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée.'];
                }
        }

        public function underDistributorLogin($email, $password)
        {

                try {


                        if (Auth::attempt(['email' => $email, 'password' => $password])) {

                                $user = SidUser::whereStatus(true)->whereEmail($email)->first();

                                if (!$user) {
                                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée'];
                                }

                                if ($user->is_under_distributer && Hash::check($password, $user->password)) {
                                        Auth::user()->update(['last_login' => Carbon::now()->toDateTimeString(), 'last_logout' => null]);
                                        $token = $user->createToken('auth_token');
                                        return ['success' => true, 'token' => $token, 'user' => $user];
                                } else {
                                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée'];
                                }
                        } else {
                                return ['success' => false, 'message' => 'L\'ecriture du Email ou mot de passe incorrect.'];
                        }
                } catch (\Throwable $th) {

                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée.'];
                }
        }


        public function custumerLogin($phone, $password)
        {

                try {


                        if (Auth::attempt(['phone' => $phone, 'password' => $password])) {

                                $user = SidUser::whereStatus(true)->wherePhone($phone)->first();

                                if (!$user) {
                                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée'];
                                }

                                if ($user->is_custumers && Hash::check($password, $user->password)) {
                                        Auth::user()->update(['last_login' => Carbon::now()->toDateTimeString(), 'last_logout' => null]);
                                        $token = $user->createToken('auth_token');
                                        return ['success' => true, 'token' => $token, 'user' => $user];
                                } else {
                                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée'];
                                }
                        } else {
                                return ['success' => false, 'message' => 'L\'écriture du numero ou mot de passe incorrect.'];
                        }
                } catch (\Throwable $th) {

                        return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée.'];
                }
        }

        public function appDispoRobus($model)
        {

                Artisan::call('app:close-status');

                // Récupérer le statut de l'application
                $appStatus = $model::latest()->first();


                // Vérifier si l'application est fermée
                if ($appStatus && $appStatus->status_app === 'closed') {

                        $robus_hour_begin = $appStatus->time_start;
                        $robus_hour_end = $appStatus->time_end;
                        $robus_hour_description = $appStatus->description;

                        return [
                                'success' => false,
                                'message' => "L'application ROBUS Sous distributeurs était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour cause de {$robus_hour_description}.",
                        ];
                }

                return ['success' => true];
        }

        public function appDispoMasterRobus($model)
        {

                Artisan::call('app:close-master-status');

                // Récupérer le statut de l'application
                $appStatus = $model::latest()->first();

               
                // Vérifier si l'application est fermée
                if ($appStatus && $appStatus->status_app === 'closed') {

            
                        $robus_hour_begin = $appStatus->time_start;
                        $robus_hour_end = $appStatus->time_end;
                        $robus_hour_description = $appStatus->description;

                
                        return [
                                'success' => false,
                                'message' => "L'application ROBUS Masters était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour cause de {$robus_hour_description}.",
                        ];
                }

                return ['success' => true];
        }

        public function appDispoComZoneRobus($model)
        {

                Artisan::call('app:close-comzone-status');

                // Récupérer le statut de l'application
                $appStatus = $model::latest()->first();


                // Vérifier si l'application est fermée
                if ($appStatus && $appStatus->status_app === 'closed') {

                        $robus_hour_begin = $appStatus->time_start;
                        $robus_hour_end = $appStatus->time_end;
                        $robus_hour_description = $appStatus->description;

                        return [
                                'success' => false,
                                'message' => "L'application ROBUS Com Zone était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour cause de {$robus_hour_description}.",
                        ];
                }

                return ['success' => true];
        }
}
