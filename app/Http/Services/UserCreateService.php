<?php

namespace App\Http\Services;


use App\Models\Custumers\Country;
use App\Models\Kyc;
use App\Models\Master;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserCreateService
{


        public function createUser($requestData, $role_id, $image, $imageRecto, $imageVerso, $is_master = false, $is_under_distributer = false)
        {
                $user = new SidUser();
                $role = Role::findOrFail($role_id);

                $verify_phone =  SidUser::wherePhone(robus()->country->indecative . $requestData['phone'])->first();


                if ($verify_phone) {
                        return ['success' => false, 'message' => "Le numero est déja utilisé par quelqu'un"];
                }


                $user->fill([
                        'firstname' => $requestData['firstname'],
                        'lastname' => $requestData['lastname'],
                        'company_id' => 1,
                        'country_id' => robus()->country->id ?? null,
                        'city_id' => $requestData['city'] ?? null,
                        'zone' => $requestData['zone'] ?? null,
                        'email' => $requestData['email'],
                        'phone' => $requestData['phone'],
                        'created_by' => robus()->id,
                        'password' => Hash::make($requestData['password']),
                        'is_master' => $is_master,
                        'is_under_distributer' => $is_under_distributer,
                ]);

                if ($image) {
                        $destination_path = public_path('profil/' . $user->id . '/');
                        $front_file = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destination_path, $front_file);
                        $user->image = env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
                }

                if (!$user->country->currencie) {
                        return ['success' => false, 'message' => 'Pas de devise dans ce pays. Veuillez activer la devise'];
                }

                $user->assignRole($role);

                $user->save();

                if ($imageRecto && $imageVerso) {
                        $destination_path = public_path('kyc_underDistributors/' . $user->id . '/');
                        $front_file = 'face_' .  date('YmdHis') . "." . $imageRecto->getClientOriginalExtension();
                        $back_file = 'arriere_' . date('YmdHis') . "." . $imageVerso->getClientOriginalExtension();
                        $imageRecto->move($destination_path, $front_file);
                        $imageVerso->move($destination_path, $back_file);

                        Kyc::updateOrCreate([
                                'sid_user_id' => $user->id,
                                'company_id' =>  $user->company->id
                        ], [
                                'motif' => 'Validation de compte',
                                'screen' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$front_file",
                                'screen_back' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$back_file",
                                'sid_user_id' => $user->id,
                                'status' => true,
                                'company_id' =>  $user->company->id
                        ]);
                }

                $walletCode = sprintf("%0.6s", str_shuffle(rand(40, 5000) * time() . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
                $wallet_user = Wallet::create([
                        'company_id' => 1,
                        'wallet_id' => $user->country->code . $walletCode,
                        'country_id' => $user->country->id,
                        'created_by' => robus()->id,
                        'sid_user_id' => $user->id,
                        'currencie_id' => $user->country->currencie->id,
                        'amount' => 0.0,
                        'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                        'status' => true
                ]);

                $user->update([
                        'company_id' => 1,
                        'wallet_id' => $wallet_user->wallet_id,
                        'master_id' => null,
                        'is_accepted' => false,
                        'phone' => $user->country->indecative . $user->phone,
                        'silver_ceiling' => $user->country->silver_ceiling
                ]);

                return ['success' => true, 'user' => $user];
        }

        public function createUserCustumer($requestData, $image, $imageRecto, $imageVerso)
        {
                $user = new SidUser();

                $verify_phone =  SidUser::wherePhone(robus()->country->indecative . $requestData['phone'])->first();


                if ($verify_phone) {
                        return ['success' => false, 'message' => "Le numero est déja utilisé par quelqu'un"];
                }

                $user->fill([
                        'firstname' => $requestData['firstname'],
                        'lastname' => $requestData['lastname'],
                        'company_id' => 1,
                        'country_id' => robus()->country->id ?? null,
                        'city_id' => $requestData['city'] ?? null,
                        'zone' => $requestData['zone'] ?? null,
                        'email' => $requestData['email'],
                        'phone' => $requestData['phone'],
                        'created_by' => robus()->id,
                        'password' => Hash::make($requestData['password']),
                        'is_custumers' => true,
                ]);

                if ($image) {
                        $destination_path = public_path('profil/' . $user->id . '/');
                        $front_file = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destination_path, $front_file);
                        $user->image = env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
                }

                if (!$user->country->currencie) {
                        return ['success' => false, 'message' => 'Pas de devise dans ce pays. Veuillez activer la devise'];
                }

                $user->save();

                if ($imageRecto && $imageVerso) {
                        $destination_path = public_path('kyc_underDistributors/' . $user->id . '/');
                        $front_file = 'face_' .  date('YmdHis') . "." . $imageRecto->getClientOriginalExtension();
                        $back_file = 'arriere_' . date('YmdHis') . "." . $imageVerso->getClientOriginalExtension();
                        $imageRecto->move($destination_path, $front_file);
                        $imageVerso->move($destination_path, $back_file);

                        Kyc::updateOrCreate([
                                'sid_user_id' => $user->id,
                                'company_id' =>  $user->company->id
                        ], [
                                'motif' => 'Validation de compte',
                                'screen' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$front_file",
                                'screen_back' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$back_file",
                                'sid_user_id' => $user->id,
                                'status' => true,
                                'company_id' =>  $user->company->id
                        ]);
                }

                $walletCode = sprintf("%0.6s", str_shuffle(rand(40, 5000) * time() . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
                $wallet_user = Wallet::create([
                        'company_id' => 1,
                        'wallet_id' => $user->country->code . $walletCode,
                        'country_id' => $user->country->id,
                        'created_by' => robus()->id,
                        'sid_user_id' => $user->id,
                        'currencie_id' => $user->country->currencie->id,
                        'amount' => 0.0,
                        'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                        'status' => true
                ]);

                $user->update([
                        'company_id' => 1,
                        'wallet_id' => $wallet_user->wallet_id,
                        'master_id' => null,
                        'is_accepted' => false,
                        'phone' => $user->country->indecative . $user->phone,
                        'silver_ceiling' => $user->country->silver_ceiling
                ]);

                return ['success' => true, 'user' => $user];
        }
        public function createUnderDistributor($requestData, $image, $imageRecto, $imageVerso)
        {
                return $this->createUser($requestData, 4, $image, $imageRecto, $imageVerso, false, true);
        }

        public function createUnderMaster($requestData, $image, $imageRecto, $imageVerso)
        {
                return $this->createUser($requestData, 7, $image, $imageRecto, $imageVerso, true, false);
        }


        public function createCustumer($requestData, $image, $imageRecto, $imageVerso)
        {

                return $this->createUserCustumer($requestData, $image, $imageRecto, $imageVerso);
        }


        // Master web 
        public function createUserUnderMaster($requestData, $role_id, $image, $imageRecto, $imageVerso, $is_custumers = false, $is_under_distributer = false)
        {
                $user = new SidUser();
                $role = Role::findOrFail($role_id);

                $verify_phone =  SidUser::wherePhone(master()->country->indecative . $requestData['phone'])->first();


                if ($verify_phone) {
                        return ['success' => false, 'message' => "Le numero est déja utilisé par quelqu'un"];
                }

                $user->fill([
                        'firstname' => $requestData['firstname'],
                        'lastname' => $requestData['lastname'],
                        'company_id' => 1,
                        'country_id' => master()->country->id ?? null,
                        'city_id' => $requestData['city_id'] ?? null,
                        'zone' => $requestData['zone'] ?? null,
                        'email' => $requestData['email'],
                        'phone' => $requestData['phone'],
                        'created_by' => master()->id,
                        'password' => Hash::make($requestData['password']),
                        'is_custumers' => $is_custumers,
                        'is_under_distributer' => $is_under_distributer,
                ]);

                if ($image) {
                        $destination_path = public_path('profil/' . $user->id . '/');
                        $front_file = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destination_path, $front_file);
                        $user->image = env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
                }

                if (!$user->country->currencie) {
                        return ['success' => false, 'message' => 'Pas de devise dans ce pays. Veuillez activer la devise'];
                }

                $user->assignRole($role);

                $user->save();

                if ($imageRecto && $imageVerso) {
                        $destination_path = public_path('kyc_underDistributors/' . $user->id . '/');
                        $front_file = 'face_' .  date('YmdHis') . "." . $imageRecto->getClientOriginalExtension();
                        $back_file = 'arriere_' . date('YmdHis') . "." . $imageVerso->getClientOriginalExtension();
                        $imageRecto->move($destination_path, $front_file);
                        $imageVerso->move($destination_path, $back_file);

                        Kyc::updateOrCreate([
                                'sid_user_id' => $user->id,
                                'company_id' =>  $user->company->id
                        ], [
                                'motif' => 'Validation de compte',
                                'screen' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$front_file",
                                'screen_back' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$back_file",
                                'sid_user_id' => $user->id,
                                'status' => true,
                                'company_id' =>  $user->company->id
                        ]);
                }

                $walletCode = sprintf("%0.6s", str_shuffle(rand(40, 5000) * time() . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
                $wallet_user = Wallet::create([
                        'company_id' =>   1,
                        'wallet_id' =>   $user->country->code . $walletCode,
                        'country_id' =>  $user->country->id,
                        'created_by' => master()->id,
                        'sid_user_id' =>  $user->id,
                        'currencie_id' =>  $user->country->currencie->id,
                        'amount' => 0.0,
                        'reload_by' => master()->firstname . ' ' . master()->lastname,
                        'status' => true
                ]);

                $user->update([
                        'company_id'  => 1,
                        'wallet_id'  => $wallet_user->wallet_id,
                        'master_id'  => master()->id,
                        'is_accepted' => false,
                        'phone'  =>   $user->country->indecative . $user->phone,
                        'silver_ceiling'  =>   $user->country->silver_ceiling
                ]);

                return ['success' => true, 'user' => $user];
        }

        public function createUserCustumerMaster($requestData, $image, $imageRecto, $imageVerso)
        {
                $user = new SidUser();

                $verify_phone =  SidUser::wherePhone(master()->country->indecative . $requestData['phone'])->first();


                if ($verify_phone) {
                        return ['success' => false, 'message' => "Le numero est déja utilisé par quelqu'un"];
                }

                $user->fill([
                        'firstname' => $requestData['firstname'],
                        'lastname' => $requestData['lastname'],
                        'company_id' => 1,
                        'country_id' => master()->country->id ?? null,
                        'city_id' => $requestData['city'] ?? null,
                        'zone' => $requestData['zone'] ?? null,
                        'email' => $requestData['email'],
                        'phone' => $requestData['phone'],
                        'created_by' => master()->id,
                        'password' => Hash::make($requestData['password']),
                        'is_custumers' => true,
                ]);

                if ($image) {
                        $destination_path = public_path('profil/' . $user->id . '/');
                        $front_file = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destination_path, $front_file);
                        $user->image = env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
                }

                if (!$user->country->currencie) {
                        return ['success' => false, 'message' => 'Pas de devise dans ce pays. Veuillez activer la devise'];
                }

                $user->save();

                if ($imageRecto && $imageVerso) {
                        $destination_path = public_path('kyc_underDistributors/' . $user->id . '/');
                        $front_file = 'face_' .  date('YmdHis') . "." . $imageRecto->getClientOriginalExtension();
                        $back_file = 'arriere_' . date('YmdHis') . "." . $imageVerso->getClientOriginalExtension();
                        $imageRecto->move($destination_path, $front_file);
                        $imageVerso->move($destination_path, $back_file);

                        Kyc::updateOrCreate([
                                'sid_user_id' => $user->id,
                                'company_id' =>  $user->company->id
                        ], [
                                'motif' => 'Validation de compte',
                                'screen' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$front_file",
                                'screen_back' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$back_file",
                                'sid_user_id' => $user->id,
                                'status' => true,
                                'company_id' =>  $user->company->id
                        ]);
                }

                $walletCode = sprintf("%0.6s", str_shuffle(rand(40, 5000) * time() . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
                $wallet_user = Wallet::create([
                        'company_id' =>   1,
                        'wallet_id' =>   $user->country->code . $walletCode,
                        'country_id' =>  $user->country->id,
                        'created_by' => master()->id,
                        'sid_user_id' =>  $user->id,
                        'currencie_id' =>  $user->country->currencie->id,
                        'amount' => 0.0,
                        'reload_by' => master()->firstname . ' ' . master()->lastname,
                        'status' => true
                ]);

                $user->update([
                        'company_id'  => 1,
                        'wallet_id'  => $wallet_user->wallet_id,
                        'master_id'  => master()->id,
                        'is_accepted' => false,
                        'phone'  =>   $user->country->indecative . $user->phone,
                        'silver_ceiling'  =>   $user->country->silver_ceiling
                ]);
                return ['success' => true, 'user' => $user];
        }

        public function createUnderMasterWeb($requestData, $image, $imageRecto, $imageVerso)
        {
                return $this->createUserUnderMaster($requestData, 4, $image, $imageRecto, $imageVerso, false, true);
        }


        public function createCustumerMasterWeb($requestData, $image, $imageRecto, $imageVerso)
        {
                return $this->createUserCustumerMaster($requestData, $image, $imageRecto, $imageVerso);
        }


        // Masters API

        public function createUserUnderMasterApi($requestData, $role_id, $image, $imageRecto, $imageVerso, $is_under_distributer = false)
        {
                $user = new SidUser();
                $role = Role::findOrFail($role_id);

                $verify_phone =  SidUser::wherePhone(robus()->country->indecative . $requestData['phone'])->first();


                if ($verify_phone) {
                        return ['success' => false, 'message' => "Le numero est déja utilisé par quelqu'un"];
                }

                $user->fill([
                        'firstname' => $requestData['firstname'],
                        'lastname' => $requestData['lastname'],
                        'company_id' => 1,
                        'country_id' => robus()->country->id ?? null,
                        'city_id' => $requestData['city'] ?? null,
                        'zone' => $requestData['zone'] ?? null,
                        'email' => $requestData['email'],
                        'phone' => $requestData['phone'],
                        'created_by' => robus()->id,
                        'password' => Hash::make($requestData['password']),
                        'is_under_distributer' => $is_under_distributer,
                ]);

                if ($image) {
                        $destination_path = public_path('profil/' . $user->id . '/');
                        $front_file = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destination_path, $front_file);
                        $user->image = env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
                }

                if (!$user->country->currencie) {
                        return ['success' => false, 'message' => 'Pas de devise dans ce pays. Veuillez activer la devise'];
                }

                $user->assignRole($role);

                $user->save();

                if ($imageRecto && $imageVerso) {
                        $destination_path = public_path('kyc_underDistributors/' . $user->id . '/');
                        $front_file = 'face_' .  date('YmdHis') . "." . $imageRecto->getClientOriginalExtension();
                        $back_file = 'arriere_' . date('YmdHis') . "." . $imageVerso->getClientOriginalExtension();
                        $imageRecto->move($destination_path, $front_file);
                        $imageVerso->move($destination_path, $back_file);

                        Kyc::updateOrCreate([
                                'sid_user_id' => $user->id,
                                'company_id' =>  $user->company->id
                        ], [
                                'motif' => 'Validation de compte',
                                'screen' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$front_file",
                                'screen_back' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$back_file",
                                'sid_user_id' => $user->id,
                                'status' => true,
                                'company_id' =>  $user->company->id
                        ]);
                }

                $walletCode = sprintf("%0.6s", str_shuffle(rand(40, 5000) * time() . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
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
                        'master_id'  => robus()->id,
                        'is_accepted' => false,
                        'phone'  =>   $user->country->indecative . $user->phone,
                        'silver_ceiling'  =>   $user->country->silver_ceiling
                ]);

                return ['success' => true, 'user' => $user];
        }


        public function createUnderMasterApi($requestData, $image, $imageRecto, $imageVerso)
        {
                return $this->createUserUnderMasterApi($requestData, 4, $image, $imageRecto, $imageVerso, true);
        }

        // Create User admin 
        public function createUserMasterWeb($requestData, $role_id, $image, $imageRecto, $imageVerso, $is_masters = false, $is_com_zone = false)
        {
                $user = new SidUser();
                $role = Role::findOrFail($role_id);

                // Phone search
                $phone_countrie = Country::whereId($requestData['country_id'])->first();

                $verify_phone =  SidUser::wherePhone($phone_countrie->indecative . $requestData['phone'])->first();


                if ($verify_phone) {
                        return ['success' => false, 'message' => "Le numero est déja utilisé par quelqu'un"];
                }

                $user->fill([
                        'firstname' => $requestData['firstname'],
                        'lastname' => $requestData['lastname'],
                        'company_id' => 1,
                        'country_id' => $requestData['country_id'] ?? null,
                        'city_id' => $requestData['city'] ?? null,   
                        'zone' => $requestData['zone'] ?? null,
                        'email' => $requestData['email'],
                        'phone' => $requestData['phone'],
                        'created_by' => sabdy()->id,
                        'password' => Hash::make($requestData['password']),
                        'is_master' => $is_masters,
                        'is_com_zone' => $is_com_zone,
                ]);

                if ($image) {
                        $destination_path = public_path('profil/' . $user->id . '/');
                        $front_file = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destination_path, $front_file);
                        $user->image = env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
                }

                if (!$user->country->currencie) {
                        return ['success' => false, 'message' => 'Pas de devise dans ce pays. Veuillez activer la devise'];
                }

                $user->assignRole($role);

                $user->save();

                if ($imageRecto && $imageVerso) {
                        $destination_path = public_path('kyc_master/' . $user->id . '/');
                        $front_file = 'face_' .  date('YmdHis') . "." . $imageRecto->getClientOriginalExtension();
                        $back_file = 'arriere_' . date('YmdHis') . "." . $imageVerso->getClientOriginalExtension();
                        $imageRecto->move($destination_path, $front_file);
                        $imageVerso->move($destination_path, $back_file);

                        Kyc::updateOrCreate([
                                'sid_user_id' => $user->id,
                                'company_id' =>  $user->company->id
                        ], [
                                'motif' => 'Validation de compte',
                                'screen' => env('APP_URL') . "/public/kyc_master/" . $user->id . "/$front_file",
                                'screen_back' => env('APP_URL') . "/public/kyc_master/" . $user->id . "/$back_file",
                                'sid_user_id' => $user->id,
                                'status' => true,
                                'company_id' =>  $user->company->id
                        ]);
                }

                Master::create([
                        'city_id' =>  $user->city_id,
                        'lastname' =>  $user->lastname,
                        'firstname' =>  $user->firstname,
                        'country_id' =>  $requestData['country_id'],
                        'email' =>  $user->email,
                        'phone' =>  $user->country->indecative .  $user->phone,
                        'zone' =>  $user->zone,
                        'is_master' => $user->is_master == true ? true : false,
                        'currency_id' =>  $user->country->currencie->id,
                        'status' => true
                ]);

                $walletCode = sprintf("%0.6s", str_shuffle(rand(40, 5000) * time() . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ')));


                $wallet_user = Wallet::create([
                        'company_id' =>   1,
                        'wallet_id' =>   $user->country->code . $walletCode,
                        'country_id' =>  $user->country->id,
                        'created_by' => sabdy()->id,
                        'sid_user_id' =>  $user->id,
                        'currencie_id' =>  $user->country->currencie->id,
                        'amount' => 0.0,
                        'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                        'status' => true
                ]);

                $user->update([
                        'company_id'  => 1,
                        'wallet_id'  => $wallet_user->wallet_id,
                        // 'master_id'  => $user->id,
                        'is_accepted' => false,
                        'phone'  =>   $user->country->indecative . $user->phone,
                        'silver_ceiling'  =>   $user->country->silver_ceiling
                ]);

                return ['success' => true, 'user' => $user];
        }

        public function createMasterWeb($requestData, $image, $imageRecto, $imageVerso)
        {
                return $this->createUserMasterWeb($requestData, 7, $image, $imageRecto, $imageVerso, true, false);
        }

        public function createComZoneWeb($requestData, $image, $imageRecto, $imageVerso)
        {
                return $this->createUserMasterWeb($requestData, 8, $image, $imageRecto, $imageVerso, false, true);
        }
}
