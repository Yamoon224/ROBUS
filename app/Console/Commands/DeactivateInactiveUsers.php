<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletXbet;


class DeactivateInactiveUsers extends Command
{
        protected $signature = 'users:deactivate-inactive';
        protected $description = 'Déconnecte les utilisateurs sans activité depuis 30 jours';

        public function handle()
        {
                $users = SidUser::where('is_under_distributer', true)->get();
                $inactiveCount = 0;

                foreach ($users as $user) {
                        $hasActivity = WalletPhoneCredit::where('wallet_id', $user->wallet->id)
                                ->where('created_at', '>=', now()->subDays(60))
                                ->exists()
                                || WalletTransaction::where('wallet_id', $user->wallet->id)
                                ->where('created_at', '>=', now()->subDays(60))
                                ->exists()
                                || WalletInter::where('wallet_id', $user->wallet->id)
                                ->where('created_at', '>=', now()->subDays(60))
                                ->exists()
                                || WalletPayment::where('wallet_id', $user->wallet->id)
                                ->where('created_at', '>=', now()->subDays(60))
                                ->exists()
                                || WalletBank::where('wallet_id', $user->wallet->id)
                                ->where('created_at', '>=', now()->subDays(60))
                                ->exists()
                                || WalletDeposit::where('wallet_id', $user->wallet->id)
                                ->where('created_at', '>=', now()->subDays(60))
                                ->exists()
                                || WalletDeposit::where('wallet_id', $user->wallet->id)
                                ->where('created_at', '>=', now()->subDays(60))
                                ->exists()
                                || WalletXbet::where('wallet_id', $user->wallet->id)
                                ->where('created_at', '>=', now()->subDays(60))
                                ->exists();

                        if (!$hasActivity && ($user->session_id || $user->session_expires_at)) {
                                $user->update([
                                        'session_id' => null,
                                        'session_expires_at' => null,
                                        'device_meta_data' => null,
                                ]);

                                $inactiveCount++;
                                $this->info("Utilisateur ID {$user->wallet->id} déconnecté pour inactivité.");
                        }
                }

                $this->info("Tâche terminée. $inactiveCount utilisateurs déconnectés.");
        }
}
