<?php

namespace App\Http\Services;

use App\Models\AdminsCom;
use App\Models\ComZoneGoalReward;
use App\Models\LedgerEntry;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use App\Models\WithMerchant;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletBankSend;
use App\Models\Wallets\WalletCommission;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ComZoneCommissionService
{
    protected array $serviceModelMap = [
        'DEPOT' => WalletTransaction::class,
        'CREDIT TELEPHONIQUE' => WalletPhoneCredit::class,
        'PAIEMENT DE FACTURE' => WalletPayment::class,
        'TRANSFERT INTERNATIONAL' => WalletInter::class,
        'RECHARGEMENT' => WalletReload::class,
        'XBET' => WalletXbet::class,
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
        'VERSEMENT BANCAIRE' => WalletVersement::class,
    ];

    /**
     * Crédite les objectifs atteints qui n'ont pas encore été payés.
     */
    public function rewardAchievedGoals(SidUser $comZone): array
    {
        return DB::transaction(function () use ($comZone) {
            $wallet = Wallet::where('sid_user_id', $comZone->id)->lockForUpdate()->first();


            if (!$wallet) {
                throw new \RuntimeException('Portefeuille COM ZONE introuvable.');
            }

            $walletCommission = WalletCommission::lockForUpdate()->find(1);

            if (!$walletCommission) {
                throw new \RuntimeException('Portefeuille de commission administrateur introuvable.');
            }

            $linkedUserIds = SidUser::where('com_zone_id', $comZone->id)->pluck('id');
            $walletIds = Wallet::whereIn('sid_user_id', $linkedUserIds)->pluck('id');
            $operatorServices = OperatorService::with('operator')
                ->where('sid_user_id', $comZone->id)
                ->lockForUpdate()
                ->get();


            $services = [];
            $totalReward = 0.0;

            foreach ($operatorServices as $operatorService) {
                $walletModel = $this->walletModelForOperatorName($operatorService->operator->name ?? '');
                $goalSignature = $this->goalSignature($operatorService);

                $transactionsCount = $walletModel ? $this->countTransactions($walletModel, $walletIds) : 0;
                $transactionsAmount = $walletModel ? $this->sumTransactions($walletModel, $walletIds) : 0.0;
                $reward = $operatorService->calculateComZoneCommission($transactionsCount, $transactionsAmount);

                $alreadyPaid = ComZoneGoalReward::where('operator_service_id', $operatorService->id)
                    ->where('goal_signature', $goalSignature)
                    ->exists();

                $paidNow = false;

                if (!$alreadyPaid && $reward > 0) {
                    if ((float) $walletCommission->amount < $reward) {
                        throw new \RuntimeException('Solde du portefeuille de commission administrateur insuffisant.');
                    }

                    $balanceBefore = (float) $wallet->amount;
                    $balanceAfter = $balanceBefore + $reward;
                    $transactionCode = Str::upper(Str::random(16));

                    $history = WithMerchant::create([
                        'amount_with_draw' => $reward,
                        'sid_user_id' => $comZone->id,
                        'date_removed' => Carbon::now(),
                        'balance_before' => $balanceBefore,
                        'amount_solde' => $balanceAfter,
                        'ref' => 'RB_ROBUS_' . $transactionCode,
                        'type_transaction' => 'Commission remonté',
                        'status' => 'SUCCESS',
                    ]);

                    $adminCommission = AdminsCom::create([
                        'amount' => $reward,
                        'amount_with_draw' => $reward,
                        'sid_user_id' => $comZone->id,
                        'wallet_commission_id' => $walletCommission->id,
                        'with_merchant_id' => $history->id,
                        'description' => 'Commission objectif COM ZONE',
                        'status' => 'SUCCESS',
                    ]);

                    $walletCommission->amount = (float) $walletCommission->amount - $reward;
                    $walletCommission->save();

                    LedgerEntry::create([
                        'transaction_id' => (string) Str::uuid(),
                        'admin_com_id' => $adminCommission->id,
                        'debit' => $reward,
                        'credit' => 0,
                        'description' => 'Commission objectif COM ZONE',
                    ]);

                    ComZoneGoalReward::create([
                        'sid_user_id' => $comZone->id,
                        'operator_service_id' => $operatorService->id,
                        'with_merchant_id' => $history->id,
                        'goal_signature' => $goalSignature,
                        'transactions_count' => $transactionsCount,
                        'transactions_amount' => $transactionsAmount,
                        'reward_amount' => $reward,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'paid_at' => Carbon::now(),
                    ]);

                    $wallet->update([
                        'reload_by' => trim($comZone->firstname . ' ' . $comZone->lastname),
                        'sid_user_relod' => $comZone->id,
                        'amount' => $balanceAfter,
                    ]);
                    $wallet->amount = $balanceAfter;
                    $totalReward += $reward;
                    $paidNow = true;

                    Log::info('Récompense objectif COM ZONE payée', [
                        'com_zone_id' => $comZone->id,
                        'operator_service_id' => $operatorService->id,
                        'reward' => $reward,
                        'wallet_balance_after' => $balanceAfter,
                        'commission_wallet_balance_after' => (float) $walletCommission->amount,
                        'reference' => 'RB_ROBUS_' . $transactionCode,
                    ]);
                }

                $services[] = [
                    'operator_service_id' => $operatorService->id,
                    'name' => $operatorService->operator->name ?? 'Service',
                    'transactions_count' => $transactionsCount,
                    'transactions_amount' => $transactionsAmount,
                    'numbers_goal' => (int) ($operatorService->numbers ?? 0),
                    'amount_goal' => (float) ($operatorService->amount ?? 0),
                    'reward_amount' => (float) ($operatorService->commission ?? 0),
                    'goal_reached' => $reward > 0 || $alreadyPaid,
                    'already_paid' => $alreadyPaid,
                    'paid_now' => $paidNow,
                ];
            }

            return [
                'total_recharged' => $totalReward,
                'wallet_balance' => (float) $wallet->amount,
                'commission_wallet_balance' => (float) $walletCommission->amount,
                'services' => $services,
            ];
        }, 3);
    }

    protected function goalSignature(OperatorService $operatorService): string
    {
        return hash('sha256', implode('|', [
            (int) ($operatorService->numbers ?? 0),
            (float) ($operatorService->amount ?? 0),
            (float) ($operatorService->commission ?? 0),
        ]));
    }

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

    public function transactionsForComZone(
        SidUser $comZone,
        int $limit = 10,
        ?Carbon $start = null,
        ?Carbon $end = null
    ): array
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
                    'numbers_goal' => (int) ($operatorService->numbers ?? 0),
                    'amount_goal' => (float) ($operatorService->amount ?? 0),
                    'progress' => 0,
                    'goal_reached' => false,
                    'transactions' => collect(),
                ];

                continue;
            }

            $baseQuery = $walletModel::whereIn('wallet_id', $walletIds)
                ->where('status', 'SUCCESS');

            if ($start && $end) {
                $baseQuery->whereBetween('created_at', [$start, $end]);
            }

            $transactionsCount = (clone $baseQuery)->count();
            $transactionsAmount = (float) (clone $baseQuery)->sum('amount');
            $commission = $operatorService->calculateComZoneCommission($transactionsCount, $transactionsAmount);
            $numbersGoal = (int) ($operatorService->numbers ?? 0);
            $amountGoal = (float) ($operatorService->amount ?? 0);
            $progressValues = [];

            if ($numbersGoal > 0) {
                $progressValues[] = min(100, ($transactionsCount / $numbersGoal) * 100);
            }

            if ($amountGoal > 0) {
                $progressValues[] = min(100, ($transactionsAmount / $amountGoal) * 100);
            }

            $progress = $progressValues ? min($progressValues) : 0;

            $transactionsQuery = (clone $baseQuery)->orderBy('created_at', 'desc');

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
                'numbers_goal' => $numbersGoal,
                'amount_goal' => $amountGoal,
                'progress' => round($progress, 1),
                'goal_reached' => $commission > 0,
                'period_start' => $start?->toDateTimeString(),
                'period_end' => $end?->toDateTimeString(),
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
