<?php

namespace App\Http\Services;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Sid\SidUser;

class HomeService
{

    public function getSubDistributorsWalletTransactionFilterRanking($walletModel, string $walletTransactionTable = 'wallet_transactions', array $filters = [])
    {
        $query = $walletModel::select(
            'sid_users.id as sub_userId',
            'sid_users.firstname as sub_distributor',
            'sid_users.email as sub_email',
            DB::raw('MAX(wallets.amount) as wallet_balance'),
            'countries.name as country_name',
            'cities.name as citie_name',
            'currencies.code as currency_code',
            'providers.name as provider',
            DB::raw("SUM({$walletTransactionTable}.amount) as total_amount"),
            DB::raw("COUNT({$walletTransactionTable}.id) as total_transactions"),
            DB::raw("MAX({$walletTransactionTable}.created_at) as last_transaction_date"),
        )
            ->join('wallets', "{$walletTransactionTable}.wallet_id", '=', 'wallets.id')
            ->join('currencies', 'wallets.currencie_id', '=', 'currencies.id')
            ->join('sid_users', 'wallets.sid_user_id', '=', 'sid_users.id')
            ->join('providers', "{$walletTransactionTable}.provider_id", '=', 'providers.id')
            ->join('countries', 'sid_users.country_id', '=', 'countries.id')
            ->join('cities', 'sid_users.city_id', '=', 'cities.id')
            ->where("{$walletTransactionTable}.status", 'SUCCESS')
            ->where('sid_users.is_under_distributer', true);

        // ✅ Filtres dynamiques
        if (!empty($filters['provider_id'])) {
            $query->where("{$walletTransactionTable}.provider_id", $filters['provider_id']);
        }

        // ✅ Filtre par pays
        if (!empty($filters['country_id'])) {
            $query->where('sid_users.country_id', $filters['country_id']);
        }

        // ✅ Filtre période
        if (!empty($filters['periode'])) {
            switch ($filters['periode']) {
                case 'today':
                    $query->whereDate("{$walletTransactionTable}.created_at", now());
                    break;
                case 'week':
                    $query->whereBetween("{$walletTransactionTable}.created_at", [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween("{$walletTransactionTable}.created_at", [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
                case 'year':
                default:
                    // ✅ Période par défaut = année en cours
                    $query->whereYear("{$walletTransactionTable}.created_at", now()->year);
                    break;
            }
        } else {
            // ✅ par défaut, filtrer sur l’année en cours
            $query->whereYear("{$walletTransactionTable}.created_at", now()->year);
        }

        // ✅ Groupements corrects
        $transactions = $query
            ->groupBy(
                'sid_users.id',
                'sid_users.firstname',
                'sid_users.email',
                'providers.id',
                'providers.name',
                'countries.id',
                'cities.id',
                'cities.name',
                'countries.name',
                'currencies.code'
            )
            ->orderByDesc('total_amount')
            ->get();

        // ✅ Transformation du résultat pour la vue
        $ranking = $transactions->map(function ($t) {
            return [
                'sub_distributor'     => $t->sub_distributor ?? 'N/A',
                'sub_countrie'        => $t->country_name ?? 'N/A',
                'sub_citie' => $t->citie_name ?? 'N/A',
                'sub_userId'          => $t->sub_userId ?? 'N/A',
                'sub_email'           => $t->sub_email ?? 'N/A',
                'wallet_balance'      => (float) $t->wallet_balance ?? 0,
                'currency'            => $t->currency_code ?? 'N/A',
                'provider'            => $t->provider ?? 'N/A',
                'total_amount'        => (float) $t->total_amount ?? 0,
                'total_transactions'  => (int) $t->total_transactions ?? 0,
                'last_transaction_date' => $t->last_transaction_date
                    ? \Carbon\Carbon::parse($t->last_transaction_date)->format('d/m/Y')
                    : 'N/A',
            ];
        });

        // ✅ Regrouper par pays pour l’affichage
        return $ranking->groupBy('sub_countrie')->map(fn($group) => $group->values());
    }



    public function getSubDistributorsWalletTransactionRanking($walletModel)
    {
        $transactions = $walletModel::select(
            'sid_users.firstname as sub_distributor',
            'sid_users.id as sub_userId',
            'sid_users.email as sub_email',
            'countries.name as country_name',
            'cities.name as citie_name',
            'currencies.code as currency_code',
            DB::raw('MAX(wallet_transactions.created_at) as last_transaction_date'),
            DB::raw('MAX(wallets.amount) as wallet_balance'),
            'providers.name as provider',
            DB::raw('SUM(wallet_transactions.amount) as total_amount'),
            DB::raw('COUNT(wallet_transactions.id) as total_transactions')
        )
            ->join('wallets', 'wallet_transactions.wallet_id', '=', 'wallets.id')
            ->join('currencies', 'wallets.currencie_id', '=', 'currencies.id')
            ->join('sid_users', 'wallets.sid_user_id', '=', 'sid_users.id')
            ->join('providers', 'wallet_transactions.provider_id', '=', 'providers.id')
            ->join('countries', 'sid_users.country_id', '=', 'countries.id')
            ->join('cities', 'sid_users.city_id', '=', 'cities.id')
            ->where('wallet_transactions.status', 'SUCCESS')
            ->where('sid_users.is_under_distributer', true)
            ->groupBy(
                'sid_users.id',
                'sid_users.firstname',
                'sid_users.email',
                'providers.id',
                'providers.name',
                'countries.id',
                'cities.id',
                'countries.name',
                'cities.name',
                'currencies.code'
            )
            ->orderByDesc('total_amount')
            ->get();

        $ranking = $transactions->map(function ($t) {
            return [
                'sub_distributor' => $t->sub_distributor ?? 'N/A',
                'sub_countrie' => $t->country_name ?? 'N/A',
                'sub_citie' => $t->citie_name ?? 'N/A',
                'sub_userId' => $t->sub_userId ?? 'N/A',
                'sub_email' => $t->sub_email ?? 'N/A',
                'wallet_balance' => (float) $t->wallet_balance ?? 0,
                'walletTransaction_Created' => $t->wallet_transaction_created ?? 'N/A',
                'currency' => $t->currency_code ?? 'N/A',
                'provider' => $t->provider ?? 'N/A',
                'total_amount' => (float) $t->total_amount ?? 0,
                'total_transactions' => (int) $t->total_transactions ?? 0,
                'last_transaction_date' => $t->last_transaction_date
                    ? \Carbon\Carbon::parse($t->last_transaction_date)->format('d/m/Y')
                    : 'N/A',
            ];
        });

        // Regroupement par pays
        return $ranking
            ->groupBy('sub_countrie')
            ->map(fn($group) => $group->values());
    }

    public function getSubDistributorsWalletTransactionFiveRanking($walletModel, string $walletTransactionTable = 'wallet_transactions')
    {

        $transactions = $walletModel::select(
            'sid_users.firstname as sub_distributor',
            'sid_users.id as sub_userId',
            'sid_users.email as sub_email',
            'countries.name as country_name',
            'cities.name as citie_name',
            'currencies.code as currency_code',
            DB::raw("MAX({$walletTransactionTable}.created_at) as last_transaction_date"),
            DB::raw('MAX(wallets.amount) as wallet_balance'),
            'providers.name as provider',
            DB::raw("SUM({$walletTransactionTable}.amount) as total_amount"),
            DB::raw("COUNT({$walletTransactionTable}.id) as total_transactions")
        )
            ->join('wallets', "{$walletTransactionTable}.wallet_id", '=', 'wallets.id')
            ->join('currencies', 'wallets.currencie_id', '=', 'currencies.id')
            ->join('sid_users', 'wallets.sid_user_id', '=', 'sid_users.id')
            ->join('providers', "{$walletTransactionTable}.provider_id", '=', 'providers.id')
            ->join('countries', 'sid_users.country_id', '=', 'countries.id')
            ->join('cities', 'sid_users.city_id', '=', 'cities.id')
            ->where("{$walletTransactionTable}.status", 'SUCCESS')

            ->where('sid_users.is_under_distributer', true)
            ->groupBy(
                'sid_users.id',
                'sid_users.firstname',
                'sid_users.email',
                'providers.id',
                'providers.name',
                'countries.id',
                'cities.id',
                'countries.name',
                'cities.name',
                'currencies.code'
            )
            ->orderByDesc('total_amount')
            ->get();

        $ranking = $transactions->map(function ($t) {

            return [
                'sub_distributor' => $t->sub_distributor ?? 'N/A',
                'sub_countrie' => $t->country_name ?? 'N/A',
                'sub_citie' => $t->citie_name ?? 'N/A',
                'sub_userId' => $t->sub_userId ?? 'N/A',
                'sub_email' => $t->sub_email ?? 'N/A',
                'wallet_balance' => (float) $t->wallet_balance ?? 0,
                'currency' => $t->currency_code ?? 'N/A',
                'provider' => $t->provider ?? 'N/A',
                'total_amount' => (float) $t->total_amount ?? 0,
                'total_transactions' => (int) $t->total_transactions ?? 0,
                'last_transaction_date' => $t->last_transaction_date
                    ? \Carbon\Carbon::parse($t->last_transaction_date)->format('d/m/Y')
                    : 'N/A',
            ];
        });

        // Regrouper par pays
        $groupedRanking = $ranking->groupBy('sub_countrie')->map(function ($group) {
            // Ne garder que les 5 premiers par pays
            return $group->sortByDesc('total_amount')->take(5)->values();
        });

        return $groupedRanking;
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
