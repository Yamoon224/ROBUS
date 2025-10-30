<?php

namespace App\Http\Services;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Sid\SidUser;

class HomeService
{

 public function getSubDistributorsWalletTransactionRanking($walletModel, $filters = [])
    {
        $query = $walletModel::select(
            'sid_users.firstname as sub_distributor',
            'sid_users.id as sub_userId',
            'sid_users.email as sub_email',
            'countries.name as country_name',
            'currencies.code as currency_code',
            'providers.name as provider',
            DB::raw('SUM(wallets.amount) as wallet_balance'),
            DB::raw('SUM(wallet_transactions.amount) as total_amount'),
            DB::raw('COUNT(wallet_transactions.id) as total_transactions')
        )
        ->join('wallets', 'wallet_transactions.wallet_id', '=', 'wallets.id')
        ->join('sid_users', 'wallets.sid_user_id', '=', 'sid_users.id')
        ->join('providers', 'wallet_transactions.provider_id', '=', 'providers.id')
        ->join('countries', 'sid_users.country_id', '=', 'countries.id')
        ->join('currencies', 'wallets.currencie_id', '=', 'currencies.id')
        ->where('wallet_transactions.status', 'SUCCESS')
        ->where('sid_users.is_under_distributer', true);

        // Appliquer les filtres
        if (!empty($filters['date_start']) && !empty($filters['date_end'])) {
            $query->whereBetween('wallet_transactions.created_at', [$filters['date_start'], $filters['date_end']]);
        }

        if (!empty($filters['provider_id'])) {
            $query->where('wallet_transactions.provider_id', $filters['provider_id']);
        }

        // GroupBy
        $transactions = $query->groupBy(
            'sid_users.id',
            'providers.id',
            'countries.id',
            'currencies.id',
            'countries.name',
            'providers.name',
            'currencies.code'
        )
        ->orderByDesc('total_amount')
        ->get();

        return $transactions->groupBy('country_name')->map(function($subDistributors) {
            return $subDistributors->map(function($t) {
                return [
                    'sub_distributor' => $t->sub_distributor ?? 'N/A',
                    'sub_countrie' => $t->country_name ?? 'N/A',
                    'currency' => $t->currency_code ?? 'XOF',
                    'sub_userId' => $t->sub_userId ?? 'N/A',
                    'sub_email' => $t->sub_email ?? 'N/A',
                    'wallet_balance' => $t->wallet_balance ?? 0,
                    'provider' => $t->provider ?? 'N/A',
                    'total_amount' => $t->total_amount ?? 0,
                    'total_transactions' => $t->total_transactions ?? 0
                ];
            });
        });
    }


    public function getUserTypeTotal($user, $status)
    {

        $totalUsers = SidUser::where($user,  $status)->count();

        return $totalUsers;
    }


    public function getTodayReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return $totalAmount;
    }

    public function getDaysReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth(DB::raw('created_at'), now()->month)
            ->sum('amount');

        return $totalAmount;
    }


    public function getTransactionCountReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return $totalCount;
    }


    public function getTransactionAllCount($walletModel, $wallet_transactions, $transaction_days, $walletMy)
    {

        $countryId = sabdy()->country->id;

        $query =  $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->whereDate($transaction_days, Carbon::today());

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, sabdy()->wallet->id);
            });
        } else {

            $query->where('wallets.country_id', '!=', $countryId)
                ->orWhere('wallets.country_id', '=', $countryId);
        }


        return $query->count();
    }


    // count valeurs
    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status);

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, sabdy()->wallet->id);
            });
        } else {

            $query->where(function ($query) use ($walletMy) {
                $query->where($walletMy, sabdy()->id)
                    ->orWhere($walletMy, '!=', sabdy()->id);
            })
                ->where(function ($query) {
                    $countryId = sabdy()->country->id;
                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                });
        }

        return $query->count();
    }

    // Sum amount days valeurs
    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } else {

            $query->where(function ($query) use ($walletMy) {
                $query->where($walletMy, sabdy()->id)
                    ->orWhere($walletMy, '!=', sabdy()->id);
            })
                ->where(function ($query) {
                    $countryId = sabdy()->country->id;
                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                });
        }



        return $query->sum($wallet_amount);
    }

    public function getTodayTransactionProvidersAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount, $providers)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where('provider_id', $providers)
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } else {

            $query->where(function ($query) use ($walletMy) {
                $query->where($walletMy, sabdy()->id)
                    ->orWhere($walletMy, '!=', sabdy()->id);
            })
                ->where(function ($query) {
                    $countryId = sabdy()->country->id;
                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                });
        }



        return $query->sum($wallet_amount);
    }

    public function getTodayTransactionProviderAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount, $providerId)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where('provider_id', $providerId)
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } else {

            $query->where(function ($query) use ($walletMy) {
                $query->where($walletMy, sabdy()->id)
                    ->orWhere($walletMy, '!=', sabdy()->id);
            })
                ->where(function ($query) {
                    $countryId = sabdy()->country->id;
                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                });
        }



        return $query->sum($wallet_amount);
    }

    public function getTodayTransactionProviderTypeOneAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {


        if (sabdy()->is_distributer) {

            $query = $walletModel::whereDate($transaction_days, Carbon::today())
                ->where($transaction_status, 'SUCCESS');

            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {

            $query = $walletModel::join('wallets', 'wallets.id', $wallet_transaction)
                ->whereDate($transaction_days, Carbon::today())
                ->where($transaction_status, 'SUCCESS');

            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } else {

            $query = $walletModel::whereDate($transaction_days, Carbon::today())
                ->where($transaction_status, 'SUCCESS');

            $query->where(function ($query) use ($walletMy) {
                $query->where($walletMy, sabdy()->id)
                    ->orWhere($walletMy, '!=', sabdy()->id);
            });
        }


        $results = $query->get();


        $totalAmountConv = 0;

        foreach ($results as $result) {

            $countryRobus = $result->country;

            $wallet_amount = (float)$result->amount_received;

            if (sabdy()->country->currencie->value > $countryRobus->currencie->value) {
                $wallet_amount_conv = $wallet_amount * $countryRobus->currencie->value;
            } elseif (sabdy()->country->currencie->value == $countryRobus->currencie->value) {
                $wallet_amount_conv = $wallet_amount;
            } elseif (sabdy()->country->currencie->value < $countryRobus->currencie->value) {
                $wallet_amount_conv = $wallet_amount * $countryRobus->currencie->value;
            }

            $totalAmountConv += $wallet_amount_conv;
        }

        return $totalAmountConv;
    }

    public function getTodayTransactionProviderOneAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount, $providerId)
    {

        $query = $walletModel::join('wallets', $wallet_transaction,  'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->whereProviderType($providerId)
            ->where($transaction_status, 'SUCCESS');

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } else {
            // Cas où ce n'est ni un distributeur ni un sous-distributeur
            $query->where(function ($query) use ($walletMy) {
                $query->where($walletMy, sabdy()->id)
                    ->orWhere($walletMy, '!=', sabdy()->id);
            })
                ->where(function ($query) {
                    $countryId = sabdy()->country->id;
                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                });
        }

        return $query->sum($wallet_amount);
    }


    // Sum amount month valeurs
    public function getDaysTransaction($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereMonth($transaction_days, now()->month)
            ->where($transaction_status, 'SUCCESS');

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } else {

            $query->where(function ($query) use ($walletMy) {
                $query->where($walletMy, sabdy()->id)
                    ->orWhere($walletMy, '!=', sabdy()->id);
            })
                ->where(function ($query) {
                    $countryId = sabdy()->country->id;
                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                });
        }

        return $query->sum($wallet_amount);
    }
}
