<?php

namespace App\Http\Services;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Sid\SidUser;

class HomeService
{
    private ?array $firmMemberWalletIds = null;

    private function todayRange(): array
    {
        return [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()];
    }

    private function currentMonthRange(): array
    {
        return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
    }

    // HOME Prospects valeurs

    public function getDaysReloadProspectAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  propect()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth(DB::raw('created_at'), now()->month)
            ->sum('amount');

        return $totalAmount;
    }


    public function getTodayReloadProspectAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  propect()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->sum('amount');

        return $totalAmount;
    }

    public function getDaysProspectTransaction($walletModel, string $wallet_transaction, string $transaction_days, string $walletMy, string $transaction_status, string $wallet_amount)
    {



        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereBetween($transaction_days, [$startOfMonth, $endOfMonth])
            ->where($transaction_status, 'SUCCESS');

        $query->where(function ($query) use ($wallet_transaction) {
            $query->where($wallet_transaction, propect()->wallet->id);
        });

        return $query->sum($wallet_amount);
    }

    public function getTransactionAllProspectCount($walletModel, $wallet_transactions, $transaction_days, $walletMy)
    {

        $query =  $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()]);

        $query->where(function ($query) use ($wallet_transactions) {
            $query->where($wallet_transactions, propect()->wallet->id);
        });


        return $query->count();
    }

    public function getTodayTransactionProvidersProspectAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount, $providers)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where('provider_id', $providers)
            ->where($transaction_status, 'SUCCESS');


        $query->where(function ($query) use ($wallet_transaction) {
            $query->where($wallet_transaction, propect()->wallet->id);
        });

        return $query->sum($wallet_amount);
    }

    public function getTransactionCountProspectReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', propect()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();

        return $totalCount;
    }

    public function getTodayTransactionProspectAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where($transaction_status, 'SUCCESS');


        $query->where(function ($query) use ($wallet_transaction) {
            $query->where($wallet_transaction, propect()->wallet->id);
        });


        return $query->sum($wallet_amount);
    }

    public function getTransactionProspectCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where($wallet_status, $status);

        $query->where(function ($query) use ($wallet_transactions) {
            $query->where($wallet_transactions, propect()->wallet->id);
        });

        return $query->count();
    }


    // home Compagnie valeurs
    public function getTransactionFirmCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where($wallet_status, $status);

        if (sabdy()->is_company) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, firm()->wallet->id);
            });
        }

        return $query->count();
    }

    public function getTransactionCountCompagnieReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();

        return $totalCount;
    }

    public function getDaysTransactionCompanieGain($walletModel)
    {

        return $walletModel::whereIn('wallet_id', $this->firmMemberWalletIds())
            ->whereStatus('SUCCESS')
            ->whereMonth('created_at', now()->month)
            ->sum('commission_companie');
    }

    public function getTodayTransactionCommissionCompanieAmountTotal($walletModel)
    {

        return $walletModel::whereIn('wallet_id', $this->firmMemberWalletIds())
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->sum('commission_companie');
    }

    public function getTodayTransactionUndersAmountTotal($walletModel)
    {

        return $walletModel::whereIn('wallet_id', $this->firmMemberWalletIds())
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->sum('amount');
    }

    public function getTodayReloadAmountCompagnieTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->sum('amount');

        return $totalAmount;
    }

    public function getDaysReloadAmountCompagnieTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth(DB::raw('created_at'), now()->month)
            ->sum('amount');

        return $totalAmount;
    }

    public function getTodayTransactionCompagnieAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where($transaction_status, 'SUCCESS');

        if (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, firm()->wallet->id);
            });
        }

        return $query->sum($wallet_amount);
    }


    public function getTransactionCompagnieCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where($wallet_status, $status);

        if (sabdy()->is_company) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, firm()->wallet->id);
            });
        }

        return $query->count();
    }

    public function getTransactionUsersCount($walletModel,  $status)
    {


        return $walletModel::whereIn('wallet_id', $this->firmMemberWalletIds())
            ->where('status', $status)->count();
    }

    /**
     * Wallets des membres de la compagnie (hors compte compagnie), chargés une seule fois.
     */
    private function firmMemberWalletIds(): array
    {
        return $this->firmMemberWalletIds ??= SidUser::where('company_id', firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->with('wallet')
            ->get()
            ->pluck('wallet.id')
            ->filter()
            ->all();
    }

    public function getDaysCompagnieTransaction($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        if ($amount !== null) {
            $totalAmount += $amount;
        }


        return $totalAmount;
    }


    public function getDaysTransactionUnderMonth($walletModel)
    {

        return $walletModel::whereIn('wallet_id', $this->firmMemberWalletIds())
            ->whereStatus('SUCCESS')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
    }

    public function getMastersGainProperty($walletModel)
    {
        $reloads = [];

        // Une seule requête groupée par mois au lieu de 12
        $sums = $walletModel::where('wallet_id', firm()->wallet->id)
            ->where('status', 'SUCCESS')
            ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        for ($month = 1; $month <= 12; $month++) {
            $reloads[] = 0 + ($sums[$month] ?? 0);
        }

        return $reloads;
    }



    // Sous-distributeurs - Classement des transactions de portefeuille avec filtres
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
            'providers.name as provider',                // ← on garde le provider
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

        // ✅ Suppression du filtre provider_id
        // if (!empty($filters['provider_id'])) { ... }  // ← à enlever

        // Filtre par pays (si présent)
        if (!empty($filters['country_id'])) {
            $query->where('sid_users.country_id', $filters['country_id']);
        }

        // Filtre période (inchangé)
        if (!empty($filters['periode'])) {
            switch ($filters['periode']) {
                case 'today':
                    $query->whereBetween("{$walletTransactionTable}.created_at", [today(), today()->endOfDay()]);
                    break;
                case 'week':
                    $query->whereBetween("{$walletTransactionTable}.created_at", [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween("{$walletTransactionTable}.created_at", [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
                case 'year':
                default:
                    $query->whereYear("{$walletTransactionTable}.created_at", now()->year);
                    break;
            }
        } else {
            $query->whereYear("{$walletTransactionTable}.created_at", now()->year);
        }

        // ✅ Groupement incluant le provider
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

        // Transformation (on conserve la clé 'provider')
        $ranking = $transactions->map(function ($t) {
            return [
                'sub_distributor'     => $t->sub_distributor ?? 'N/A',
                'sub_countrie'        => $t->country_name ?? 'N/A',
                'sub_citie'           => $t->citie_name ?? 'N/A',
                'sub_userId'          => $t->sub_userId ?? 'N/A',
                'sub_email'           => $t->sub_email ?? 'N/A',
                'wallet_balance'      => (float) $t->wallet_balance ?? 0,
                'currency'            => $t->currency_code ?? 'N/A',
                'provider'            => $t->provider ?? 'N/A',        // ← réintégré
                'total_amount'        => (float) $t->total_amount ?? 0,
                'total_transactions'  => (int) $t->total_transactions ?? 0,
                'last_transaction_date' => $t->last_transaction_date
                    ? \Carbon\Carbon::parse($t->last_transaction_date)->format('d/m/Y')
                    : 'N/A',
            ];
        });

        // Regroupement par pays (optionnel)
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
        $query = SidUser::where($user, $status);

        if (sabdy()->is_com_zone) {
            $query->where('com_zone_id', sabdy()->id);
        }

        return $query->count();
    }


    public function getTodayReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', $this->todayRange())
            ->sum('amount');

        return $totalAmount;
    }

    public function getDaysReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', $this->currentMonthRange())
            ->sum('amount');

        return $totalAmount;
    }


    public function getTransactionCountReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', $this->todayRange())
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
            ->whereBetween($transaction_days, $this->todayRange());

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {

            $query->whereIn('wallets.sid_user_id', function ($sub) {
                $sub->select('id')
                    ->from('sid_users')
                    ->where('com_zone_id', sabdy()->id);
            });
        } else {

            // Regroupé : sans parenthèses, le orWhere annulait le filtre sur la date du jour
            $query->where(function ($query) use ($countryId) {
                $query->where('wallets.country_id', '!=', $countryId)
                    ->orWhere('wallets.country_id', '=', $countryId);
            });
        }


        return $query->count();
    }


    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereBetween($transaction_days, $this->todayRange())
            ->where($wallet_status, $status);

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {


            $query->whereIn('wallets.sid_user_id', function ($sub) {
                $sub->select('id')
                    ->from('sid_users')
                    ->where('com_zone_id', sabdy()->id);
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
            ->whereBetween($transaction_days, $this->todayRange())
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {
             $query->whereIn('wallets.sid_user_id', function($sub) {
            $sub->select('id')
                ->from('sid_users')
                ->where('com_zone_id', sabdy()->id);
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
            ->whereBetween($transaction_days, $this->todayRange())
            ->where('provider_id', $providers)
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {
            $query->whereIn('wallets.sid_user_id', function($sub) {
            $sub->select('id')
                ->from('sid_users')
                ->where('com_zone_id', sabdy()->id);
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
            ->whereBetween($transaction_days, $this->todayRange())
            ->where('provider_id', $providerId)
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {
            $query->whereIn('wallets.sid_user_id', function($sub) {
            $sub->select('id')
                ->from('sid_users')
                ->where('com_zone_id', sabdy()->id);
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

    // Nombre de transactions du jour (SUCCESS) pour un provider
    public function getTodayTransactionProviderCount($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $providerId)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereBetween($transaction_days, $this->todayRange())
            ->where('provider_id', $providerId)
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {
            $query->whereIn('wallets.sid_user_id', function ($sub) {
                $sub->select('id')
                    ->from('sid_users')
                    ->where('com_zone_id', sabdy()->id);
            });
        } else {

            // Mêmes filtres que getTodayTransactionProviderAmountTotal pour que nombre et montant concordent
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

    public function getTodayTransactionProviderTypeOneAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {


        if (sabdy()->is_distributer) {

            $query = $walletModel::whereBetween($transaction_days, $this->todayRange())
                ->where($transaction_status, 'SUCCESS');

            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {

            $query = $walletModel::join('wallets', 'wallets.id', $wallet_transaction)
                ->whereBetween($transaction_days, $this->todayRange())
                ->where($transaction_status, 'SUCCESS');

            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {
            $query = $walletModel::join('wallets', 'wallets.id', $wallet_transaction)
                ->whereBetween($transaction_days, $this->todayRange())
                ->where($transaction_status, 'SUCCESS');

            $query->whereIn('wallets.sid_user_id', function($sub) {
            $sub->select('id')
                ->from('sid_users')
                ->where('com_zone_id', sabdy()->id);
        });
        } else {

            $query = $walletModel::whereBetween($transaction_days, $this->todayRange())
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
            ->whereBetween($transaction_days, $this->todayRange())
            ->whereProviderType($providerId)
            ->where($transaction_status, 'SUCCESS');

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {
            $query->whereIn('wallets.sid_user_id', function($sub) {
            $sub->select('id')
                ->from('sid_users')
                ->where('com_zone_id', sabdy()->id);
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


    public function getDaysAmountTransaction(
        $walletModel,
        string $wallet_transaction,
        string $transaction_days,
        string $walletMy,
        string $transaction_status,
        string $wallet_amount
    ) {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $walletTransactionTable = (new $walletModel)->getTable();

        $query = $walletModel::join('wallets', function ($join) use ($wallet_transaction) {
            $join->on($wallet_transaction, '=', 'wallets.id');
        })
            // ✅ On filtre correctement le mois et l'année
            ->whereBetween("{$walletTransactionTable}.{$transaction_days}", [$startOfMonth, $endOfMonth])
            ->where("{$walletTransactionTable}.{$transaction_status}", 'SUCCESS');

        if (sabdy()->is_distributer) {
            $query->where("{$walletTransactionTable}.{$walletMy}", sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($sub) use ($wallet_transaction) {
                $sub->where($wallet_transaction, sabdy()->wallet->id);
            });
        } else {
            $query->where(function ($sub) use ($walletTransactionTable, $walletMy) {
                $sub->where("{$walletTransactionTable}.{$walletMy}", sabdy()->id)
                    ->orWhere("{$walletTransactionTable}.{$walletMy}", '!=', sabdy()->id);
            })->where(function ($sub) {
                $countryId = sabdy()->country->id;
                $sub->where('wallets.country_id', '!=', $countryId)
                    ->orWhere('wallets.country_id', '=', $countryId);
            });
        }

        return $query->sum("{$walletTransactionTable}.{$wallet_amount}");
    }

    // Sum amount month valeurs
    public function getDaysTransaction($walletModel, string $wallet_transaction, string $transaction_days, string $walletMy, string $transaction_status, string $wallet_amount)
    {



        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereBetween($transaction_days, [$startOfMonth, $endOfMonth])
            ->where($transaction_status, 'SUCCESS');

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {
            $query->whereIn('wallets.sid_user_id', function($sub) {
            $sub->select('id')
                ->from('sid_users')
                ->where('com_zone_id', sabdy()->id);
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
