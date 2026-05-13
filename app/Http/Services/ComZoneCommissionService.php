<?php

namespace App\Http\Services;

use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletBankSend;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;

class ComZoneCommissionService
{
    protected array $serviceModelMap = [
        'Active Transactions' => WalletTransaction::class,
        'Active Crédits' => WalletPhoneCredit::class,
        'Active Paiements' => WalletPayment::class,
        'Active International' => WalletInter::class,
        'Active Rechargements' => WalletReload::class,
        'Active Virements' => WalletBank::class,
        'Active Commande' => WalletDeposit::class,
        'Active Push' => WalletPush::class,
        'Active Djamo' => WalletDjamo::class,
        'Active Xbets' => WalletXbet::class,
        'Active Bank Send' => WalletBankSend::class,
        'Active Wave' => WalletWave::class,
        'Active Tresort Money' => WalletTresortMoney::class,
        'Active Versements' => WalletVersement::class,
    ];

    public function calculateForComZone(SidUser $comZone): array
    {
        $linkedUserIds = SidUser::where('com_zone_id', $comZone->id)->pluck('id');
        $walletIds = Wallet::whereIn('sid_user_id', $linkedUserIds)->pluck('id');
        $operatorServices = OperatorService::with('operator')
            ->where('sid_user_id', $comZone->id)
            ->get();

        $services = [];
        $totalTransactions = 0;
        $totalAmount = 0;
        $totalCommission = 0;

        foreach ($operatorServices as $operatorService) {
            $walletModel = $this->walletModelForOperatorName($operatorService->operator->name ?? '');

            if (!$walletModel) {
                continue;
            }

            $transactionsCount = $this->countTransactions($walletModel, $walletIds);
            $transactionsAmount = $this->sumTransactions($walletModel, $walletIds);
            $commission = $operatorService->calculateComZoneCommission($transactionsCount, $transactionsAmount);

            $services[] = [
                'name' => $operatorService->operator->name ?? 'Service',
                'transactions_count' => $transactionsCount,
                'transactions_amount' => $transactionsAmount,
                'commission' => $commission,
                'numbers_rule' => $operatorService->numbers,
                'amount_rule' => $operatorService->amount,
            ];

            $totalTransactions += $transactionsCount;
            $totalAmount += $transactionsAmount;
            $totalCommission += $commission;
        }

        return [
            'linked_users_count' => $linkedUserIds->count(),
            'transactions_count' => $totalTransactions,
            'transactions_amount' => $totalAmount,
            'commission' => $totalCommission,
            'services' => $services,
        ];
    }

    public function transactionsForComZone(SidUser $comZone, int $limit = 10): array
    {
        $linkedUserIds = SidUser::where('com_zone_id', $comZone->id)->pluck('id');
        $walletIds = Wallet::whereIn('sid_user_id', $linkedUserIds)->pluck('id');
        $operatorServices = OperatorService::with('operator')
            ->where('sid_user_id', $comZone->id)
            ->get();

        $services = [];

        foreach ($operatorServices as $operatorService) {
            $walletModel = $this->walletModelForOperatorName($operatorService->operator->name ?? '');

            if (!$walletModel) {
                $services[] = [
                    'name' => $operatorService->operator->name ?? 'Service',
                    'transactions_count' => 0,
                    'transactions_amount' => 0,
                    'commission' => 0,
                    'transactions' => collect(),
                ];

                continue;
            }

            $transactionsCount = $this->countTransactions($walletModel, $walletIds);
            $transactionsAmount = $this->sumTransactions($walletModel, $walletIds);
            $commission = $operatorService->calculateComZoneCommission($transactionsCount, $transactionsAmount);

            $transactionsQuery = $walletModel::whereIn('wallet_id', $walletIds)
                ->where('status', 'SUCCESS')
                ->orderBy('created_at', 'desc');

            if (method_exists($walletModel, 'wallet')) {
                $transactionsQuery->with('wallet.sidUser');
            }

            if (method_exists($walletModel, 'provider')) {
                $transactionsQuery->with('provider');
            }

            $services[] = [
                'name' => $operatorService->operator->name ?? 'Service',
                'transactions_count' => $transactionsCount,
                'transactions_amount' => $transactionsAmount,
                'commission' => $commission,
                'transactions' => $transactionsQuery->limit($limit)->get(),
            ];
        }

        return $services;
    }

    public function calculateForService(SidUser $comZone, int $operatorId, string $walletModel): array
    {
        $operatorService = OperatorService::where('sid_user_id', $comZone->id)
            ->where('operator_id', $operatorId)
            ->first();

        if (!$operatorService) {
            return [
                'transactions_count' => 0,
                'transactions_amount' => 0,
                'commission' => 0,
            ];
        }

        $linkedUserIds = SidUser::where('com_zone_id', $comZone->id)->pluck('id');
        $walletIds = Wallet::whereIn('sid_user_id', $linkedUserIds)->pluck('id');

        $transactionsCount = $walletModel::whereIn('wallet_id', $walletIds)
            ->where('status', 'SUCCESS')
            ->count();

        $transactionsAmount = (float) $walletModel::whereIn('wallet_id', $walletIds)
            ->where('status', 'SUCCESS')
            ->sum('amount');

        return [
            'transactions_count' => $transactionsCount,
            'transactions_amount' => $transactionsAmount,
            'commission' => $operatorService->calculateComZoneCommission($transactionsCount, $transactionsAmount),
        ];
    }

    protected function walletModelForOperatorName(string $operatorName): ?string
    {
        foreach (explode(',', $operatorName) as $name) {
            $name = trim($name);

            if (isset($this->serviceModelMap[$name])) {
                return $this->serviceModelMap[$name];
            }
        }

        return null;
    }

    protected function countTransactions(string $walletModel, $walletIds): int
    {
        return $walletModel::whereIn('wallet_id', $walletIds)
            ->where('status', 'SUCCESS')
            ->count();
    }

    protected function sumTransactions(string $walletModel, $walletIds): float
    {
        return (float) $walletModel::whereIn('wallet_id', $walletIds)
            ->where('status', 'SUCCESS')
            ->sum('amount');
    }
}
