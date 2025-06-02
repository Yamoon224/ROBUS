<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        public function underDistributorLogin($email, $password, $code_active, $device_meta_data)
        {

                try {
                        $user = SidUser::whereStatus(true)->whereEmail($email)->first();

                        if (!$user) {
                                return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée', 'catch' => true];
                        }

                        // Si l'utilisateur a un code d'activation défini, vérifiez-le.
                        if (!empty($user->activation_code)) {
                                return $this->attemptActiveCodeLogin($user, $email, $password, $device_meta_data, $code_active);
                        } else {
                                return $this->attemptDeviceLogin($user, $email, $password, $device_meta_data);
                        }
                        //      return $this->attemptDeviceLogin($user, $email, $password, $device_meta_data);
                } catch (\Throwable $th) {

                        return ['success' => false, 'catch' => true, 'message' => 'Mauvais identifiants ! Authentification échouée.', 'catch' => true];
                }
        }


        private function attemptActiveCodeLogin($user, $email, $password, $device_meta_data, $code_active)
        {
                if (Auth::attempt(['email' => $email, 'password' => $password])) {


                        if ($user->is_under_distributer && Hash::check($password, $user->password)) {

                                if ($user->activation_code === $code_active) {

                                        $user->update([
                                                'last_login' => Carbon::now()->toDateTimeString(),
                                                'last_logout' => null,
                                                'activation_code' => null,
                                                'device_meta_data' => $device_meta_data
                                        ]);

                                        $token = $user->createToken('auth_token');

                                        return ['success' => true, 'token' => $token, 'user' => $user];
                                } else {
                                        return ['success' => false, 'message' => 'Votre code OTP d\'activation est incorrecte .', 'catch' => true];
                                }
                        } else {
                                return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée', 'catch' => true];
                        }
                } else {
                        return ['success' => false, 'catch' => true, 'message' => 'L\'ecriture du Email ou mot de passe incorrect.', 'catch' => true];
                }
        }

        private function attemptDeviceLogin($user, $email, $password, $device_meta_data)
        {
                if (Auth::attempt(['email' => $email, 'password' => $password])) {


                        if ($user->is_under_distributer && Hash::check($password, $user->password)) {

                                // Vérifier l'activation_code
                                if (trim($user->device_meta_data) === trim(json_encode($device_meta_data)) ||  $user->device_meta_data === $device_meta_data) {

                                        $user->update([
                                                'last_login' => Carbon::now()->toDateTimeString(),
                                                'last_logout' => null,
                                                'activation_code' => null,
                                                'device_meta_data' => $device_meta_data
                                        ]);

                                        $token = $user->createToken('auth_token');

                                        return ['success' => true, 'token' => $token, 'user' => $user];
                                } else {
                                        return ['success' => false, 'message' => 'Changement de dispositif détecté ou données du téléphone incorrectes.', 'catch' => false];
                                }
                        } else {
                                return ['success' => false, 'message' => 'Mauvais identifiants ! Authentification échouée', 'catch' => true];
                        }
                } else {
                        return ['success' => false, 'message' => 'L\'ecriture du Email ou mot de passe incorrect.', 'catch' => true];
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
                try {


                        // Vérifier si le statut est en cache
                        $cachedResult = Cache::get('app_status_mobile');
                        if ($cachedResult) {
                                return $cachedResult;
                        }

                        // Exécuter la commande Artisan pour mettre à jour les données
                        Artisan::call('app:close-status');

                        // Récupérer le dernier statut de l'application
                        $appStatus = $model::latest()->first();

                        if ($appStatus && $appStatus->status_app === 'closed') {
                                return $this->formatClosedAppResponse($appStatus);
                        }

                        // Si l'application est ouverte, mettre en cache un statut de succès
                        $successResponse = ['success' => true];
                        Cache::put('app_status_mobile', $successResponse, now()->addMinutes(30));
                        return $successResponse;
                } catch (\Exception $e) {
                        // Gérer les exceptions et retourner une erreur générique
                        return [
                                'success' => false,
                                'message' => 'Une erreur est survenue lors de la vérification du statut de l\'application.',
                                // 'error' => $e->getMessage(),
                        ];
                }
        }
        // public function appDispoRobus($model)
        // {

        //         // Artisan::call('app:close-status');

        //         // Récupérer le statut de l'application
        //         $appStatus = $model::latest()->first();

        //         // Vérifier si l'application est fermée
        //         if ($appStatus && $appStatus->status_app === 'closed') {

        //                 $robus_hour_begin = $appStatus->time_start;
        //                 $robus_hour_end = $appStatus->time_end;
        //                 $robus_hour_description = $appStatus->description;

        //                 return [
        //                         'success' => false,
        //                         'message' => "L'application ROBUS Sous distributeurs était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour cause de {$robus_hour_description}.",
        //                 ];
        //         }

        //         return ['success' => true];
        // }

        private function formatClosedAppResponse($appStatus)
        {
                $robusHourBegin = $appStatus->time_start;
                $robusHourEnd = $appStatus->time_end;
                $robusHourDescription = $appStatus->description;

                $message = "L'application ROBUS Sous distributeurs était ouverte aujourd'hui de {$robusHourBegin} à {$robusHourEnd} et est fermée pour cause de {$robusHourDescription}.";

                $response = [
                        'success' => false,
                        'message' => $message,
                ];

                // Mettre en cache la réponse
                Cache::put('app_status_mobile', $response, now()->addMinutes(30));

                return $response;
        }


        public function appDispoWebRobus($model)
        {
                // Vérifier d'abord si les données sont en cache
                $cachedResult = Cache::get('app_status_result');

                if ($cachedResult) {
                        return $cachedResult;
                }

                // Si les données ne sont pas en cache, exécuter la requête
                Artisan::call('app:close-web-status');

                // Récupérer le statut de l'application
                $appStatus = $model::latest()->first();

                // Vérifier si l'application est fermée
                if ($appStatus && $appStatus->status_app === 'closed') {

                        $robus_hour_begin = $appStatus->time_start;
                        $robus_hour_end = $appStatus->time_end;
                        $robus_hour_description = $appStatus->description;

                        // Mettre à jour la mise en cache avec le résultat
                        $result = [
                                'success' => false,
                                'message' => "L'application ROBUS Web était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour cause de {$robus_hour_description}.",
                        ];

                        Cache::put('app_status_result', $result, now()->addMinutes(20)); // Mettre en cache pendant 60 minutes
                        return $result;
                }

                // Mettre à jour la mise en cache avec le résultat
                $result = ['success' => true];
                Cache::put('app_status_result', $result, now()->addMinutes(20)); // Mettre en cache pendant 60 minutes
                return $result;
        }

        public function appDispoMasterWebRobus($model)
        {
                // Vérifier d'abord si les données sont en cache
                $cachedResult = Cache::get('app_status_master_result');

                if ($cachedResult) {
                        return $cachedResult;
                }

                Artisan::call('app:close-master-status');

                // Récupérer le statut de l'application
                $appStatus = $model::latest()->first();

                if ($appStatus && $appStatus->status_app === 'closed') {

                        $robus_hour_begin = $appStatus->time_start;
                        $robus_hour_end = $appStatus->time_end;
                        $robus_hour_description = $appStatus->description;

                        // Mettre à jour la mise en cache avec le résultat
                        $result = [
                                'success' => false,
                                'message' => "L'application ROBUS Web était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour cause de {$robus_hour_description}.",
                        ];

                        Cache::put('app_status_master_result', $result, now()->addMinutes(20)); // Mettre en cache pendant 60 minutes
                        return $result;
                }

                // Mettre à jour la mise en cache avec le résultat
                $result = ['success' => true];
                Cache::put('app_status_master_result', $result, now()->addMinutes(20)); // Mettre en cache pendant 60 minutes
                return $result;
        }

        public function appDispoZoneWebRobus($model)
        {
                // Vérifier d'abord si les données sont en cache
                $cachedResult = Cache::get('app_status_zone_result');

                if ($cachedResult) {
                        return $cachedResult;
                }

                Artisan::call('app:close-comzone-status');

                // Récupérer le statut de l'application
                $appStatus = $model::latest()->first();

                if ($appStatus && $appStatus->status_app === 'closed') {

                        $robus_hour_begin = $appStatus->time_start;
                        $robus_hour_end = $appStatus->time_end;
                        $robus_hour_description = $appStatus->description;

                        // Mettre à jour la mise en cache avec le résultat
                        $result = [
                                'success' => false,
                                'message' => "L'application ROBUS Web était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour cause de {$robus_hour_description}.",
                        ];

                        Cache::put('app_status_zone_result', $result, now()->addMinutes(20)); // Mettre en cache pendant 60 minutes
                        return $result;
                }

                // Mettre à jour la mise en cache avec le résultat
                $result = ['success' => true];
                Cache::put('app_status_zone_result', $result, now()->addMinutes(20)); // Mettre en cache pendant 60 minutes
                return $result;
        }

        public function appDispoCompanieRobus($model)
        {



                $cachedResult = Cache::get('app_compagnie_result');

                if ($cachedResult) {
                        return $cachedResult;
                }


                Artisan::call('app:close-company-status');

                $appStatus = $model::latest()->first();

                if ($appStatus && $appStatus->status_app === 'closed') {

                        $robus_hour_begin = $appStatus->time_start;
                        $robus_hour_end = $appStatus->time_end;
                        $robus_hour_description = $appStatus->description;


                        $result = [
                                'success' => false,
                                'message' => "L'application ROBUS Compagnie était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour {$robus_hour_description}.",
                        ];

                        Cache::put('app_compagnie_result', $result, now()->addMinutes(10));
                        return $result;
                }

                $result = ['success' => true];
                Cache::put('app_compagnie_result', $result, now()->addMinutes(10));
                return $result;
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
