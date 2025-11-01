<?php

namespace App\Http\Livewire\Rankings;

use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletBankSend;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletBank;


class RankingAuthers extends Component
{

    public function render()
    {

        $classment_transactions = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFiveRanking(WalletTransaction::class, 'wallet_transactions');

        $classment_bank_sends = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFiveRanking(WalletBankSend::class, 'wallet_bank_sends');

        $classment_reloadCards = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFiveRanking(WalletDeposit::class, 'wallet_deposits');

        $classment_paiements = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFiveRanking(WalletPayment::class, 'wallet_payments');

        $classment_creditPhone = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFiveRanking(WalletPhoneCredit::class, 'wallet_phone_credits');

        $classment_Inters = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFiveRanking(WalletInter::class, 'wallet_inters');

        $classment_virements = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFiveRanking(WalletBank::class, 'wallet_banks');

        return view('livewire.rankings.ranking-authers', [
            'classment_transactions' => $classment_transactions,
            'classment_reloadCards' => $classment_reloadCards,
            'classment_paiements' => $classment_paiements,
            'classment_virements' => $classment_virements,
            'classment_creditPhone' => $classment_creditPhone,
            'classment_Inters' => $classment_Inters,
            'classment_bank_sends' => $classment_bank_sends
        ]);
    }
}
