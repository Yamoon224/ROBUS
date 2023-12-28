<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('App.Models.Sid.SidUser.{id}', function ($user, $id) {
    return  (int) $user->id === (int) $id;
});

Broadcast::channel('wallet-bank-created.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletBank::find($id)->wallet->sid_user_id;
});

Broadcast::channel('wallet-reload-created.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletReload::find($id)->wallet->sid_user_id;
});

Broadcast::channel('wallet-phone-created.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletPhoneCredit::find($id)->wallet->sid_user_id;
});

Broadcast::channel('wallet-inter-created.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletInter::find($id)->wallet->sid_user_id;
});

Broadcast::channel('wallet-paiement-created.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletPayment::find($id)->wallet->sid_user_id;
});

Broadcast::channel('wallet-card-created.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletDeposit::find($id)->wallet->sid_user_id;
});

Broadcast::channel('wallet-transfers-created.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletTransaction::find($id)->wallet->sid_user_id;
},);


Broadcast::channel('updated_bank.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletBank::find($id)->wallet->sid_user_id;
});

Broadcast::channel('updated_inter.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletInter::find($id)->wallet->sid_user_id;
});
Broadcast::channel('updated_paiement.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletPayment::find($id)->wallet->sid_user_id;
});
Broadcast::channel('updated_phone.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletPhoneCredit::find($id)->wallet->sid_user_id;
});

Broadcast::channel('updated_card.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletDeposit::find($id)->wallet->sid_user_id;
});

Broadcast::channel('updated_reload.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletReload::find($id)->wallet->sid_user_id;
});

Broadcast::channel('updated_transfers.{id}', function ($user, $id) {
    return  (int) $user->id === (int) \App\Models\Wallets\WalletTransaction::find($id)->wallet->sid_user_id;
});