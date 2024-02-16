<?php

namespace App\Http\Services;


use App\Models\Kyc;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Facades\Hash;

class UserCreateService
{

        public function createUnderDistributor($requestData, $image, $imageRecto, $imageVerso)
        {
                $user = new SidUser();

                $user->fill([
                        'firstname' => $requestData['firstname'],
                        'lastname' => $requestData['lastname'],
                        'company_id' => 1,
                        'country_id' => robus()->country->id ?? null,
                        'city_id' => $requestData['city'] ?? null,
                        'zone_id' => $requestData['zone_id'] ?? null,
                        'zone' => $requestData['zone'] ?? null,
                        'email' => $requestData['email'],
                        'phone' => $requestData['phone'],
                        'created_by' => robus()->id,
                        'password' => Hash::make($requestData['password']),
                        'is_under_distributer' => true,
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
                        $front_file =  'face_' .  date('YmdHis') . "." . $imageRecto->getClientOriginalExtension();
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
}
